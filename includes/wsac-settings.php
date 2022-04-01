<?php 
/**
 * WooCommerce Sticky Add To Cart Settings
 *
 * @author    Magnigenie
 * @category  Admin
 * @version   1.2
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if (  class_exists( 'WC_Settings_Page' ) ) :

	/**
	 * WC_Settings_Accounts
	 */
	class Wsac_Settings extends WC_Settings_Page {

	/**
	* Constructor.
	*/
	public function __construct() {
		$this->id    = 'product_sticky_bar';
		$this->label = __( 'Sticky Cart', 'wsac' );
		add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
		add_action( 'woocommerce_settings_' . $this->id, array( $this, 'output' ) );
		add_action( 'woocommerce_settings_save_' . $this->id, array( $this, 'save' ) );
	}

	/**
	* Get settings array
	*
	* @return array
	*/
	public function get_settings() {

		return apply_filters( 'woocommerce_' . $this->id . '_settings', array(

			array( 'title' => __( 'Woocommerce Sticky Add To Cart', 'wsac' ), 'type' => 'title', 'desc' => '', 'id' => 'sticky_bar_title' ),
				array(
					'title'		=> __( 'Enable', 'wsac' ),
					'desc'    => __( 'Enable sticky add to cart option', 'wsac' ),
					'type'    => 'checkbox',
					'id'    	=> 'wsac[enable]',
					'default' => 'no'
				),
				array(
					'title'   => __( 'Enable on Desktop', 'wsac' ),
					'desc'    => __( 'Enable sticky add to cart option on desktop', 'wsacp' ),
					'type'    => 'checkbox',
					'id'    	=> 'wsac[enable_desktop]',
					'default' => 'yes'
				),
				array(
					'title'   => __( 'Enable on mobile', 'wsac' ),
					'desc'    => __( 'Enable sticky add to cart option on mobile', 'wsac' ),
					'type'    => 'checkbox',
					'id'    	=> 'wsac[enable_mobile]',
					'default' => 'no'
				),
				array(
					'title'   => __( 'Always visible', 'wsac' ),
					'desc'    => __( 'Make sticky bar always visible on single product pages', 'wsac' ),
					'type'    => 'checkbox',
					'id'    	=> 'wsac[on_load_page]',
					'default' => 'no'
				),
				array(
					'title' 	=> __( 'Sticky bar position', 'wsac' ),
					'type' 		=> 'select',
					'class'		=> 'wc-enhanced-select',
					'desc'    	=> __( 'Select a position to display sticky bar , Default will bottom', 'wsac' ),
					'id'		=> 'wsac[fix_postion]',
					'options' 	=> array('top' => 'Top' , 'bottom' => 'Bottom'),
					'default' 	=> 'bottom'
				),
				array(
					'title'   	=> __( 'Sticky bar\'s height', 'wsac' ),
					'desc'    	=> __( 'Set height for sticky bar  in px. Blank will assume as auto', 'wsac' ),
					'id'     		=> 'wsac[height]',
					'type'    	=> 'text',
					'css'    		=> 'width:200px',
					'default' 	=> '60',
					'desc_tip'	=>  true
				),
				array(
					'title'    	=> __( 'Sticky bar\'s background', 'wsac' ),
					'desc'     	=> __( 'Select background color for sticky bar.', 'wsac' ),
					'id'     		=> 'wsac[background]',
					'type'     	=> 'color',
					'css'    		=> 'width:100px',
					'default'   => '#222930',
					'desc_tip'  =>  true
				),
				array(
					'title'    	=> __( 'Star background color', 'wsac' ),
					'desc'     	=> __( 'Select background color for stat.', 'wsac' ),
					'id'     		=> 'wsac[star-background]',
					'type'     	=> 'color',
					'css'    		=> 'width:100px',
					'default'   => '#fff',
					'desc_tip'  =>  true
				),
				array(
					'title'    	=> __( 'Star color', 'wsac' ),
					'desc'     	=> __( 'Select star color.', 'wsac' ),
					'id'     		=> 'wsac[star-color]',
					'type'     	=> 'color',
					'css'    		=> 'width:100px',
					'default'   => '#cd534a',
					'desc_tip'  =>  true
				),
				array(
					'title'    	=> __( 'Sticky bar\'s border color', 'wsac' ),
					'desc'     	=> __( 'Select border color for sticky bar.', 'wsac' ),
					'id'     		=> 'wsac[border]',
					'type'     	=> 'color',
					'css'    		=> 'width:100px',
					'default'   => '#cd534a',
					'desc_tip'  =>  true
				),
				array(
					'title'    	=> __( 'Customize cart button\'s text', 'wsac' ),
					'desc'     	=> __( 'Customize text on cart button', 'wsac' ),
					'id'     		=> 'wsac[text]',
					'type'     	=> 'text',
					'css'    		=> 'width:200px',
					'default'   => 'Add to cart',
					'desc_tip'  =>  true
				),
				array(
					'title'    	=> __( 'Customize variable product button\'s text ', 'wsac' ),
					'desc'     	=> __( 'Customize text on cart button when product type variable', 'wsac' ),
					'id'     		=> 'wsac[variable_text]',
					'type'     	=> 'text',
					'css'    		=> 'width:200px',
					'default'   => 'Show Options',
					'desc_tip'  =>  true
				),
				array(
					'title'   	=> __( 'Cart button background', 'wsac' ),
					'desc'    	=> __( 'Set background color for cart button', 'wsac' ),
					'type'    	=> 'color',
					'css'    		=> 'width:100px',
					'id'   			=> 'wsac[cart_background]',
					'default'   => '#cd534a',
					'desc_tip'  =>  true
				),
				array(
					'title'    	=> __( 'Button text color', 'wsac' ),
					'desc'    	=> __( 'Text color for cart button.', 'wsac' ),
					'type'     	=> 'color',
					'css'    		=> 'width:100px',
					'id'    		=> 'wsac[text_color]',
					'default'   => '#fff',
					'desc_tip'  =>  true
				),
				array(
					'title'    	=> __( 'Badge text color of price', 'wsac' ),
					'desc'    	=> __( 'Text color for badge of price.', 'wsac' ),
					'type'     	=> 'color',
					'css'    		=> 'width:100px',
					'id'    		=> 'wsac[text_color_badge]',
					'default'   => '#fff',
					'desc_tip'  =>  true
				),
				array(
					'title'   	=> __( 'Badge background of price', 'wsac' ),
					'desc'    	=> __( 'Set background color for badge of price', 'wsac' ),
					'type'    	=> 'color',
					'css'    		=> 'width:100px',
					'id'   			=> 'wsac[badge_background]',
					'default'   => '#cd534a',
					'desc_tip'  =>  true
				),
				array(
					'title'   	=> __( 'Regular price strike color', 'wsac' ),
					'desc'    	=> __( 'Set strike color  of  regular price', 'wsac' ),
					'type'    	=> 'color',
					'css'    		=> 'width:100px',
					'id'   			=> 'wsac[strike_color]',
					'default'   => '#cd534a',
					'desc_tip'  =>  true
				),
				array(
					'title'   	=> __( 'Text color', 'wsac' ),
					'desc'    	=> __( 'Set color to text of sticky bar', 'wsac' ),
					'type'    	=> 'color',
					'css'    		=> 'width:100px',
					'id'   			=> 'wsac[sticky_text]',
					'default'   => '#ffffff',
					'desc_tip'  =>  true
				),
				array(
					'title' 	=> __( 'Select cart button shape', 'wsac' ),
					'type' 		=> 'select',
					'class'		=> 'wc-enhanced-select',
					'desc'    	=> __( 'Select a shape of cart button either rounded or square , Default will rounded', 'wsac' ),
					'id'		=> 'wsac[cart_shape]',
					'options' 	=> array('round' => 'Round' , 'square' => 'Square'),
					'default' 	=> 'square'
				),
				array(
					'title' 	=> __( 'Select badge shape of price', 'wsac' ),
					'type' 		=> 'select',
					'class'		=> 'wc-enhanced-select',
					'desc'    	=> __( 'Select a shape of badge shape either rounded or square , Default will rounded', 'wsac' ),
					'id'		=> 'wsac[badge_shape]',
					'options' 	=> array('round' => 'Round' , 'square' => 'Square'),
					'default' 	=> 'square'
				),
				array( 'type' => 'sectionend', 'id' => 'product_sticky_bar' ),

			) ); // End pages settings
	}
}
new Wsac_Settings();

endif;