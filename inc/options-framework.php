
<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


// Don't load if wsac_optionsframework_init is already defined
if (is_admin() && ! function_exists( 'wsac_optionsframework_init' ) ) :

function wsac_optionsframework_init() {

	//  If user can't edit theme options, exit
	if ( ! current_user_can( 'edit_theme_options' ) )
		return;

	// Loads the required Options Framework classes.
	require plugin_dir_path( __FILE__ ) . 'includes/class-options-framework.php';
	require plugin_dir_path( __FILE__ ) . 'includes/class-options-framework-admin.php';
	require plugin_dir_path( __FILE__ ) . 'includes/class-options-interface.php';
	require plugin_dir_path( __FILE__ ) . 'includes/class-options-media-uploader.php';
	require plugin_dir_path( __FILE__ ) . 'includes/class-options-sanitization.php';

	// Instantiate the main plugin class.
	$options_framework = new Wsac_Options_Framework;
	$options_framework->init();

	// Instantiate the options page.
	$Wsac_Options_Framework_Admin = new Wsac_Options_Framework_Admin;
	$Wsac_Options_Framework_Admin->init();

	// Instantiate the media uploader class
	$options_framework_media_uploader = new Wsac_Options_Framework_Media_Uploader;
	$options_framework_media_uploader->init();

}

add_action( 'init', 'wsac_optionsframework_init', 20 );

endif;


/**
 * Helper function to return the theme option value.
 * If no value has been saved, it returns $default.
 * Needed because options are saved as serialized strings.
 *
 * Not in a class to support backwards compatibility in themes.
 */

if ( ! function_exists( 'wsac_of_get_option' ) ) :

function wsac_of_get_option( $name, $default = false ) {
	$config = get_option( 'wsac_optionsframework' );

	if ( ! isset( $config['id'] ) ) {
		return $default;
	}

	$options = get_option( $config['id
		'] );

	if ( isset( $options[$name] ) ) {
		return $options[$name];
	}

	return $default;
}

endif;