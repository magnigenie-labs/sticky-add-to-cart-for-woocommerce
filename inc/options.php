<?php
/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 * By default it uses the theme name, in lowercase and without spaces, but this can be changed if needed.
 * If the identifier changes, it'll appear as if the options have been reset.
 */

function wsac_optionsframework_option_name() {
	$wsac_optionsframework_settings = get_option( 'wsac_optionsframework' );
	$wsac_optionsframework_settings['id'] = 'wsac_options';
	update_option( 'wsac_optionsframework', $wsac_optionsframework_settings );
}


add_filter( 'wsac_optionsframework_menu', 'wsac_add_settings_menu' );

function wsac_add_settings_menu( $menu ) {
	$menu['page_title']  = 'WooCommerce Sticky Add To Cart';
	$menu['menu_title']  = 'Sticky Add To Cart';
	$menu['mode']		 = 'menu';
	$menu['menu_slug']   = 'woocommerce-sticky-add-to-cart';
	$menu['position']    = '100';
	return $menu;
}



/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the 'id' fields, make sure to use all lowercase and no spaces.
 *
 * If you are making your theme translatable, you should replace 'options_framework_theme'
 * with the actual text domain for your theme.  Read more:
 * http://codex.wordpress.org/Function_Reference/load_theme_textdomain
 */

$options = get_option( 'wsac_options' );

function wsac_optionsframework_options() {

	$categories = get_terms( 'product_cat', 'orderby=name&hide_empty=0' );
	$cats = array();

	if ( $categories ) foreach ( $categories as $cat ) $cats[$cat->term_id] = esc_html( $cat->name );
	
	$options = array();

  $options[] = array( 'name' => __( 'General', 'wsac' ),
        'type' => 'heading' );  
		
	$options[] = array( 'name' => __( 'Enable', 'wsac' ),
		'desc' 		=> __( 'Turn on if you want to enable Sticky Add To Cart.', 'wsac' ),
		'id' 			=> 'enable',
		'std' 		=> '1',
		'type' 		=> 'checkbox' );

	$options[] = array( 'name' => __( 'Enable On Desktop', 'wsac' ),
		'desc' 		=> __( 'Enable sticky add to cart option on desktop', 'wsac' ),
		'id' 			=> 'enable_desktop',
		'std' 		=> '1',
		'type' 		=> 'checkbox' );

	$options[] = array( 'name' => __( 'Enable On Mobile', 'wsac' ),
		'desc' 		=> __( 'Enable sticky add to cart option on mobile', 'wsac' ),
		'id' 			=> 'enable_mobile',
		'std' 		=> '1',
		'type' 		=> 'checkbox' );

	$options[] = array( 'name' => __( 'Always Visible', 'wsac' ),
		'desc' 		=> __( 'Make sticky bar always visible on single product pages', 'wsac' ),
		'id' 			=> 'on_load_page',
		'std' 		=> '1',
		'type' 		=> 'checkbox' );

	$options[] = array( 'name' => __( 'Sticky Add To Cart Style', 'wsac' ),
		'desc' 		=> __( 'Select the style that you want to use in the sticky add to cart', 'wsac' ),
		'id' 			=> 'sticky_bar_style',
		'std' 		=> 'classic',
		'class'	=> 'pro-feature',
		'options'	=> array('classic' => 'Classic', 'modern' => 'Modern'),
		'type' 		=> 'select' );

	$options[] = array( 'name' => __( 'Sticky Bar Position', 'wsac' ),
		'desc' 		=> __( 'Select a position to display sticky bar , Default will be bottom', 'wsac' ),
		'id' 			=> 'fix_postion',
		'options' 	=> array( 'top' => 'Top','bottom' => 'Bottom' ),
		'std' 		=> 'bottom',
		'type' 		=> 'select' );

	$options[] = array( 'name' => __( 'Sticky Bar Height', 'wsac' ),
		'desc' 		=> __( 'Set height for sticky bar in px. Blank will assume as auto', 'wsac' ),
		'id' 			=> 'height',
		'std' 		=> 'auto',
		'type' 		=> 'text' );


	$options[] = array('name' => __('Hide Sticky Cart For Products', 'wsac'),
		'id' 			=> 'wsac_exclude_products',
		'class' 	=> 'pro-feature',
		'desc' 		=> __( 'Select for which products the sticky add to cart won\'t be shown. Keeping blank will show sticky add to cart over all the products', 'wsac' ),
		'type' 		=> 'text');

	$options[] = array( 'name' => __( 'Enable ajax For Add To Cart', 'wsac' ),
		'desc' 		=> __( 'Enable ajax based add to cart option', 'wsac' ),
		'id' 			=> 'ajax_based',
		'class'		=> 'pro-feature',
		'std' 		=> '1',
		'type' 		=> 'checkbox' );

	$options[] = array( 'name' => __( 'Redirect After Added Into Cart', 'wsac' ),
		'desc' 		=> __( 'Select on which page you want to redirect after once the product has been added to cart', 'wsac' ),
		'id' 			=> 'redirect_page',
		'options'		=> array('shop' => 'Shop Page', 'cart' => 'Cart Page', '' => 'Same Page', 'checkout' => 'Checkout Page'),
		'std' 		=> 'shop',
		'type' 		=> 'select' );

	$options[] = array( 'name' => __( 'Loading text for add to cart', 'wsac' ),
		'desc' 		=> __( 'This will be loading text for add to cart button', 'wsac' ),
		'id' 			=> 'loading_text',
		'std' 		=> 'Loading',
		'class'		=> 'pro-feature',
		'type' 		=> 'text' );

	$options[] = array( 'name' => __( 'Appearance Settings', 'wsac' ),
		'type' 		=> 'heading' );

	$options[] = array( 'name' => __( 'Show Product Image', 'wsac' ),
		'desc' 		=> __( 'Show product image in sticky add to cart ', 'wsac' ),
		'id' 			=> 'show_product_image',
		'std' 		=> '1',
		'class'		=> 'pro-feature',
		'type' 		=> 'checkbox' );

	$options[] = array( 'name' => __( 'Show Star Ratings', 'wsac' ),
		'desc' 		=> __( 'Show product star ratings in sticky add to cart ', 'wsac' ),
		'id' 			=> 'show_product_star_ratings',
		'std' 		=> '1',
		'class'		=> 'pro-feature',
		'type' 		=> 'checkbox' );

	$options[] = array( 'name' => __( 'Show Quantity Box', 'wsac' ),
		'desc' 		=> __( 'Show quantity box in sticky add to cart ', 'wsac' ),
		'id' 			=> 'show_quantity_box',
		'std' 		=> '1',
		'class'		=> 'pro-feature',
		'type' 		=> 'checkbox' );

	$options[] = array( 'name' => __( 'Sticky Bar Background', 'wsac' ),
		'desc' 		=> __( 'Select background color for sticky bar.', 'wsac' ),
		'id' 				=> 'background',
		'std' 			=> '#222930',
		'type' 			=> 'color' );

	$options[] = array( 'name' => __( 'Sticky Bar Background Image', 'wsac' ),
		'desc' 		=> __( 'Set sticky bar background image. If sticky bar background image is set then sticky bar background color would not work.', 'wsac' ),
		'id' 				=> 'bar_bg_img',
		'std' 			=> '',
		'class'		=> 'pro-feature',
		'type' 			=> 'upload' );

	$options[] = array( 'name' => __( 'Background Image Size', 'wsac' ),
		'desc' 		=> __( 'Set sticky bar background image size', 'wsac' ),
		'id' 				=> 'bar_img_size',
		'std' 			=> 'contain',
		'options'		=> array('contain' => 'Contain', 'cover' => 'Cover', 'custom' => 'Custom'),
		'class'		=> 'pro-feature',
		'type' 			=> 'select' );

	$options[] = array( 'name' => __( 'Background Image Custom Size', 'wsac' ),
		'desc' 		=> __( 'Set sticky bar background custom image size, example: 50%', 'wsac' ),
		'id' 				=> 'bar_custom_img_size',
		'std' 			=> '50%',
		'class'		=> 'pro-feature',
		'type' 			=> 'text' );

	$options[] = array( 'name' => __( 'Background Image Position', 'wsac' ),
		'desc' 		=> __( 'Set sticky bar background image position', 'wsac' ),
		'id' 				=> 'bar_img_position',
		'std' 			=> 'center',
		'class'		=> 'pro-feature',
		'options'		=> array('bottom' => 'Bottom', 'center' => 'Center', 'left' => 'Left', 'right' => 'Right', 'top' => 'Top' ),
		'type' 			=> 'select' );

	$options[] = array( 'name' => __( 'Background Image Repeat', 'wsac' ),
		'desc' 		=> __( 'Set sticky bar background image repeat', 'wsac' ),
		'id' 				=> 'bar_img_repeat',
		'std' 			=> 'repeat',
		'class'		=> 'pro-feature',
		'options'		=> array('repeat' => 'Repeat', 'repeat-x' => 'Repeat-X', 'repeat-y' => 'Repeat-Y'),
		'type' 			=> 'select' );

	$options[] = array( 'name' => __( 'Star Background Color', 'wsac' ),
		'desc' 		=> __( 'Select background color for star.', 'wsac' ),
		'id' 				=> 'star-background',
		'std' 			=> '#FFFFFF',
		'type' 			=> 'color' );

	$options[] = array( 'name' => __( 'Star Color', 'wsac' ),
		'desc' 		=> __( 'Select star color.', 'wsac' ),
		'id' 				=> 'star-color',
		'std' 			=> '#cd534a',
		'type' 			=> 'color' );

	$options[] = array( 'name' => __( 'Sticky Bar\'s Border Color', 'wsac' ),
		'desc' 		=> __( 'Select border color for sticky bar.', 'wsac' ),
		'id' 				=> 'border',
		'std' 			=> '#cd534a',
		'type' 			=> 'color' );

	$options[] = array( 'name' => __( 'Simple Product Add To Cart Text', 'wsac' ),
		'desc' 		=> __( 'Set text for add to cart button', 'wsac' ),
		'id' 				=> 'text',
		'std' 			=> 'Add to cart',
		'type' 			=> 'text' );

	$options[] = array( 'name' => __( 'Variable Product Add To Cart Text', 'wsac' ),
		'desc' 		=> __( 'Set text for variable product add to cart text', 'wsac' ),
		'id' 				=> 'variable_text',
		'std' 			=> 'Show Options',
		'type' 			=> 'text' );

	$options[] = array( 'name' => __( 'Cart Button Background', 'wsac' ),
		'desc' 		=> __( 'Set background color for cart button', 'wsac' ),
		'id' 				=> 'cart_background',
		'std' 			=> '#cd534a',
		'type' 			=> 'color' );

	$options[] = array( 'name' => __( 'Cart Button Hover Background', 'wsac' ),
		'desc' 		=> __( 'Set background hover color for cart button', 'wsac' ),
		'id' 				=> 'cart_background_hover',
		'std' 			=> '#cd534a',
		'type' 			=> 'color' );

	$options[] = array( 'name' => __( 'Button Text & Icon Color', 'wsac' ),
		'desc' 		=> __( 'Text color for cart button.', 'wsac' ),
		'id' 				=> 'text_color',
		'std' 			=> '#FFFFFF',
		'type' 			=> 'color' );
	$options[] = array( 'name' => __( 'Stockout Text & Icon Color', 'wsac' ),
		'desc' 		=> __( 'Text color for out of stock.', 'wsac' ),
		'id' 				=> 'text_color_stock_out',
		'std' 			=> '#FFFFFF',
		'type' 			=> 'color' );


	$options[] = array( 'name' => __( 'Price Badge Text Color', 'wsac' ),
		'desc' 		=> __( 'Text color for badge of price.', 'wsac' ),
		'id' 				=> 'text_color_badge',
		'std' 			=> '#FFFFFF',
		'type' 			=> 'color' );

	$options[] = array( 'name' => __( 'Price Badge Background Color', 'wsac' ),
		'desc' 		=> __( 'Set background color for badge of price.', 'wsac' ),
		'id' 				=> 'badge_background',
		'std' 			=> '#cd534a',
		'type' 			=> 'color' );

	$options[] = array( 'name' => __( 'Regular Price Strike Color', 'wsac' ),
		'desc' 		=> __( 'Set strike color  of  regular price', 'wsac' ),
		'id' 				=> 'strike_color',
		'std' 			=> '#cd534a',
		'type' 			=> 'color' );

	$options[] = array( 'name' => __( 'Text Color For Product and Price', 'wsac' ),
		'desc' 		=> __( 'Set text color for product name and price', 'wsac' ),
		'id' 				=> 'sticky_text',
		'std' 			=> '#ffffff',
		'type' 			=> 'color' );

	$options[] = array( 'name' => __( 'Select Product Image Shape', 'wsac' ),
		'desc' 		=> __( 'Select product image shape which will be shown in the stick bar', 'wsac' ),
		'id' 				=> 'product_image_shape',
		'std' 			=> 'round',
		'options'		=> array('round' => 'Round' , 'square' => 'Square'),
		'type' 			=> 'select' );

	$options[] = array( 'name' => __( 'Select Badge Shape Of Price', 'wsac' ),
		'desc' 		=> __( 'Select a shape of badge shape either rounded or square , Default will rounded', 'wsac' ),
		'id' 				=> 'badge_shape',
		'std' 			=> 'round',
		'options'		=> array('round' => 'Round' , 'square' => 'Square'),
		'type' 			=> 'select' );

    return $options;
}