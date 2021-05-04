<?php
/*
Plugin Name: FourTwo - Multisite User Manager
Plugin URI:  https://fourtwoweb.com/wordpress/plugins/multisite-user-manager/
Description: A WordPress Multisite plugin that allows you easily add a user to all blogs in your multisite.
Version:     0.0.1
Author:      Christopher Spires
Author URI:  https://fourtwoweb.com
License:     GPLv2
License URI: https://www.gnu.org/licenses/gpl-3.0.html
Text Domain: fourtwo
Domain Path: /languages
*/

define('FOURTWO_MUM_VERSION', '0.0.1');
define('FOURTWO_MUM_TEXTDOMAIN', 'fourtwo');
define('FOURTWO_MUM_DIR', plugin_dir_path(__FILE__));
define('FOURTWO_MUM_URL', plugin_dir_url(__FILE__));

// Include all our class files
foreach (glob(FOURTWO_MUM_DIR . 'classes/*.php') as $file) {
	include_once $file;
}

// Load Plugin Assets
$fourtwo_mum_assets = new FourTwo_MUM_Assets();

// Add Menu Page
$fourtwo_mum_menu_page = new FourTwo_MUM_Admin_Page();

// Instantiate AJAX Calls
$fourtwo_mum_ajax = new FourTwo_MUM_Ajax();
