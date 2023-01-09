<?php

class WC_Sticky_Add_To_Cart {

  /**
  * Bootstraps the class and hooks required actions & filters.
  *
  */
  protected $options = '';

  public function __construct() {

    $this->options = get_option( 'wsac_options' );

    add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

    //Add setting link for the admin settings
    add_filter( "plugin_action_links_" . WSAC_BASE, array( $this, 'wsac_settings_link' ) );

    if( $this->option('enable') == 'yes' ) {
      add_action( 'wp_head', array( $this, 'init' ) );

      //Add all js script and css to sticky bar
      add_action( 'wp_enqueue_scripts',  array( $this, 'wsac_enque_scripts' ) );
    }
  }

  /**
  * Add init function to get html sticky bar
  * @param void
  * @return html for stick bar
  */
  public function init() {

    global $woocommerce;
    global $post;
    global $product;

    //Check page is single product And admin setting's meta key has been saved
    if ( $this->option( 'enable' ) == 'yes' && is_product() )
      echo $this->get_sticky_add_to_cart_bar( $post->ID );
  }

/**
  * Add admin scripts for the plugin
  *
  * @param void
  * @return void
  *
  */
  public function admin_scripts() {
    wp_enqueue_style( 'wsac-admin-style', plugins_url( 'inc/assets/css/admin-style.css', WSAC_FILE ) );
  }

  /**
  * Add new link for the settings under plugin links
  *
  * @param array $links an array of existing links.
  * @return array of links along with sticky add to cart settings link.
  *
  */
  public function wsac_settings_link($links) {
    $new_links = array();
    $pro_link = 'https://www.magnigenie.com/downloads/woocommerce-sticky-add-to-cart-pro/';
    $settings_link = esc_url( add_query_arg( array(
                            'page' => 'woocommerce-sticky-add-to-cart',
                            ), admin_url( 'admin.php' ) ) );
    $new_links[ 'settings' ] = sprintf( '<a href="%1$s" title="%2$s">%2$s</a>', $settings_link, esc_attr__( 'Settings', 'wsac' ) );
    $new_links[ 'go-pro' ] = sprintf( '<a target="_blank" style="color: #45b450; font-weight: bold;" href="%1$s" title="%2$s">%2$s</a>', $pro_link, esc_attr__( 'Get Pro Version', 'wsac' ) );

    return array_merge( $links, $new_links );
  }

  /**
   * Get options for plugin
   *
   * @param settings options.
   * @return array of options.
   *
   */
  public function option( $option ) {
    if( isset( $this->options[$option] ) && $this->options[$option] != '' )
      return $this->options[$option];
    return '';
  }

  /**
  *
  * Add necessary js and css files for sticky bar
  * @param void
  * @return void
  */
  public function wsac_enque_scripts() {
    // Set height  for sticky bar
    $height = ( empty( $this->option('height') )? '60px' : $this->option('height').'px' );
    // Set badge shape
    $badge_shape = ( $this->option('badge_shape') === 'round' ? '32px' : '0px' ) ;
    // Set button shape
    $cart_btn_shape = ( $this->option('cart_shape') === 'round' ? '32px' : '0px' );
    // Set for inline css
    $css = ".mg-wsac-fix-sticky-bar{ background : ".$this->option('background')."  ; ".$this->option('fix_postion')." : 0; }";

    if( $this->option('fix_postion') == 'top'
      && is_admin_bar_showing() ) {
      $css .= 'html body div.mg-wsac-fix-sticky-bar {top: 32px;}';
    }

    $css .= ".mg-height {min-height: $height;position: relative;}";
    $css .= ".mg-wsac-fix-sticky-bar .mg-wsac-btn:hover {color: ".$this->option('text_color').";}";
    $css .= ".stky-reglr-price:before {border-color: ".$this->option('strike_color')."!important;}";
    $css .= "#cart-icon {fill: ".$this->option('text_color').";}";
    $css .= "p.mg-wsac-out-of-stock {color: ".$this->option('text_color_stock_out').";}";
    $css .= "svg#stock-out-icon {fill: ".$this->option('text_color_stock_out').";}";
    $css .= ".stky-reglr-price:before{border-color:".$this->option('border')."}";
    $css .= "@media screen  and (max-width: 600px) { .right-border {border-right: none;}}";
    $css .= ".right-border{border-right: 1px solid ".$this->option('border')."}";
    $css .= ".mg-wsac-btn{ background : ".$this->option('cart_background')." ; color : ".$this->option('text_color')." }";
    $css .= ".mg-wsac-btn:hover{ background : ".$this->option('cart_background_hover')." }";
    $css .= ".mg-wsac-price-container .mg-wsac-badge{border-radius : $badge_shape ; color : ".$this->option('text_color_badge')." ; background : ".$this->option('badge_background')." }";
    $css .= ".stky-strike{color : ".$this->option('strike_color')." }";
    $css .= ".stky-reglr-price , .stky-prdct-name {color : ".$this->option('sticky_text')." }";
    $css .= ".mg-wsac-round-xxlarge{ border-radius : $cart_btn_shape }";

    if( $this->option('product_image_shape') == 'round' )
      $css .= ".mg-wsac-fix-sticky-bar img.mg-wsac-circle { border-radius : 50% }";

    //Load rateyo js text_color
    wp_enqueue_script( 'rateyo-js', plugins_url( 'inc/assets/js/jquery.rateyo.js', WSAC_FILE ) , array( 'jquery' ), '1.0.0', true);

    //Load custom js
    wp_enqueue_script( 'wsac-customjs', plugins_url( 'inc/assets/js/wsac-custom.js', WSAC_FILE ) , array( 'jquery' ), '1.0.0', true);

    //Load font awesome css
    wp_enqueue_style( 'font-awesome-css', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css' , array(), '3.3.7', false);

    //Load rateyo css
    wp_enqueue_style( 'jquery-reteyo-css', plugins_url( 'inc/assets/css/jquery.rateyo.css', WSAC_FILE ) , array(), '1.0.0', false);

    //Load custom css
    wp_enqueue_style( 'wsac-custom-css', plugins_url( 'inc/assets/css/wsac-custom.css', WSAC_FILE ) , array(), '1.0.0', false);

    //Add inline css to custom css
    wp_add_inline_style( 'wsac-custom-css', $css );

    //Localize for custom js
    wp_localize_script( 'wsac-customjs' , 'wsac' , array(
      'always_visible'  => $this->option('on_load_page'),
      'mobile_enable'   => $this->option('enable_mobile'),
      'enable_desktop'  => $this->option('enable_desktop'),
      'star_background' => $this->option('star-background'),
      'star_color'      => $this->option('star-color'),
      'show_quantity_box' => $this->option('show_quantity_box'),
      'enable_ajax'       => $this->option('ajax_based'),
      'site_url'        => get_site_url(),
      'redirect_page'   => $this->option('redirect_page'),
      'ajax_url'        => admin_url( 'admin-ajax.php' ),
      'loading_text'    => $this->option('loading_text'),
      'add_to_cart_text' => $this->option('text'),
      'ajax_enabled'     => $this->option('ajax_based'),
      'variable_product_add_to_cart' => $this->option('variable_text'),
    ));
  }

  /**
   * Add classic sticky add to cart
   *
   * @param array $links an array of existing links.
   * @return array of links  along with age restricted shopping settings link.
   *
   */
  public function get_sticky_add_to_cart_bar( $product_id ) {
    $product = wc_get_product( $product_id );
    $product_type = $product->get_type();
    $class = $this->option( 'fix_postion' );
    ?>
    <!-- Main Container of stick bar -->
    <div class="mg-wsac-fix-sticky-bar classic-sticky-cart <?php echo $class; ?>  mg-wsac-container">
      <!-- Main Row of sticky bar -->
      <div class="mg-wsac-row mg-wsac-wrap product-sticky-sec" >
        <!-- First section -->
        <div class="col-width mg-wsac-image-container mg-wsac-container right-border fst-cont-div">
          <div class="mg-wsac-row mg-height">

            <!-- Image Section -->
            <div class="img-col-width mg-wsac-container  mg-wsac-center  padding">
              <div class="img center-blck-img" style=" ">
                <?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $product_id ), 'single-post-thumbnail' );?>
                <img src="<?php echo $image[0]?>" class="mg-wsac-circle" alt="<?php echo $product->get_name(); ?>"  >
              </div>
            </div>
            <!-- End of image section -->

            <!-- Name section -->
            <div class="name-col-width mg-wsac-container padding" style="height: inherit; ">
              <div class="mg-wsac-row " style="">
                <!-- Name Section -->
                <div class="mg-wsac-col mg-wsac-container mg-wsac-center  center-blck-name"  style="width:100%">
                  <span class="stky-prdct-name">
                    <?php echo $product->get_name(); ?>
                  </span>
                </div>
                <!-- end of name section -->
                  <!-- Star counting section -->
                <!-- <div class="mg-wsac-col mg-wsac-container  mg-wsac-center center-blck-star "  style="width:100%">
                  <span class="rateyo star-margin" data-star =" <?php echo $product->get_average_rating() ; ?>">
                  </span>
                </div> -->
              <!-- End of star counting section  -->
              </div>
            </div>
            <!-- End of name and star -->
            <div class="wsac-clearfix"></div>

          </div>
        </div>
        <!-- End of first section  -->

        <!-- Second section Or price section  -->
        <div class="col-width mg-wsac-price-container mg-wsac-container right-border  mg-wsac-center "  >
          <div class="mg-wsac-row  mg-height" >
            <div class="mg-wsac-col mg-wsac-container mg-wsac-center center-blck padding">
              <?php
              $currency_symb = get_woocommerce_currency_symbol( get_woocommerce_currency() ); //get currency then convert to symbol ;
              if( $product_type == 'simple' ) :

              // If Product is simple
              if( !empty( $product->get_sale_price() ) ) : // check for sale price ?>
                <strike class="stky-strike">
                  <span class="stky-reglr-price">
                    <?php echo $currency_symb.number_format( $product->get_regular_price() ,2 )?>
                  </span>
                </strike>
                &nbsp;
                <span class="mg-wsac-badge">
                  <?php echo $currency_symb.number_format( $product->get_sale_price() , 2 )?>
                </span>
                <?php else: ?>
                <span class="mg-wsac-badge">
                  <?php
                  if( $product->get_regular_price() !== '' ) :
                    echo $currency_symb.number_format( $product->get_regular_price(), 2 );
                  endif;
                  ?>
                </span>
                <?php
                  endif;
                else:

                $min_price = $product->get_variation_price( 'min' );
                $max_price = $product->get_variation_price( 'max' );
                  ?>
                <span class="mg-wsac-badge">
                <?php
                  $product_price = $currency_symb . number_format( $min_price, 2 );

                  if( $max_price !== '' )
                    $product_price .= ' - '.$currency_symb . number_format( $max_price, 2 );

                  echo $product_price;
                ?>
                </span>
                <?php
                  endif;
                ?>
                </div>
              </div>
            </div>
            <!-- End of second section or price section  -->

            <!-- Third section or price section  -->

<div class="col-width mg-wsac-price-container mg-wsac-container right-border  mg-wsac-center "  >
          <div class="mg-wsac-row  mg-height" >
            <div class="mg-wsac-col mg-wsac-container mg-wsac-center center-blck padding">
              
<div class="mg-wsac-col mg-wsac-container  mg-wsac-center center-blck-star sticky-star-container "  style="width:100%">
                  <span class="rateyo star-margin sticky-star-rating" data-star =" <?php echo $product->get_average_rating() ; ?>">
                  </span>
                </div>
              <!-- End of star counting section  -->
           



                </div>
              </div>
            </div>

            <!-- End of Third section or price section  -->




            <!-- Fourth section or add to cart section  -->
            <div class="col-width mg-wsac-quantity-container mg-wsac-container mg-wsac-center padding"  >
              <div class="mg-wsac-row  mg-height" >
                <div class="mg-wsac-col wsac-button-wrapper  mg-wsac-container mg-wsac-center center-blck stky-cart-section">
                  <?php
                    if( $product->is_in_stock() ) :

                      if( $product_type == 'simple' ) :
                        $shop_page_url = get_permalink( $product_id );
                        $redirect_page = $this->option('redirect_page');

                        if( $redirect_page !== '' ) {
                          $shop_page_url = get_permalink( wc_get_page_id( $redirect_page ) );
                        }

                        $redirect_link = $shop_page_url .'?add-to-cart='.$product_id.'&quantity=1';

                        if( $product->get_regular_price() == ''
                          && $product->get_sale_price() == ''
                          && $product->get_sale_price() == '' ) {
                          $cart_text = __( $this->option('no_price_product'), 'wsac' );
                          $redirect_link = get_permalink( $product_id );
                        }
                        else {
                          $cart_text = __( $this->option('text') , 'wsac');
                        }

                      ?>

                      <a href="<?php echo $redirect_link; ?>" data-product-id="<?php echo $product_id; ?>" class="mg-wsac-button simple-product mg-wsac-round-xxlarge mg-wsac-btn cart-text">
                      <span class="icon-wrap">
                    <svg version="1.1" id="cart-icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                       viewBox="0 0 486.569 486.569">
                    <g>
                      <path d="M146.069,320.369h268.1c30.4,0,55.2-24.8,55.2-55.2v-112.8c0-0.1,0-0.3,0-0.4c0-0.3,0-0.5,0-0.8c0-0.2,0-0.4-0.1-0.6
                        c0-0.2-0.1-0.5-0.1-0.7s-0.1-0.4-0.1-0.6c-0.1-0.2-0.1-0.4-0.2-0.7c-0.1-0.2-0.1-0.4-0.2-0.6c-0.1-0.2-0.1-0.4-0.2-0.6
                        c-0.1-0.2-0.2-0.4-0.3-0.7c-0.1-0.2-0.2-0.4-0.3-0.5c-0.1-0.2-0.2-0.4-0.3-0.6c-0.1-0.2-0.2-0.3-0.3-0.5c-0.1-0.2-0.3-0.4-0.4-0.6
                        c-0.1-0.2-0.2-0.3-0.4-0.5c-0.1-0.2-0.3-0.3-0.4-0.5s-0.3-0.3-0.4-0.5s-0.3-0.3-0.4-0.4c-0.2-0.2-0.3-0.3-0.5-0.5
                        c-0.2-0.1-0.3-0.3-0.5-0.4c-0.2-0.1-0.4-0.3-0.6-0.4c-0.2-0.1-0.3-0.2-0.5-0.3s-0.4-0.2-0.6-0.4c-0.2-0.1-0.4-0.2-0.6-0.3
                        s-0.4-0.2-0.6-0.3s-0.4-0.2-0.6-0.3s-0.4-0.1-0.6-0.2c-0.2-0.1-0.5-0.2-0.7-0.2s-0.4-0.1-0.5-0.1c-0.3-0.1-0.5-0.1-0.8-0.1
                        c-0.1,0-0.2-0.1-0.4-0.1l-339.8-46.9v-47.4c0-0.5,0-1-0.1-1.4c0-0.1,0-0.2-0.1-0.4c0-0.3-0.1-0.6-0.1-0.9c-0.1-0.3-0.1-0.5-0.2-0.8
                        c0-0.2-0.1-0.3-0.1-0.5c-0.1-0.3-0.2-0.6-0.3-0.9c0-0.1-0.1-0.3-0.1-0.4c-0.1-0.3-0.2-0.5-0.4-0.8c-0.1-0.1-0.1-0.3-0.2-0.4
                        c-0.1-0.2-0.2-0.4-0.4-0.6c-0.1-0.2-0.2-0.3-0.3-0.5s-0.2-0.3-0.3-0.5s-0.3-0.4-0.4-0.6c-0.1-0.1-0.2-0.2-0.3-0.3
                        c-0.2-0.2-0.4-0.4-0.6-0.6c-0.1-0.1-0.2-0.2-0.3-0.3c-0.2-0.2-0.4-0.4-0.7-0.6c-0.1-0.1-0.3-0.2-0.4-0.3c-0.2-0.2-0.4-0.3-0.6-0.5
                        c-0.3-0.2-0.6-0.4-0.8-0.5c-0.1-0.1-0.2-0.1-0.3-0.2c-0.4-0.2-0.9-0.4-1.3-0.6l-73.7-31c-6.9-2.9-14.8,0.3-17.7,7.2
                        s0.3,14.8,7.2,17.7l65.4,27.6v61.2v9.7v74.4v66.5v84c0,28,21,51.2,48.1,54.7c-4.9,8.2-7.8,17.8-7.8,28c0,30.1,24.5,54.5,54.5,54.5
                        s54.5-24.5,54.5-54.5c0-10-2.7-19.5-7.5-27.5h121.4c-4.8,8.1-7.5,17.5-7.5,27.5c0,30.1,24.5,54.5,54.5,54.5s54.5-24.5,54.5-54.5
                        s-24.5-54.5-54.5-54.5h-255c-15.6,0-28.2-12.7-28.2-28.2v-36.6C126.069,317.569,135.769,320.369,146.069,320.369z M213.269,431.969
                        c0,15.2-12.4,27.5-27.5,27.5s-27.5-12.4-27.5-27.5s12.4-27.5,27.5-27.5S213.269,416.769,213.269,431.969z M428.669,431.969
                        c0,15.2-12.4,27.5-27.5,27.5s-27.5-12.4-27.5-27.5s12.4-27.5,27.5-27.5S428.669,416.769,428.669,431.969z M414.169,293.369h-268.1
                        c-15.6,0-28.2-12.7-28.2-28.2v-66.5v-74.4v-5l324.5,44.7v101.1C442.369,280.769,429.669,293.369,414.169,293.369z"/>
                    </g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg></span>
                        <?php echo $cart_text; ?>
                      </a>
                    <?php else : ?>
                      <a href="<?php echo $shop_page_url; ?>/shop/?add-to-cart=<?php echo $product_id; ?>" class="mg-wsac-button variable-product mg-wsac-round-xxlarge mg-wsac-btn cart-text">
                    <?php _e( $this->option( 'variable_text' ) , 'wsac'); ?>
                      </a>
                    <?php
                    endif;
                  ?>
                  <?php else : ?>
                    <p class="mg-wsac-out-of-stock ">
                      <span class="stock-out-icon-wrap">
                        <svg id="stock-out-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 21.275 19.945">
                          <g data-name="stock-out" transform="translate(-1047 -341)">
                            <path id="clear" d="M3.083,3.678,1.9,2.494.717,3.678a.42.42,0,0,1-.594-.594L1.306,1.9.123.718A.42.42,0,0,1,.717.124L1.9,1.307,3.083.124a.42.42,0,0,1,.594.594L2.494,1.9,3.677,3.084a.42.42,0,0,1-.594.594Zm0,0" transform="translate(1060.752 353.433)"/>
                            <path id="Path_3" data-name="Path 3" d="M63.116,286H60.623a.623.623,0,0,0,0,1.247h2.493a.623.623,0,1,0,0-1.247Z" transform="translate(989.493 66.219)"/>
                            <path id="Path_4" data-name="Path 4" d="M63.116,226H60.623a.623.623,0,0,0,0,1.247h2.493a.623.623,0,1,0,0-1.247Z" transform="translate(989.493 123.726)"/>
                            <path id="Path_5" data-name="Path 5" d="M15.665,24.726a5.638,5.638,0,0,0-.706.044V16.623A.623.623,0,0,0,14.336,16H.623A.623.623,0,0,0,0,16.623V30.336a.623.623,0,0,0,.623.623h9.467a5.61,5.61,0,1,0,5.575-6.233Zm-9.433-7.48H8.726V19.74H6.233Zm-4.986,0h3.74v3.116a.623.623,0,0,0,.623.623h3.74a.623.623,0,0,0,.623-.623V17.247h3.74v7.83a5.624,5.624,0,0,0-3.622,4.635H1.247ZM15.665,34.7a4.363,4.363,0,1,1,4.363-4.363A4.368,4.368,0,0,1,15.665,34.7Z" transform="translate(1047 325)"/>
                          </g>
                        </svg>
                      </span>
                      <?php _e( 'Out Of Stock' , 'wsac' ) ; ?>
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
  }

}
