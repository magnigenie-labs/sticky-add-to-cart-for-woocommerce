<?php 
/**
 * WooCommerce Sticky Add To Cart Frontend 
 *
 * @author   Magnigenie
 * @category  Frontend
 * @version     1.0.0
 */

// No direct file access
! defined( 'ABSPATH' ) AND exit;

class Wsac_frontend {
	/**
	* Class constructor. 
	* Bootstraps the class and hooks required actions & filters.
	*/
	function __construct() {
		// Add action to add html view to forntend 
		add_action( 'add_sticky_bar', array( $this, 'add_stick_bar_to_single_page' ) );
	}

	/**
	* Html sticky bar
	* @param array $options Admin Settings options.
	*/
	public function add_stick_bar_to_single_page($options) {
		//Check page is single product And admin setting's meta key has been saved
		if ( is_product() AND ( $options )  ) :
			if ($options['enable'] !== 'no') : // Check enable option is yes
				global $post;

				//get woocommerce object according to specific product
				$product_details = new WC_Product_Variable($post->ID);?>
	
				<!-- Main Container of stick bar -->
				<div class="mg-wsac-fix-sticky-bar  w3-container" style=" position: fixed ; z-index: 99999 ; width: 100% ;">
					<!-- Main Row of sticky bar -->
					<div class="w3-row " >
						<!-- First section -->
						<div class="col-width  w3-container  right-border fst-cont-div"  >
							<div class="w3-row  height"  >
								<!-- Image Section -->
								<div class="img-col-width w3-container  w3-center  padding" style="height: inherit;position: relative">
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
									<?php if( $product_details->is_in_stock() ) : ?>
								  	<button class="w3-button  w3-round-xxlarge mg-wsac-btn" style="">
								    	<span class="mg-wsac-shopping mg-wsac-shopping-bag"></span>
								    	<input type="hidden" data-productId = "<?= $post->ID?>">
									    <span class="cart-text"><?= _e($options['text'] , 'wsac'); ?></span>
								    </button>
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
			<?php endif;
		endif ;
	}
}

new Wsac_frontend();