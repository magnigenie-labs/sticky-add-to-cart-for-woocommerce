<?php 
/**
 * WooCommerce Sticky Add To  Cart
 *
 * @author   Magnigenie
 * @category  Admin
 * @version     1.0
 */

! defined( 'ABSPATH' ) AND exit; // No direct file access

class WC_Sticky_Add_To_Cart {
	/**
	* Class constructor. 
	* Bootstraps the class and hooks required actions & filters.
	*/
	function __construct() {

		//Check if woocommerce plugin is installed.
    add_action( 'admin_notices', array( $this, 'check_required_plugins' ) );
    
    //Add init function to frontend
		add_action( 'wp', array($this, 'init') );

		//Add sticky bar tab to woocommerce settings
		add_filter( 'woocommerce_get_settings_pages', array( $this, 'wsac_settings' ) );

		//Add all js script and css to sticky bar
		add_action( 'wp_enqueue_scripts',  array( $this, 'wsac_enque_scripts' ) );

		//Add setting link for the admin settings
    add_filter( "plugin_action_links_". WSAC_BASE, array( $this, 'wsac_settings_link' ) );
	}

	/**
	*
	* Check if woocommerce is installed and activated and if not
	* activated then deactivate WooCommerce product sticky bar.
	*
	*/
  public function check_required_plugins() {
  	//Check if woocommerce is installed and activated
    if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) { ?>
    	<div id="message" class="error">
      	<p>WooCommerce Sticky Add To Cart requires <a href="http://www.woothemes.com/woocommerce/" target="_blank">WooCommerce</a> to be activated in order to work. Please install and activate <a href="<?php echo admin_url('/plugin-install.php?tab=search&amp;type=term&amp;s=WooCommerce'); ?>" target="">WooCommerce</a> first.</p>
      </div>
      <?php deactivate_plugins( '/woocommerce-sticky-add-cart/woocommerce-sticky-add-cart.php' );
    }
  }
	
	/**
	* Add new admin setting page for woocommerce sticky add to cart settings.
	*
	* @param array $settings an array of existing setting pages.
	* @return array of setting pages along with sticky bar settings page.
	*
	*/
	public function wsac_settings( $settings ) {
		$settings[] = include 'wsac-settings.php';
		return $settings;
	}

	/**
	* Add init function to get html sticky bar 
	*/
	public function init() {
		do_action( 'add_sticky_bar' , get_option( 'wsac' ) );
	}

	/**
	*
	* Add necessary js and css files for sticky bar
	*
	*/
	public function wsac_enque_scripts() {
		// Get all admin settings value 
		$options = get_option( 'wsac' );
		// Set height  for sticky bar 
		$height = ( empty( $options['height'] )? '60px' : $options['height'].'px' );
		// Set badge shape 
		$badge_shape = ( $options['badge_shape'] === 'round' ? '32px' : '0px' ) ;
		// Set button shape 
		$cart_btn_shape = ( $options['cart_shape'] === 'round' ? '32px' : '0px' );
		// Set for inline css 
		$css = ".mg-wsac-fix-sticky-bar{ background : $options[background]  ; $options[fix_postion] : 0; }";
		$css .= ".height {height: $height;position: relative;}";
		$css .= "@media screen  and (max-width: 600px) { .right-border {border-right: none;}}";
		$css .= ".right-border{border-right: 1px solid $options[border]}";
		$css .= ".mg-wsac-btn{ background : $options[cart_background] ; color : $options[text_color]}";
		$css .= ".w3-badge{border-radius : $badge_shape ; color : $options[text_color_badge] ; background : $options[badge_background] }";
		$css .= ".stky-strike{color : $options[strike_color]}";
		$css .= ".stky-reglr-price , .stky-prdct-name {color : $options[sticky_text]}";
		$css .= ".w3-round-xxlarge{ border-radius : $cart_btn_shape }";
		//Load rateyo jstext_color
		wp_enqueue_script( 'rateyo-js', plugins_url( 'includes/assets/js/jquery.rateyo.js', WSAC_FILE ) , array( 'jquery' ), '1.0.0', true);
		//Load custom js
		wp_enqueue_script( 'wsac-customjs', plugins_url( 'includes/assets/js/custom.js', WSAC_FILE ) , array( 'jquery' ), '1.0.0', true);
		//Load font awesome css
		wp_enqueue_style( 'font-awesome-css', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css' , array(), '3.3.7', false);
		//Load w3 css
		wp_enqueue_style( 'w3-css', plugins_url( 'includes/assets/css/w3.css', WSAC_FILE ) , array(), '1.0.0', false);
		//Load rateyo css
		wp_enqueue_style( 'jquery-reteyo-css', plugins_url( 'includes/assets/css/jquery.rateyo.css', WSAC_FILE ) , array(), '1.0.0', false);
		//Load custom css
		wp_enqueue_style( 'wsac-custom-css', plugins_url( 'includes/assets/css/custom.css', WSAC_FILE ) , array(), '1.0.0', false);
		//Add inline css to custom css
		wp_add_inline_style( 'wsac-custom-css', $css );
		//Localize for custom js 
		wp_localize_script( 'wsac-customjs' , 'wsac' , array(
			'ajaxurl' 				=> admin_url( 'admin-ajax.php' ),
			'mobile_enable' 	=> $options['enable_mobile'],
			'star_background' => $options['star-background'],
			'star_color' 			=> $options['star-color'] ,
			'btn_message' 		=> $options['text'] ,
		));
	}

	/**
	* Add new link for the settings under plugin links
	*
	* @param array $links an array of existing links.
	* @return array of links  along with sticky bar settings link.
	*
	*/
  public function wsac_settings_link($links) {
  	$settings_link = '<a href="'.admin_url('admin.php?page=wc-settings&tab=product_sticky_bar').'">Settings</a>'; 
    array_unshift( $links, $settings_link ); 
    return $links; 
  }

  
}