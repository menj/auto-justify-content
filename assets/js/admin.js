/**
 * Auto Justify Content - Admin Scripts
 *
 * @package AutoJustifyContent
 * @since 2.0.0
 */

(function () {
    'use strict';

    /**
     * Initialize when DOM is ready
     */
    document.addEventListener('DOMContentLoaded', function () {
        initTabs();
        initToggleLabels();
    });

    /**
     * Tab Navigation
     * Handles client-side tab switching without page reload
     */
    function initTabs() {
        const tabs = document.querySelectorAll('.ajc-tab');
        const contents = document.querySelectorAll('.ajc-tab-content');

        if (!tabs.length || !contents.length) {
            return;
        }

        tabs.forEach(function (tab) {
            tab.addEventListener('click', function (e) {
                e.preventDefault();

                const targetTab = this.href.split('tab=')[1];

                // Update URL without reload
                const url = new URL(window.location);
                url.searchParams.set('tab', targetTab);
                window.history.pushState({}, '', url);

                // Update active tab
                tabs.forEach(function (t) {
                    t.classList.remove('ajc-tab-active');
                });
                this.classList.add('ajc-tab-active');

                // Update visible content
                contents.forEach(function (content) {
                    content.classList.remove('ajc-tab-content-active');
                    if (content.dataset.tab === targetTab) {
                        content.classList.add('ajc-tab-content-active');
                    }
                });
            });
        });

        // Handle browser back/forward
        window.addEventListener('popstate', function () {
            const urlParams = new URLSearchParams(window.location.search);
            const activeTab = urlParams.get('tab') || 'general';

            tabs.forEach(function (tab) {
                const tabName = tab.href.split('tab=')[1];
                tab.classList.toggle('ajc-tab-active', tabName === activeTab);
            });

            contents.forEach(function (content) {
                content.classList.toggle('ajc-tab-content-active', content.dataset.tab === activeTab);
            });
        });
    }

    /**
     * Toggle Label Click Handler
     * Allows clicking on labels to toggle their associated checkboxes
     */
    function initToggleLabels() {
        const labels = document.querySelectorAll('.ajc-setting-info label[for]');

        labels.forEach(function (label) {
            label.addEventListener('click', function () {
                const checkbox = document.getElementById(this.getAttribute('for'));
                if (checkbox && checkbox.type === 'checkbox') {
                    checkbox.checked = !checkbox.checked;
                    checkbox.dispatchEvent(new Event('change'));
                }
            });
        });
    }

})();
