<?php
/*
* Plugin Name:       Custom Company Addon
* Description:       Handle the Custom Company functionalities with this plugin.
* Version:           1.0
* Requires at least: 5.2
* Requires PHP:      7.2
* Author:            Habibul Islam
* Author URI:        https://github.com/hihabib
* License:           GPL v2 or later
* License URI:       https://www.gnu.org/licenses/gpl-2.0.html
* Text Domain:       custom-comapny-addon-habib
*/
define("VERSION", time());
define("CUSTOM_COMPANY_ADDON_PLUGIN_DIR", plugin_dir_url(__FILE__));
define("CUSTOM_COMPANY_ADDON_VERSION", time());


require_once __DIR__ . "/class/Company.php";
new CustomCompanyAddonCompany();


add_action('wp', 'custom_company_addon');
function custom_company_addon()
{
    require_once __DIR__ . "/class/Scripts.php";
    new CustomCompanyAddonScript();

    if (is_singular('company') || is_post_type_archive('company')) :
        require_once __DIR__ . "/class/NanoShortCodes.php";
        new CustomCompanyAddonNanoShortCodes();

    endif;
}
