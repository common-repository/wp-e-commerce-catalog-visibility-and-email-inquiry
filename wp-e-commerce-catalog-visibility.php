<?php
/*
Plugin Name: WP e-Commerce Catalog Visibility Email inquiry PRO
Description: Remove add to cart function / hide the price from all or any product on your WP e-Commerce store. Apply to all not logged in users and individually apply to any logged in user role. Add product email inquiry button or hyperlink text to all or any product with fully mobile responsive pop-up email inquiry form.
Version: 2.0.0
Author: a3rev Software
Author URI: https://a3rev.com/
Text Domain: wp-e-commerce-catalog-visibility-and-email-inquiry
Domain Path: /languages
License: This software is under commercial license and copyright to A3 Revolution Software Development team

	WP e-Commerce Catalog Visibility Email inquiry. Plugin for the WP e-Commerce shopping Cart.
	Copyright© 2011 A3 Revolution Software Development team

	A3 Revolution Software Development team
	admin@a3rev.com
	PO Box 1170
	Gympie 4570
	QLD Australia
*/
?>
<?php
define('WPEC_PCF_FILE_PATH', dirname(__FILE__));
define('WPEC_PCF_DIR_NAME', basename(WPEC_PCF_FILE_PATH));
define('WPEC_PCF_FOLDER', dirname(plugin_basename(__FILE__)));
define('WPEC_PCF_URL', untrailingslashit(plugins_url('/', __FILE__)));
define('WPEC_PCF_DIR', WP_PLUGIN_DIR . '/' . WPEC_PCF_FOLDER);
define('WPEC_PCF_NAME', plugin_basename(__FILE__));
define('WPEC_PCF_IMAGES_URL', WPEC_PCF_URL . '/assets/images');
define('WPEC_PCF_JS_URL', WPEC_PCF_URL . '/assets/js');
define('WPEC_PCF_CSS_URL', WPEC_PCF_URL . '/assets/css');

/**
 * Load Localisation files.
 *
 * Note: the first-loaded translation file overrides any following ones if the same translation is present.
 *
 * Locales found in:
 * 		- WP_LANG_DIR/wp-e-commerce-catalog-visibility-and-email-inquiry/wp-e-commerce-catalog-visibility-and-email-inquiry-LOCALE.mo
 * 	 	- WP_LANG_DIR/plugins/wp-e-commerce-catalog-visibility-and-email-inquiry-LOCALE.mo
 * 	 	- /wp-content/plugins/wp-e-commerce-catalog-visibility-and-email-inquiry/languages/wp-e-commerce-catalog-visibility-and-email-inquiry-LOCALE.mo (which if not found falls back to)
 */
function wpec_pcf_plugin_textdomain() {
	$locale = apply_filters( 'plugin_locale', get_locale(), 'wp-e-commerce-catalog-visibility-and-email-inquiry' );

	load_textdomain( 'wp-e-commerce-catalog-visibility-and-email-inquiry', WP_LANG_DIR . '/wp-e-commerce-catalog-visibility-and-email-inquiry/wp-e-commerce-catalog-visibility-and-email-inquiry-' . $locale . '.mo' );
	load_plugin_textdomain( 'wp-e-commerce-catalog-visibility-and-email-inquiry', false, WPEC_PCF_FOLDER.'/languages' );
}

include ('admin/admin-ui.php');
include ('admin/admin-interface.php');

include ('admin/admin-pages/admin-rules-roles-page.php');
include ('admin/admin-pages/admin-email-inquiry-page.php');

include ('admin/admin-init.php');
include ('admin/less/sass.php');

include ('classes/class-pcf-functions.php');
include ('classes/class-pcf-hook.php');
include ('classes/class-pcf-metabox.php');

include ('admin/pcf-init.php');

/**
 * Call when the plugin is activated and deactivated
 */
register_activation_hook( __FILE__, 'wpec_pcf_install' );

?>