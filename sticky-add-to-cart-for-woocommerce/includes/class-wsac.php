<?php 
/**
 * WooCommerce Sticky Add To  Cart
 *
 * @author   Magnigenie
 * @category  Admin
 * @version     1.1
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
		add_action( 'wp_head', array($this, 'init') );

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
		$options = get_option( 'wsac' );
		global $woocommerce;
		global $post;
		//Check page is single product And admin setting's meta key has been saved
		if ($options['enable'] == 'yes' && is_product() ) : 
			//get woocommerce object according to specific product
			$product_details = new WC_Product_Variable($post->ID);
			$class = $options['fix_postion'];

		?>
			<!-- Main Container of stick bar -->
				<div class="mg-wsac-fix-sticky-bar <?php echo $class; ?>  w3-container" style=" position: fixed ; z-index: 99999 ; width: 100% ;">
					<!-- Main Row of sticky bar -->
					<div class="w3-row " >
						<!-- First section -->
						<div class="col-width  w3-container  right-border fst-cont-div"  >
							<div class="w3-row  height"  >
								<!-- Image Section -->
								<div class="img-col-width w3-container  w3-center  padding" >
									<div class="img center-blck-img " style=" ">
										<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID ), 'single-post-thumbnail' );?>
							    	<img src="<?= $image[0]?>" class="w3-circle " alt="" style="height: 100%" >
								  </div>
								</div>
								<!-- End of image section -->
								<!-- Name section -->
								<div class="name-col-width w3-container  padding" style="height: inherit; ">
									<div class="w3-row " style="height: inherit; position: relative;">
										<!-- Name Section -->
										<div class="w3-col w3-container w3-center  center-blck-name"  style="width:100%">
											<span class="stky-prdct-name">
												<?= $product_details->get_name(); ?>
											</span>
										</div>
										<!-- end of name section -->
										
									</div>
								</div>
								<!-- End of name and star -->
							</div>
						</div>
						<!-- End of first section  -->
						
						<!-- Second section Or price section  -->
						<div class="col-width w3-container right-border  w3-center "  >
							<div class="w3-row  height" >
								<div class="w3-col w3-container w3-center center-blck padding">
					  			<?php $currency_symb = get_woocommerce_currency_symbol(get_woocommerce_currency()) //get currency then convert to symbol ; ?>
	    						<?php if( !empty($product_details->get_sale_price())) : // check for sale price ?>
						    	<strike class="stky-strike">
							    	<span class="stky-reglr-price">
							    		<?= $currency_symb.number_format($product_details->get_regular_price(),2)?>
							    	</span>

					    		</strike>
					    		&nbsp;
					    		<span class="w3-badge">
						    		<?= $currency_symb.number_format($product_details->get_sale_price(),2)?>

						    	</span>
						    	<?php else: ?>
						    	<span class="w3-badge">
						    		<?= $currency_symb.number_format($product_details->get_regular_price(),2); ?>
						    	</span>		
						    	<?php endif ; ?>
						    </div>
							</div>
						</div>
						<!-- End of second section or price section  -->
						<!-- Third section or star ratting section  -->
						<div class="col-width w3-container right-border  w3-center star-contr-div " >
							<div class="w3-row  height" >
								<!-- Star counting section -->								
								<div class="w3-col w3-container  w3-center center-blck-star "  style="width:100%">
									<span class="rateyo star-margin" data-star =" <?= $product_details->get_average_rating() ; ?>">
								  </span> 
								</div>
							<!-- End of star counting section  -->
				    	</div>
						</div>
						<!-- End of third section or star rating section  -->
						<!-- Fourth section or add to cart section  -->
						<div class="col-width w3-container w3-center padding"  >
							<div class="w3-row  height" >
								<div class="w3-col w3-container w3-center center-blck stky-cart-section">
									<?php if( $product_details->is_in_stock() ) : 
										$shop_page_url = get_site_url();
							    	$_product = wc_get_product( $post->ID );
										if( $_product->is_type( 'simple' ) ) 
											$product_class = 'simple-product'; 
										else
											$product_class = 'variable-product'; 
									?>
								    <a href="<?= $shop_page_url; ?>/shop/?add-to-cart=<?= $post->ID?>" class="w3-button <?php echo $product_class; ?>  w3-round-xxlarge mg-wsac-btn cart-text">

								    <?php if( $product_class == 'variable-product' ) : ?>
								    	<?= _e($options['variable_text'] , 'wsac'); ?></a>
								    <?php else : ?>
								    	<?= _e($options['text'] , 'wsac'); ?></a>
								    <?php endif; ?>
		
									  <?php else : ?>
									  <p class="mg-wsac-out-of-stock ">
									  	<?= _e('Out Of Stock' , 'wsac' ) ; ?>
									  </p>
									<?php endif ; ?>
								</div>
							</div>
						</div>
						<!-- End of fourth section or add to cart button  -->
					</div>
					<!-- End of main row -->
				</div>
				<!-- end of main container  -->
			<?php
		endif;
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
			'always_visible'	=> $options['on_load_page'],
			'ajaxurl' 				=> admin_url( 'admin-ajax.php' ),
			'mobile_enable' 	=> $options['enable_mobile'],
			'enable_desktop'	=> $options['enable_desktop'],
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