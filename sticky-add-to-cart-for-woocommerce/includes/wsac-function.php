<?php 
/**
 * WooCommerce Sticky Add To Cart Functions 
 *
 * @author   Magnigenie
 * @category  Admin
 * @version     1.0.0
 */
// No direct file access

! defined( 'ABSPATH' ) AND exit;

class Wsac_function {
	/**
	* Class constructor. 
	* Bootstraps the class and hooks required actions & filters.
	*/
	function __construct() {
		//Add action for login user ,  to "add to cart"  by ajax 
		add_action( 'wp_ajax_wsac_to_cart', array( $this ,  'wsac_to_cart' ) );
		//Add action for non log in user , to "add to cart" by ajax
		add_action( 'wp_ajax_nopriv_wsac_to_cart', array( $this ,  'wsac_to_cart' ) );
	}
	/**
	* Add to cart  using ajax 
	*/
	public function wsac_to_cart() {
		global $woocommerce ;
		if( isset( $_POST['id'] ) ) {
			$product_id = $_POST['id'];
			$woocommerce->cart->add_to_cart( $product_id );
			foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $values ) {
				$product = $values['data'];
				if ( $product->id == $product_id )
					$data = $product->get_data() ;
			}
			echo json_encode(array('success' => true , 'message' =>  $data['name'] .' has added to cart' , 'cart_url' => site_url('cart')) );
		}else{
			echo json_encode(array('success' => false , 'message' => 'Please try again'));
		}
		wp_die();
	}
}
new Wsac_function();