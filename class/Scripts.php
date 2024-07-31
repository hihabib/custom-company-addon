<?php
class CustomCompanyAddonScript {
    public function __construct() {
        add_action('wp_enqueue_scripts', [$this, 'scripts']);
    }

    public function scripts() {
        wp_enqueue_script('custom-company-addon-script', CUSTOM_COMPANY_ADDON_PLUGIN_DIR . "/scripts/script.js", [], CUSTOM_COMPANY_ADDON_VERSION, true);
        wp_localize_script('custom-company-addon-script','pageInfo', [
            'isArchive' => is_post_type_archive('company')
        ]);
    }
}