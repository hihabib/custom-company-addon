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
define("CUSTOM_COMPANY_ADDON_PLUGIN_DIR", plugin_dir_url(__FILE__));
define("CUSTOM_COMPANY_ADDON_VERSION", time());

require_once __DIR__ . "/class/Scripts.php";

new CustomCompanyAddonScript();