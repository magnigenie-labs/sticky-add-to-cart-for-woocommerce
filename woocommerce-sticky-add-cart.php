<?php
/*
Plugin Name: WooCommerce Sticky Add To Cart
Plugin URI: https://www.magnigenie.com/downloads/woocommerce-sticky-add-to-cart-pro/
Description: This plugin allows you to add a sticky add to cart on single product pages.
Version: 1.5
Author: Magnigenie
Author URI: https://www.magnigenie.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// No direct file access
! defined( 'ABSPATH' ) AND exit;

define( 'WSAC_FILE', __FILE__ );
define( 'WSAC_PATH', plugin_dir_path( __FILE__ ) );
define( 'WSAC_BASE', plugin_basename( __FILE__ ));
define( 'WSAC_OPTIONS_FRAMEWORK_DIRECTORY', plugins_url( '/inc/', __FILE__ ) );
define( 'WSAC_OPTIONS_FRAMEWORK_PATH', dirname( __FILE__ ) . '/inc/' );
define( 'PLUGIN_NAME', 'WooCommerce Sticky Add To Cart' );
define( 'WSAC_PRO_LINK', 'https://www.magnigenie.com/downloads/woocommerce-sticky-add-to-cart-pro/' );

//Check if WooCommerce is installed and active
add_action( 'admin_init', 'wsac_check_required_plugins' );

if( ! function_exists( 'wsac_check_required_plugins' ) ) {
	function wsac_check_required_plugins() {
		if ( ! class_exists( 'woocommerce' ) ) {
			add_action( 'admin_notices', 'wsac_show_admin_notices'  );
		}
	}
}

/**
* Show admin notice if woocommerce plugin is not active and disable
* sticky add to cart pro plugin
*
* @param void
* @return void
*
*/
function wsac_show_admin_notices() {
?>
	<div id="message" class="error">
  	<p>WooCommerce Sticky Add To Cart requires <a href="http://www.woothemes.com/woocommerce/" target="_blank">WooCommerce</a> to be activated in order to work. Please install and activate <a href="<?php echo admin_url('/plugin-install.php?tab=search&amp;type=term&amp;s=WooCommerce'); ?>" target="">WooCommerce</a> first.</p>
  </div>
	<?php
	deactivate_plugins( '/sticky-add-to-cart-for-woocommerce/woocommerce-sticky-add-cart.php' );
}

/**
* Load the text domain for the plugin
*
* @param void
* @return void
*
*/
function wsac_load_textdomain() {
	load_plugin_textdomain( 'wsac', false, dirname( plugin_basename( __FILE__ ) ). '/languages/' );
}
add_action( 'plugins_loaded', 'wsac_load_textdomain' );

//Include necessary files for the plugin
require_once dirname( __FILE__ ) . '/inc/options-framework.php';
require_once dirname( __FILE__ ) . '/inc/wsac.php';

new WC_Sticky_Add_To_Cart();
