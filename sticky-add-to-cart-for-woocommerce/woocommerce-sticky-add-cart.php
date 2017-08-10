<?php
/*
Plugin Name: WooCommerce Sticky Add To Cart
Plugin URI: https://wordpress.org/plugins/sticky-add-to-cart-for-woocommerce/
Description: This plugin allows you to add a sticky add to cart on single product pages.
Version: 1.0
Author: Magnigenie
Author URI: http://magnigenie.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// No direct file access
! defined( 'ABSPATH' ) AND exit;

define( 'WSAC_FILE', __FILE__ );
define( 'WSAC_PATH', plugin_dir_path( __FILE__ ) );
define( 'WSAC_BASE', plugin_basename( __FILE__ ) );

require WSAC_PATH . '/includes/class-wsac.php';
new WC_Sticky_Add_To_Cart();