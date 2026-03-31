/**
 * Auto Justify Content — Admin Scripts
 *
 * @package AutoJustifyContent
 * @since 3.0.0
 */

(function ($) {
    'use strict';

    // -------------------------------------------------------------------------
    // Boot
    // -------------------------------------------------------------------------

    $(document).ready(function () {
        initTabs();
        initStylePicker();
        initLinesPicker();
        initColorPicker();
        initFontSelector();
        initPreview();
    });

    // -------------------------------------------------------------------------
    // Tab routing (client-side, no reload)
    // -------------------------------------------------------------------------

    function initTabs() {
        var $tabs     = $('.ajc-tab');
        var $contents = $('.ajc-tab-content');

        if (!$tabs.length) return;

        $tabs.on('click', function (e) {
            e.preventDefault();

            var href     = $(this).attr('href');
            var tabName  = href.split('tab=')[1];

            // Update URL without reload
            if (window.history && window.history.pushState) {
                var url = new URL(window.location.href);
                url.searchParams.set('tab', tabName);
                window.history.pushState({}, '', url.toString());
            }

            $tabs.removeClass('ajc-tab-active');
            $(this).addClass('ajc-tab-active');

            $contents.removeClass('ajc-tab-content-active');
            $contents.filter('[data-tab="' + tabName + '"]').addClass('ajc-tab-content-active');
        });

        // Browser back/forward
        $(window).on('popstate', function () {
            var params  = new URLSearchParams(window.location.search);
            var active  = params.get('tab') || 'general';

            $tabs.each(function () {
                var tabName = $(this).attr('href').split('tab=')[1];
                $(this).toggleClass('ajc-tab-active', tabName === active);
            });

            $contents.each(function () {
                $(this).toggleClass('ajc-tab-content-active', $(this).data('tab') === active);
            });
        });
    }

    // -------------------------------------------------------------------------
    // Style picker (Drop / Raised) — visual radio buttons
    // -------------------------------------------------------------------------

    function initStylePicker() {
        $(document).on('change', 'input[name="ajc_dc_style"]', function () {
            $('.ajc-style-option').removeClass('ajc-style-active');
            $(this).closest('.ajc-style-option').addClass('ajc-style-active');
            updatePreview();
        });

        // Clicking the label div itself
        $(document).on('click', '.ajc-style-option', function () {
            var $radio = $(this).find('input[type="radio"]');
            $radio.prop('checked', true).trigger('change');
        });
    }

    // -------------------------------------------------------------------------
    // Lines picker (2 / 3 / 4) — visual radio buttons
    // -------------------------------------------------------------------------

    function initLinesPicker() {
        $(document).on('change', 'input[name="ajc_dc_lines"]', function () {
            $('.ajc-lines-option').removeClass('ajc-lines-active');
            $(this).closest('.ajc-lines-option').addClass('ajc-lines-active');
            updatePreview();
        });

        $(document).on('click', '.ajc-lines-option', function () {
            var $radio = $(this).find('input[type="radio"]');
            $radio.prop('checked', true).trigger('change');
        });
    }

    // -------------------------------------------------------------------------
    // WordPress colour picker (Iris)
    // -------------------------------------------------------------------------

    function initColorPicker() {
        var $colorInput = $('#ajc_dc_color');

        if (!$colorInput.length || typeof $.fn.wpColorPicker === 'undefined') return;

        $colorInput.wpColorPicker({
            change: function (event, ui) {
                // Debounce slightly so Iris animation doesn't fire per-frame
                clearTimeout(ajcColorTimer);
                ajcColorTimer = setTimeout(function () {
                    updatePreview();
                }, 80);
            },
            clear: function () {
                updatePreview();
            }
        });
    }

    var ajcColorTimer;

    // -------------------------------------------------------------------------
    // Font selector — show/hide custom field, trigger preview
    // -------------------------------------------------------------------------

    function initFontSelector() {
        var $fontSelect     = $('#ajc_dc_font');
        var $customRow      = $('#ajcCustomFontRow');
        var $customInput    = $('#ajc_dc_custom_font');

        if (!$fontSelect.length) return;

        $fontSelect.on('change', function () {
            var val = $(this).val();

            if (val === 'custom') {
                $customRow.removeClass('ajc-hidden');
            } else {
                $customRow.addClass('ajc-hidden');
            }

            updatePreview();
        });

        // Custom font input — debounced preview update
        $customInput.on('input', function () {
            clearTimeout(ajcCustomFontTimer);
            ajcCustomFontTimer = setTimeout(updatePreview, 400);
        });
    }

    var ajcCustomFontTimer;

    // -------------------------------------------------------------------------
    // Live Preview
    // -------------------------------------------------------------------------

    // Font size map keyed by line-span value
    var fontSizes = { '2': '2.8em', '3': '4.0em', '4': '5.4em' };

    // Tracks the currently-loaded Google Font param so we don't re-request it
    var loadedGoogleFont = null;

    function initPreview() {
        if (typeof ajcData === 'undefined') return;

        // Render initial state using saved values passed from PHP
        applyPreviewStyles(
            ajcData.currentFont,
            ajcData.currentColor,
            ajcData.currentLines,
            ajcData.currentStyle,
            ajcData.customFont
        );
    }

    function updatePreview() {
        if (typeof ajcData === 'undefined') return;

        var fontKey    = $('#ajc_dc_font').val()              || ajcData.currentFont;
        var color      = getCurrentColor();
        var lines      = $('input[name="ajc_dc_lines"]:checked').val()  || String(ajcData.currentLines);
        var style      = $('input[name="ajc_dc_style"]:checked').val()  || ajcData.currentStyle;
        var customFont = $('#ajc_dc_custom_font').val()       || '';

        applyPreviewStyles(fontKey, color, lines, style, customFont);
    }

    /**
     * Resolves current colour from the Iris picker or the raw input.
     */
    function getCurrentColor() {
        var $input = $('#ajc_dc_color');

        // Try to get the colour from the Iris picker container first
        var $container = $input.closest('.wp-picker-container');
        if ($container.length) {
            var irisColor = $container.find('.wp-color-result').css('background-color');
            if (irisColor && irisColor !== 'transparent') {
                return irisColor; // RGB string — CSS accepts it fine
            }
        }

        // Fallback to raw input value
        return $input.val() || ajcData.currentColor;
    }

    /**
     * Loads a Google Font if needed, then applies all preview styles.
     */
    function applyPreviewStyles(fontKey, color, lines, style, customFont) {
        var fonts      = ajcData.fonts;
        var fontData   = fonts[fontKey] || fonts['playfair'];
        var googleParam = fontData.google || null;

        // Show/hide GDPR notice
        if (googleParam) {
            $('#ajcPreviewGdpr').addClass('ajc-visible');
        } else {
            $('#ajcPreviewGdpr').removeClass('ajc-visible');
        }

        // Resolve the CSS font-family stack
        var fontStack;
        if (fontKey === 'custom' && customFont.trim() !== '') {
            fontStack   = customFont.trim();
            googleParam = null; // custom fonts are user-loaded
        } else {
            fontStack = fontData.stack || 'inherit';
        }

        // Load Google Font for preview if it has changed
        if (googleParam && googleParam !== loadedGoogleFont) {
            loadedGoogleFont = googleParam;
            loadPreviewGoogleFont(googleParam).then(function () {
                renderPreviewCap(fontStack, color, lines, style);
            });
        } else {
            renderPreviewCap(fontStack, color, lines, style);
        }
    }

    /**
     * Applies computed CSS directly to the preview cap element.
     */
    function renderPreviewCap(fontStack, color, lines, style) {
        var $cap      = $('#ajcPreviewCap');
        var $preview  = $('#ajcPreviewBody');
        var size      = fontSizes[String(lines)] || '4.0em';

        var lineHeight, margin;
        if (style === 'raised') {
            lineHeight = '1.0';
            margin     = '0 0.1em 0 0';
        } else {
            lineHeight = '0.83';
            margin     = '0.04em 0.1em -0.05em 0';
        }

        $preview.removeClass('ajc-preview-loading');

        $cap.css({
            'font-family': fontStack,
            'font-size':   size,
            'color':       color,
            'line-height': lineHeight,
            'margin':      margin
        });
    }

    /**
     * Dynamically injects a <link> tag to load a Google Font for the preview.
     * Returns a Promise that resolves when the font stylesheet has loaded.
     */
    function loadPreviewGoogleFont(googleParam) {
        var $preview  = $('#ajcPreviewBody');
        $preview.addClass('ajc-preview-loading');

        return new Promise(function (resolve) {
            // Remove any previously injected preview font
            $('#ajcPreviewFontLink').remove();

            var link       = document.createElement('link');
            link.id        = 'ajcPreviewFontLink';
            link.rel       = 'stylesheet';
            link.href      = 'https://fonts.googleapis.com/css2?family=' + googleParam + '&display=swap';
            link.onload    = function () {
                $preview.removeClass('ajc-preview-loading');
                resolve();
            };
            link.onerror   = function () {
                $preview.removeClass('ajc-preview-loading');
                resolve(); // degrade gracefully
            };

            document.head.appendChild(link);
        });
    }

}(jQuery));
