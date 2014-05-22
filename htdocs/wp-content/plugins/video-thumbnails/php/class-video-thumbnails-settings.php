<?php

/*  Copyright 2013 Sutherland Boswell  (email : sutherland.boswell@gmail.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as 
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class Video_Thumbnails_Settings {

	public $options;

	var $default_options = array(
		'save_media'   => 1,
		'set_featured' => 1,
		'post_types'   => array( 'post' ),
		'custom_field' => ''
	);

	function __construct() {
		// Activation and deactivation hooks
		register_activation_hook( VIDEO_THUMBNAILS_PATH . '/video-thumbnails.php', array( &$this, 'plugin_activation' ) );
		register_deactivation_hook( VIDEO_THUMBNAILS_PATH . '/video-thumbnails.php', array( &$this, 'plugin_deactivation' ) );
		// Set current options
		add_action( 'plugins_loaded', array( &$this, 'set_options' ) );
		// Add options page to menu
		add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
		// Initialize options
		add_action( 'admin_init', array( &$this, 'initialize_options' ) );
		// Custom field detection callback
		add_action( 'wp_ajax_video_thumbnail_custom_field_detection', array( &$this, 'custom_field_detection_callback' ) );
		// Ajax clear all callback
		add_action( 'wp_ajax_clear_all_video_thumbnails', array( &$this, 'ajax_clear_all_callback' ) );
		// Ajax test callbacks
		add_action( 'wp_ajax_video_thumbnail_provider_test', array( &$this, 'provider_test_callback' ) ); // Provider test
		add_action( 'wp_ajax_video_thumbnail_saving_media_test', array( &$this, 'saving_media_test_callback' ) ); // Saving media test
		add_action( 'wp_ajax_video_thumbnail_markup_detection_test', array( &$this, 'markup_detection_test_callback' ) ); // Markup input test
		// Settings page actions
		if ( isset ( $_GET['page'] ) && ( $_GET['page'] == 'video_thumbnails' ) ) {
			// Admin scripts
			add_action( 'admin_enqueue_scripts', array( &$this, 'admin_scripts' ) );
		}
		// Add "Go Pro" call to action to settings footer
		add_action( 'video_thumbnails/settings_footer', array( 'Video_Thumbnails_Settings', 'settings_footer' ) );
	}

	// Activation hook
	function plugin_activation() {
		add_option( 'video_thumbnails', $this->default_options );
	}

	// Deactivation hook
	function plugin_deactivation() {
		delete_option( 'video_thumbnails' );
	}

	// Set options & possibly upgrade
	function set_options() {
		// Get the current options from the database
		$options = get_option( 'video_thumbnails' );
		// If there aren't any options, load the defaults
		if ( ! $options ) $options = $this->default_options;
		// Check if our options need upgrading
		$options = $this->upgrade_options( $options );
		// Set the options class variable
		$this->options = $options;
	}

	function upgrade_options( $options ) {

		// Boolean for if options need updating
		$options_need_updating = false;

		// If there isn't a settings version we need to check for pre 2.0 settings
		if ( ! isset( $options['version'] ) ) {

			// Check for post type setting
			$post_types = get_option( 'video_thumbnails_post_types' );

			// If there is a a post type option we know there should be others
			if ( $post_types !== false ) {

				$options['post_types'] = $post_types;
				delete_option( 'video_thumbnails_post_types' );

				$options['save_media'] = get_option( 'video_thumbnails_save_media' );
				delete_option( 'video_thumbnails_save_media' );

				$options['set_featured'] = get_option( 'video_thumbnails_set_featured' );
				delete_option( 'video_thumbnails_set_featured' );

				$options['custom_field'] = get_option( 'video_thumbnails_custom_field' );
				delete_option( 'video_thumbnails_custom_field' );

			}

			// Updates the options version to 2.0
			$options['version'] = '2.0';
			$options_need_updating = true;

		}

		if ( version_compare( $options['version'], VIDEO_THUMBNAILS_VERSION, '<' ) ) {
			$options['version'] = VIDEO_THUMBNAILS_VERSION;
			$options_need_updating = true;
		}

		// Save options to database if they've been updated
		if ( $options_need_updating ) {
			update_option( 'video_thumbnails', $options );
		}

		return $options;

	}

	function admin_menu() {
		add_options_page(
			'Video Thumbnail Options',
			'Video Thumbnails',
			'manage_options',
			'video_thumbnails',
			array( &$this, 'options_page' )
		);
	}

	function admin_scripts() {
		wp_enqueue_script( 'video_thumbnails_settings', plugins_url( 'js/settings.js' , VIDEO_THUMBNAILS_PATH . '/video-thumbnails.php' ), array( 'jquery' ), VIDEO_THUMBNAILS_VERSION );
	}

	function custom_field_detection_callback() {
		if ( current_user_can( 'manage_options' ) ) {
			echo $this->detect_custom_field();
		}
		die();
	}

	function detect_custom_field() {
		global $video_thumbnails;
		$latest_post = get_posts( array(
			'posts_per_page'  => 1,
			'post_type'  => $this->options['post_types'],
			'orderby' => 'modified',
		) );
		$latest_post = $latest_post[0];
		$custom = get_post_meta( $latest_post->ID );
		foreach ( $custom as $name => $values ) {
			foreach ($values as $value) {
				if ( $video_thumbnails->get_first_thumbnail_url( $value ) ) {
					return $name;
				}
			}
		}
	}

	function ajax_clear_all_callback() {
		if ( wp_verify_nonce( $_POST['nonce'], 'clear_all_video_thumbnails' ) ) {
			global $wpdb;
			// Clear images from media library
			$media_library_items = get_posts( array(
				'showposts'  => -1,
				'post_type'  => 'attachment',
				'meta_key'   => 'video_thumbnail',
				'meta_value' => '1',
				'fields'     => 'ids'
			) );
			foreach ( $media_library_items as $item ) {
				wp_delete_attachment( $item, true );
			}
			echo '<p><span style="color:green">&#10004;</span> ' . sprintf( _n( '1 attachment deleted', '%s attachments deleted', count( $media_library_items ), 'video-thumbnails' ), count( $media_library_items ) ) . '</p>';
			// Clear custom fields
			$custom_fields_cleared = $wpdb->query( "DELETE FROM $wpdb->postmeta WHERE meta_key='_video_thumbnail'" );
			echo '<p><span style="color:green">&#10004;</span> ' . sprintf( _n( '1 custom field cleared', '%s custom fields cleared', $custom_fields_cleared, 'video-thumbnails' ), $custom_fields_cleared ) . '</p>';
		} else {
			echo '<p><span style="color:red">&#10006;</span> <strong>Error</strong>: Could not verify nonce.</p>';
		}

		die();
	}

	function get_file_hash( $url ) {
		$response = wp_remote_get( $url, array( 'sslverify' => false ) );
		if( is_wp_error( $response ) ) {
			$result = false;
		} else {
			$result = md5( $response['body'] );
		}
		return $result;
	}

	function provider_test_callback() {

		global $video_thumbnails;

		?>
			<table class="widefat">
				<thead>
					<tr>
						<th>Name</th>
						<th>Pass/Fail</th>
						<th>Result</th>
					</tr>
				</thead>
				<tbody>
			<?php
			$passed = 0;
			$failed = 0;
			foreach ( $video_thumbnails->providers as $provider ) {
				foreach ( $provider->test_cases as $test_case ) {
					echo '<tr>';
					echo '<td><strong>' . $provider->service_name . '</strong> - ' . $test_case['name'] . '</td>';
					$result = $video_thumbnails->get_first_thumbnail_url( $test_case['markup'] );
					if ( is_wp_error( $result ) ) {
						$error_string = $result->get_error_message();
						echo '<td style="color:red;">&#10007; Failed</td>';
						echo '<td>';
						echo '<div class="error"><p>' . $error_string . '</p></div>';
						echo '</td>';
						$failed++;
					} else {
						$result = explode( '?', $result );
						$result = $result[0];
						$result_hash = false;
						if ( $result == $test_case['expected'] ) {
							$matched = true;
						} else {
							$result_hash = $this->get_file_hash( $result );
							$matched = ( $result_hash == $test_case['expected_hash'] ? true : false );
						}
						
						if ( $matched ) {
							echo '<td style="color:green;">&#10004; Passed</td>';
							$passed++;
						} else {
							echo '<td style="color:red;">&#10007; Failed</td>';
							$failed++;
						}
						echo '<td>';
						if ( $result ) {
							echo '<a href="' . $result . '">View Image</a>';
						}
						if ( $result_hash ) {
							echo ' <code>' . $result_hash . '</code>';
						}
						echo '</td>';
					}
					echo '</tr>';
				}
			} ?>
				<tbody>
				<tfoot>
					<tr>
						<th></th>
						<th><span style="color:green;">&#10004; <?php echo $passed; ?></span> / <span style="color:red;">&#10007; <?php echo $failed; ?></span></th>
						<th></th>
					</tr>
				</tfoot>
			</table>
		<?php die();
	} // End provider test callback

	function saving_media_test_callback() {

		// Try saving 'http://img.youtube.com/vi/dMH0bHeiRNg/0.jpg' to media library
		$attachment_id = Video_Thumbnails::save_to_media_library( 'http://img.youtube.com/vi/dMH0bHeiRNg/0.jpg', 1 );
		if ( is_wp_error( $attachment_id ) ) {
			echo '<p><span style="color:red;">&#10006;</span> ' . $attachment_id->get_error_message() . '</p>';
		} else {
			echo '<p><span style="color:green;">&#10004;</span>Attachment created with an ID of ' . $attachment_id . '</p>';
			wp_delete_attachment( $attachment_id, true );
			echo '<p><span style="color:green;">&#10004;</span>Attachment with an ID of ' . $attachment_id . ' deleted</p>';			
		}

		die();
	} // End saving media test callback

	function markup_detection_test_callback() {

		$new_thumbnail = null;

		global $video_thumbnails;

		$new_thumbnail = $video_thumbnails->get_first_thumbnail_url( stripslashes( $_POST['markup'] ) );

		if ( $new_thumbnail == null ) {
			// No thumbnail
			echo '<p><span style="color:red;">&#10006;</span> No thumbnail found</p>';
		} elseif ( is_wp_error( $new_thumbnail ) ) {
			// Error finding thumbnail
			echo '<p><span style="color:red;">&#10006;</span> Error: ' . $new_thumbnail->get_error_message() . '</p>';
		} else {
			// Found a thumbnail
			$remote_response = wp_remote_head( $new_thumbnail );
			if ( is_wp_error( $remote_response ) ) {
				// WP Error trying to read image from remote server
				echo '<p><span style="color:red;">&#10006;</span> Thumbnail found, but there was an error retrieving the URL.</p>';
				echo '<p>Error Details: ' . $remote_response->get_error_message() . '</p>';
			} elseif ( $remote_response['response']['code'] != '200' ) {
				// Response code isn't okay
				echo '<p><span style="color:red;">&#10006;</span> Thumbnail found, but it may not exist on the source server. If opening the URL below in your web browser returns an error, the source is providing an invalid URL.</p>';
				echo '<p>Thumbnail URL: <a href="' . $new_thumbnail . '" target="_blank">' . $new_thumbnail . '</a>';
			} else {
				// Everything is okay!
				echo '<p><span style="color:green;">&#10004;</span> Thumbnail found! Image should appear below. <a href="' . $new_thumbnail . '" target="_blank">View full size</a></p>';
				echo '<p><img src="' . $new_thumbnail . '" style="max-width: 500px;"></p>';
			}
		}

		die();
	} // End markup detection test callback

	function initialize_options() {
		add_settings_section(  
			'general_settings_section',
			'General Settings',
			array( &$this, 'general_settings_callback' ),
			'video_thumbnails'
		);
		$this->add_checkbox_setting(
			'save_media',
			'Save Thumbnails to Media Library',
			'Checking this option will download video thumbnails to your server'
		);
		$this->add_checkbox_setting(
			'set_featured',
			'Automatically Set Featured Image',
			'Check this option to automatically set video thumbnails as the featured image (requires saving to media library)'
		);
		// Get post types
		$post_types = get_post_types( null, 'names' );
		// Remove certain post types from array
		$post_types = array_diff( $post_types, array( 'attachment', 'revision', 'nav_menu_item' ) );
		$this->add_multicheckbox_setting(
			'post_types',
			'Post Types',
			$post_types
		);
		$this->add_text_setting(
			'custom_field',
			'Custom Field (optional)',
			'<a href="#" class="button" id="vt_detect_custom_field">Automatically Detect</a> Enter the name of the custom field where your embed code or video URL is stored.'
		);
		register_setting( 'video_thumbnails', 'video_thumbnails', array( &$this, 'sanitize_callback' ) );
	}

	function sanitize_callback( $input ) {
		$current_settings = get_option( 'video_thumbnails' );
		$output = array();
		// General settings
		if ( !isset( $input['provider_options'] ) ) {
			foreach( $current_settings as $key => $value ) {
				if ( $key == 'version' OR $key == 'providers' ) {
					$output[$key] = $current_settings[$key];
				} elseif ( isset( $input[$key] ) ) {
					$output[$key] = $input[$key];
				} else {
					$output[$key] = '';
				}
			}
		}
		// Provider settings
		else {
			$output = $current_settings;
			unset( $output['providers'] );
			$output['providers'] = $input['providers'];
		}
		return $output;
	}  

	function general_settings_callback() {  
		echo '<p>These options configure where the plugin will search for videos and what to do with thumbnails once found.</p>';  
	}

	function add_checkbox_setting( $slug, $name, $description ) {
		add_settings_field(
			$slug,
			$name,
			array( &$this, 'checkbox_callback' ),
			'video_thumbnails',
			'general_settings_section',
			array(
				'slug'        => $slug,
				'description' => $description
			)
		);
	}

	function checkbox_callback( $args ) {
		$html = '<label for="' . $args['slug'] . '"><input type="checkbox" id="' . $args['slug'] . '" name="video_thumbnails[' . $args['slug'] . ']" value="1" ' . checked( 1, $this->options[$args['slug']], false ) . '/> ' . $args['description'] . '</label>';
		echo $html;
	}

	function add_multicheckbox_setting( $slug, $name, $options ) {
		add_settings_field(
			$slug,
			$name,
			array( &$this, 'multicheckbox_callback' ),
			'video_thumbnails',
			'general_settings_section',
			array(
				'slug'    => $slug,
				'options' => $options
			)
		);
	}

	function multicheckbox_callback( $args ) {
		if ( is_array( $this->options[$args['slug']] ) ) {
			$selected_types = $this->options[$args['slug']];
		} else {
			$selected_types = array();
		}
		$html = '';
		foreach ( $args['options'] as $option ) {
			$checked = ( in_array( $option, $selected_types ) ? 'checked="checked"' : '' );
			$html .= '<label for="' . $args['slug'] . '_' . $option . '"><input type="checkbox" id="' . $args['slug'] . '_' . $option . '" name="video_thumbnails[' . $args['slug'] . '][]" value="' . $option . '" ' . $checked . '/> ' . $option . '</label><br>';			
		}
		echo $html;
	}

	function add_text_setting( $slug, $name, $description ) {
		add_settings_field(
			$slug,
			$name,
			array( &$this, 'text_field_callback' ),
			'video_thumbnails',
			'general_settings_section',
			array(
				'slug'          => $slug,
				'description' => $description
			)
		);
	}

	function text_field_callback( $args ) {
		$html = '<input type="text" id="' . $args['slug'] . '" name="video_thumbnails[' . $args['slug'] . ']" value="' . $this->options[$args['slug']] . '"/>';
		$html .= '<label for="' . $args['slug'] . '"> ' . $args['description'] . '</label>';
		echo $html;
	}

	function options_page() {

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		?><div class="wrap">

			<div id="icon-options-general" class="icon32"></div><h2>Video Thumbnails Options</h2>

			<?php $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'general_settings'; ?> 
			<h2 class="nav-tab-wrapper">
				<a href="?page=video_thumbnails&tab=general_settings" class="nav-tab <?php echo $active_tab == 'general_settings' ? 'nav-tab-active' : ''; ?>">General</a>
				<a href="?page=video_thumbnails&tab=provider_settings" class="nav-tab <?php echo $active_tab == 'provider_settings' ? 'nav-tab-active' : ''; ?>">Providers</a>
				<a href="?page=video_thumbnails&tab=mass_actions" class="nav-tab <?php echo $active_tab == 'mass_actions' ? 'nav-tab-active' : ''; ?>">Mass Actions</a>
				<a href="?page=video_thumbnails&tab=debugging" class="nav-tab <?php echo $active_tab == 'debugging' ? 'nav-tab-active' : ''; ?>">Debugging</a>
				<a href="?page=video_thumbnails&tab=support" class="nav-tab <?php echo $active_tab == 'support' ? 'nav-tab-active' : ''; ?>">Support</a>
			</h2>

			<?php
			// Main settings
			if ( $active_tab == 'general_settings' ) {
			?>
			<h3>Getting started</h3>

			<p>If your theme supports post thumbnails, just leave "Save Thumbnails to Media Library" and "Automatically Set Featured Image" enabled, then select what post types you'd like scanned for videos.</p>

			<p>For more detailed instructions, check out the page for <a href="http://wordpress.org/extend/plugins/video-thumbnails/">Video Thumbnails on the official plugin directory</a>.</p>

			<form method="post" action="options.php">  
				<?php settings_fields( 'video_thumbnails' ); ?>  
				<?php do_settings_sections( 'video_thumbnails' ); ?>            
				<?php submit_button(); ?>  
			</form>

			<?php
			// End main settings
			}
			// Provider Settings
			if ( $active_tab == 'provider_settings' ) {
			?>

			<form method="post" action="options.php">
				<input type="hidden" name="video_thumbnails[provider_options]" value="1" />
				<?php settings_fields( 'video_thumbnails' ); ?>  
				<?php do_settings_sections( 'video_thumbnails_providers' ); ?>            
				<?php submit_button(); ?>  
			</form>

			<?php
			// End provider settings
			}
			// Scan all posts
			if ( $active_tab == 'mass_actions' ) {
			?>
			<h3>Scan All Posts</h3>

			<p>Scan all of your past posts for video thumbnails. Be sure to save any settings before running the scan.</p>

			<p><a class="button-primary" href="<?php echo admin_url( 'tools.php?page=video-thumbnails-bulk' ); ?>">Scan Past Posts</a></p>

			<h3>Clear all Video Thumbnails</h3>

			<p>This will clear the video thumbnail field for all posts and delete any video thumbnail attachments. Note: This only works for attachments added using version 2.0 or later.</p>

			<p><input type="submit" class="button-primary" onclick="clear_all_video_thumbnails('<?php echo wp_create_nonce( 'clear_all_video_thumbnails' ); ?>');" value="Clear Video Thumbnails" /></p>

			<div id="clear-all-video-thumbnails-result"></div>

			<?php
			// End scan all posts
			}
			// Debugging
			if ( $active_tab == 'debugging' ) {
			?>

			<p>Use these tests to help diagnose any problems. Please include results when requesting support.</p>

			<h3>Test Thumbnail Providers</h3>

			<p>This test automatically searches a sample for every type of video supported and compares it to the expected value. Sometimes tests may fail due to API rate limits.</p>

			<div id="provider-test">
				<p><input type="submit" class="button-primary" onclick="test_video_thumbnail('provider');" value="Test Providers" /></p>
			</div>

			<h3>Test Markup for Video</h3>

			<p>Copy and paste an embed code below to see if a video is detected.</p>

			<textarea id="markup-input" cols="50" rows="5"></textarea>

			<p><input type="submit" class="button-primary" onclick="test_video_thumbnail_markup_detection();" value="Scan For Thumbnail" /></p>

			<div id="markup-test-result"></div>

			<h3>Test Saving to Media Library</h3>

			<p>This test checks for issues with the process of saving a remote thumbnail to your local media library.</p>

			<p>Also be sure to test that you can manually upload an image to your site. If you're unable to upload images, you may need to <a href="http://codex.wordpress.org/Changing_File_Permissions">change file permissions</a>.</p>

			<div id="saving_media-test">
				<p><input type="submit" class="button-primary" onclick="test_video_thumbnail('saving_media');" value="Test Image Downloading" /></p>
			</div>

			<h3>Installation Information</h3>
			<table class="widefat">
				<thead>
					<tr>
						<th></th>
						<th></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><strong>WordPress Version</strong></td>
						<td><?php echo get_bloginfo( 'version' ); ?></td>
						<td></td>
					</tr>
					<tr>
						<td><strong>Video Thumbnails Version</strong></td>
						<td><?php echo VIDEO_THUMBNAILS_VERSION; ?></td>
						<td></td>
					</tr>
					<tr>
						<td><strong>Video Thumbnails Settings Version</strong></td>
						<td><?php echo $this->options['version']; ?></td>
						<td></td>
					</tr>
					<tr>
						<td><strong>PHP Version</strong></td>
						<td><?php echo PHP_VERSION; ?></td>
						<td></td>
					</tr>
					<tr>
						<td><strong>Post Thumbnails</strong></td>
						<td><?php if ( current_theme_supports( 'post-thumbnails' ) ) : ?><span style="color:green">&#10004;</span> Your theme supports post thumbnails.<?php else: ?><span style="color:red">&#10006;</span> Your theme doesn't support post thumbnails, you'll need to make modifications or switch to a different theme. <a href="http://codex.wordpress.org/Post_Thumbnails">More info</a><?php endif; ?></td>
						<td></td>
					</tr>
					<tr>
						<td><strong>Providers</strong></td>
						<td>
							<?php global $video_thumbnails; ?>
								<?php $provider_names = array(); foreach ( $video_thumbnails->providers as $provider ) { $provider_names[] = $provider->service_name; }; ?>
							<strong><?php echo count( $video_thumbnails->providers ); ?></strong>: <?php echo implode( ', ', $provider_names ); ?>
						</td>
						<td></td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<th></th>
						<th></th>
						<th></th>
					</tr>
				</tfoot>
			</table>

			<?php
			// End debugging
			}
			// Support
			if ( $active_tab == 'support' ) {

				Video_Thumbnails::no_video_thumbnail_troubleshooting_instructions();

			// End support
			}
			?>

			<?php do_action( 'video_thumbnails/settings_footer' ); ?>

		</div><?php
	}

	public static function settings_footer() {
		?>
		<div style="width: 250px; margin: 20px 0; padding: 0 20px; background: #fff; border: 1px solid #dfdfdf; text-align: center;">
			<div>
				<p>Support video thumbnails and unlock additional features</p>
				<p><a href="https://refactored.co/plugins/video-thumbnails" class="button button-primary button-large">Go Pro</a></p>
			</div>
		</div>
		<?php
	}

}

?>