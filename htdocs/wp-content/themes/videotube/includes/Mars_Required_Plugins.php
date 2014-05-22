<?php
/**
 * VideoTube Required Plugin
 * Display the notifcation about the required plugins, TGM_Plugin_Activation class is required.
 * @author 		Toan Nguyen
 * @category 	Core
 * @version     1.0.0
 */
if( !defined('ABSPATH') ) exit;
function mars_theme_register_required_plugins() {

	/**
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(
		array(
			'name'					=>	'Advanced Custom Fields',
			'slug'					=>	'advanced-custom-fields',
			'source'				=>	get_template_directory() . '/plugins/advanced-custom-fields.zip',
			'required'				=>	true,
			'external_url'			=>	null
		),
		array(
			'name'					=>	'Video Thumbnails',
			'slug'					=>	'video-thumbnails',
			'required'				=>	true,
			'source'				=>	get_template_directory() . '/plugins/video-thumbnails.zip',
			'external_url'			=>	null
		),
		array(
			'name'					=>	'Prime Strategy Page Navi',
			'slug'					=>	'prime-strategy-page-navi',
			'required'				=>	true,
			'source'				=>	get_template_directory() . '/plugins/prime-strategy-page-navi.zip',
			'external_url'			=>	null
		),
		array(
			'name'					=>	'Social Count Plus',
			'slug'					=>	'social-count-plus',
			'required'				=>	true,
			'source'				=>	get_template_directory() . '/plugins/social-count-plus.zip',
			'external_url'			=>	null
		),
		array(
			'name'					=>	'Easy Bootstrap Shortcode',
			'slug'					=>	'easy-bootstrap-shortcodes',
			'required'				=>	false,
			'source'				=>	get_template_directory() . '/plugins/easy-bootstrap-shortcodes.zip',
		),
		array(
			'name'					=>	'Recaptcha Class',
			'slug'					=>	'recaptchalib',
			'required'				=>	false,
			'source'				=>	get_template_directory() . '/plugins/recaptchalib.zip',
		),
		array(
			'name'					=>	'VideoTube Feed',
			'slug'					=>	'videotube-feed',
			'required'				=>	false,
			'source'				=>	get_template_directory() . '/plugins/videotube-feed.zip',
		),
		array(
			'name'					=>	'VideoTube Video Search',
			'slug'					=>	'videotube-videosearch',
			'required'				=>	false,
			'source'				=>	get_template_directory() . '/plugins/videotube-videosearch.zip',
		),		
		array(
			'name'					=>	'Profile Builder',
			'slug'					=>	'profile-builder',
			'required'				=>	false,
			'source'				=>	get_template_directory() . '/plugins/profile-builder.1.1.59.zip',
		)					
	);
	/**
	 * Array of configuration settings. Amend each line as needed.
	 * If you want the default strings to be available under your own theme domain,
	 * leave the strings uncommented.
	 * Some of the strings are added into a sprintf, so see the comments at the
	 * end of each line for what each argument will be.
	 */
	$config = array(
		'domain'       		=> 'mars',         	// Text domain - likely want to be the same as your theme.
		'default_path' 		=> '',                         	// Default absolute path to pre-packaged plugins
		'parent_menu_slug' 	=> 'themes.php', 				// Default parent menu slug
		'parent_url_slug' 	=> 'themes.php', 				// Default parent URL slug
		'menu'         		=> 'install-required-plugins', 	// Menu slug
		'has_notices'      	=> true,                       	// Show admin notices or not
		'is_automatic'    	=> false,					   	// Automatically activate plugins after installation or not
		'message' 			=> '',							// Message to output right before the plugins table
		'strings'      		=> array(
			'page_title'                       			=> __( 'Install Required Plugins', 'mars' ),
			'menu_title'                       			=> __( 'Install Plugins', 'mars' ),
			'installing'                       			=> __( 'Installing Plugin: %s', 'mars' ), // %1$s = plugin name
			'oops'                             			=> __( 'Something went wrong with the plugin API.', 'mars' ),
			'notice_can_install_required'     			=> _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.' ), // %1$s = plugin name(s)
			'notice_can_install_recommended'			=> _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.' ), // %1$s = plugin name(s)
			'notice_cannot_install'  					=> _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ), // %1$s = plugin name(s)
			'notice_can_activate_required'    			=> _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
			'notice_can_activate_recommended'			=> _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
			'notice_cannot_activate' 					=> _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ), // %1$s = plugin name(s)
			'notice_ask_to_update' 						=> _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ), // %1$s = plugin name(s)
			'notice_cannot_update' 						=> _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ), // %1$s = plugin name(s)
			'install_link' 					  			=> _n_noop( 'Begin installing plugin', 'Begin installing plugins' ),
			'activate_link' 				  			=> _n_noop( 'Activate installed plugin', 'Activate installed plugins' ),
			'return'                           			=> __( 'Return to Required Plugins Installer', 'mars' ),
			'plugin_activated'                 			=> __( 'Plugin activated successfully.', 'mars' ),
			'complete' 									=> __( 'All plugins installed and activated successfully. %s', 'mars' ), // %1$s = dashboard link
			'nag_type'									=> 'updated' // Determines admin notice type - can only be 'updated' or 'error'
		)
	);

	tgmpa( $plugins, $config );

}
add_action( 'tgmpa_register', 'mars_theme_register_required_plugins' );