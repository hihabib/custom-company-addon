<?php
class CustomCompanyAddonScript {
    public function __construct() {
        add_action('wp_enqueue_scripts', [$this, 'scripts']);
    }

    public function scripts() {
        if (is_singular('company') || is_post_type_archive('company')){
            wp_enqueue_script('custom-company-addon-script', CUSTOM_COMPANY_ADDON_PLUGIN_DIR . "/scripts/script.js", [], CUSTOM_COMPANY_ADDON_VERSION, false);
            wp_localize_script('custom-company-addon-script','pageInfo', [
                'isArchive' => is_post_type_archive('company')
            ]);
        }

        wp_enqueue_script('company-search', CUSTOM_COMPANY_ADDON_PLUGIN_DIR . "/scripts/search-company.js", [], CUSTOM_COMPANY_ADDON_VERSION, true);
        wp_localize_script('company-search', 'pageInfo', [
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('search_company'),
                'notFoundShortcodeHTML' => do_shortcode('[elementor-template id="6692"]')
            ]
        );
    }

}