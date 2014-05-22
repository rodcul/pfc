<?php
/**
  ReduxFramework Sample Config File
  For full documentation, please visit: https://github.com/ReduxFramework/ReduxFramework/wiki
 * */

if (!class_exists("Redux_Framework_config")) {

    class Redux_Framework_config {

        public $args = array();
        public $sections = array();
        public $theme;
        public $ReduxFramework;
		private $google_api_key = null;
        public function __construct() {
			$this->initSettings();
        }

        public function initSettings() {

            if ( !class_exists("ReduxFramework" ) ) {
                return;
            }       
            
            // Just for demo purposes. Not needed per say.
            $this->theme = wp_get_theme();

            // Set the default arguments
            $this->setArguments();

            // Set a few help tabs so you can see how it's done
            $this->setHelpTabs();

            // Create the sections and fields
            $this->setSections();

            if (!isset($this->args['opt_name'])) { // No errors please
                return;
            }

            // If Redux is running as a plugin, this will remove the demo notice and links
            //add_action( 'redux/plugin/hooks', array( $this, 'remove_demo' ) );
            // Function to test the compiler hook and demo CSS output.
            //add_filter('redux/options/'.$this->args['opt_name'].'/compiler', array( $this, 'compiler_action' ), 10, 2); 
            // Above 10 is a priority, but 2 in necessary to include the dynamically generated CSS to be sent to the function.
            // Change the arguments after they've been declared, but before the panel is created
            //add_filter('redux/options/'.$this->args['opt_name'].'/args', array( $this, 'change_arguments' ) );
            // Change the default value of a field after it's been set, but before it's been useds
            //add_filter('redux/options/'.$this->args['opt_name'].'/defaults', array( $this,'change_defaults' ) );
            // Dynamically add a section. Can be also used to modify sections/fields
            //add_filter('redux/options/' . $this->args['opt_name'] . '/sections', array($this, 'dynamic_section'));

            $this->ReduxFramework = new ReduxFramework($this->sections, $this->args);
        }

        /**

          This is a test function that will let you see when the compiler hook occurs.
          It only runs if a field	set with compiler=>true is changed.

         * */
        function compiler_action($options, $css) {
            //echo "<h1>The compiler hook has run!";
            //print_r($options); //Option values
            //print_r($css); // Compiler selector CSS values  compiler => array( CSS SELECTORS )

            /*
              // Demo of how to use the dynamic CSS and write your own static CSS file
              $filename = dirname(__FILE__) . '/style' . '.css';
              global $wp_filesystem;
              if( empty( $wp_filesystem ) ) {
              require_once( ABSPATH .'/wp-admin/includes/file.php' );
              WP_Filesystem();
              }

              if( $wp_filesystem ) {
              $wp_filesystem->put_contents(
              $filename,
              $css,
              FS_CHMOD_FILE // predefined mode settings for WP files
              );
              }
             */
        }

        /**

          Custom function for filtering the sections array. Good for child themes to override or add to the sections.
          Simply include this function in the child themes functions.php file.

          NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
          so you must use get_template_directory_uri() if you want to use any of the built in icons

         * */
        function dynamic_section($sections) {
            //$sections = array();
            $sections[] = array(
                'title' => __('Section via hook', 'redux-framework-demo'),
                'desc' => __('<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'redux-framework-demo'),
                'icon' => 'el-icon-paper-clip',
                // Leave this as a blank section, no options just some intro text set above.
                'fields' => array()
            );

            return $sections;
        }

        /**

          Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.

         * */
        function change_arguments($args) {
            //$args['dev_mode'] = true;

            return $args;
        }

        /**

          Filter hook for filtering the default value of any given field. Very useful in development mode.

         * */
        function change_defaults($defaults) {
            $defaults['str_replace'] = "Testing filter hook!";

            return $defaults;
        }

        // Remove the demo link and the notice of integrated demo from the redux-framework plugin
        function remove_demo() {

            // Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
            if (class_exists('ReduxFrameworkPlugin')) {
                remove_filter('plugin_row_meta', array(ReduxFrameworkPlugin::get_instance(), 'plugin_meta_demo_mode_link'), null, 2);
            }

            // Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
            remove_action('admin_notices', array(ReduxFrameworkPlugin::get_instance(), 'admin_notices'));
        }

        public function setSections() {

            /**
              Used within different fields. Simply examples. Search for ACTUAL DECLARATION for field examples
             * */
            // Background Patterns Reader
            $sample_patterns_path = ReduxFramework::$_dir . '../sample/patterns/';
            $sample_patterns_url = ReduxFramework::$_url . '../sample/patterns/';
            $sample_patterns = array();

            if (is_dir($sample_patterns_path)) :

                if ($sample_patterns_dir = opendir($sample_patterns_path)) :
                    $sample_patterns = array();

                    while (( $sample_patterns_file = readdir($sample_patterns_dir) ) !== false) {

                        if (stristr($sample_patterns_file, '.png') !== false || stristr($sample_patterns_file, '.jpg') !== false) {
                            $name = explode(".", $sample_patterns_file);
                            $name = str_replace('.' . end($name), '', $sample_patterns_file);
                            $sample_patterns[] = array('alt' => $name, 'img' => $sample_patterns_url . $sample_patterns_file);
                        }
                    }
                endif;
            endif;

            ob_start();

            $ct = wp_get_theme();
            $this->theme = $ct;
            $item_name = $this->theme->get('Name');
            $tags = $this->theme->Tags;
            $screenshot = $this->theme->get_screenshot();
            $class = $screenshot ? 'has-screenshot' : '';

            $customize_title = sprintf(__('Customize &#8220;%s&#8221;', 'redux-framework-demo'), $this->theme->display('Name'));
            ?>
            <div id="current-theme" class="<?php echo esc_attr($class); ?>">
            <?php if ($screenshot) : ?>
                <?php if (current_user_can('edit_theme_options')) : ?>
                        <a href="<?php echo wp_customize_url(); ?>" class="load-customize hide-if-no-customize" title="<?php echo esc_attr($customize_title); ?>">
                            <img src="<?php echo esc_url($screenshot); ?>" alt="<?php esc_attr_e('Current theme preview'); ?>" />
                        </a>
                <?php endif; ?>
                    <img class="hide-if-customize" src="<?php echo esc_url($screenshot); ?>" alt="<?php esc_attr_e('Current theme preview'); ?>" />
            <?php endif; ?>

                <h4>
            <?php echo $this->theme->display('Name'); ?>
                </h4>

                <div>
                    <ul class="theme-info">
                        <li><?php printf(__('By %s', 'redux-framework-demo'), $this->theme->display('Author')); ?></li>
                        <li><?php printf(__('Version %s', 'redux-framework-demo'), $this->theme->display('Version')); ?></li>
                        <li><?php echo '<strong>' . __('Tags', 'redux-framework-demo') . ':</strong> '; ?><?php printf($this->theme->display('Tags')); ?></li>
                    </ul>
                    <p class="theme-description"><?php echo $this->theme->display('Description'); ?></p>
                <?php
                if ($this->theme->parent()) {
                    printf(' <p class="howto">' . __('This <a href="%1$s">child theme</a> requires its parent theme, %2$s.') . '</p>', __('http://codex.wordpress.org/Child_Themes', 'redux-framework-demo'), $this->theme->parent()->display('Name'));
                }
                ?>

                </div>

            </div>

            <?php
            $item_info = ob_get_contents();

            ob_end_clean();

            $sampleHTML = '';
            if (file_exists(dirname(__FILE__) . '/info-html.html')) {
                /** @global WP_Filesystem_Direct $wp_filesystem  */
                global $wp_filesystem;
                if (empty($wp_filesystem)) {
                    require_once(ABSPATH . '/wp-admin/includes/file.php');
                    WP_Filesystem();
                }
                $sampleHTML = $wp_filesystem->get_contents(dirname(__FILE__) . '/info-html.html');
            }
			//---- Theme Option Here ----//
			$this->sections[] 	=	array(
				'title'	=>	__('General','mars'),
				'icon'	=>	'el-icon-cogs',
				'desc'	=>	null,
				'fields'	=>	array(
					array(
						'id'	=>	'logo',
						'type'	=>	'media',
						'url' => true,
                        'subtitle' => __('Upload any media using the WordPress native uploader', 'mars'),
                        'default' => array('url' => get_template_directory_uri() . '/img/logo.png'),					
						'title'	=>	__('Logo (194*31px)','mars')
					),
					array(
						'id'	=>	'favicon',
						'type'	=>	'media',
						'preview' => false,
						'url' => true,
                        'subtitle' => __('Upload any media using the WordPress native uploader', 'mars'),				
						'title'	=>	__('Favicon','mars')
					),	
                   array(
                        'id' => 'custom_css',
                        'type' => 'ace_editor',
                        'title' => __('Custom CSS', 'mars'),
                        'subtitle' => __('Paste your CSS code here, no style tag.', 'mars'),
                        'mode' => 'css',
                        'theme' => 'monokai'
                    ),	
                    array(
                        'id' => 'custom_js',
                        'type' => 'ace_editor',
                        'title' => __('Custom JS', 'mars'),
                        'subtitle' => __('Paste your JS code here, no script tag, eg: alert(\'hello world\');', 'mars'),
                        'mode' => 'javascript',
                        'theme' => 'chrome'
                    ),
					array(
						'id'	=>	'copyright_text',
						'title'	=>	__('Copyright Text','mars'),
						'type'	=>	'editor',
						'default'	=>	'<p>Copyright 2014 By MarsTheme All rights reserved. Powered by WordPress &amp; MarsTheme</p>'
					),
					array(
						'id'	=>	'google-analytics',
						'title'	=>	__('Google Analytics ID','mars'),
						'type'	=>	'text',
						'placeholder'	=>	'UA-39642846-1'
					),
                    array(
                        'id' => 'datetime_format',
                        'type' => 'button_set',
                        'title'	=>	__('Video Datetime Format','mars'),
                        'options' => array('default' => __('Default Format','mars'), 'videotube' => __('VideoTube Format','mars')),
                        'default' => 'videotube'
                    ),
                    array(
                        'id' => 'autoplay',
                        'type' => 'switch',
                        'title' => __('Video AutoPlay', 'mars'),
                        "default" => 1,
                        'on' => __('Yes','mars'),
                        'off' => __('No','mars'),
                    ),
                    array(
                        'id' => 'enable_channelpage',
                        'type' => 'switch',
                        'title' => __('Enable Channel Page', 'mars'),
                    	'desc'	=>	__('This feature will enable User Channel page, is used to replace for Author default page.','mars'),
                        "default" => 0,
                        'on' => __('Yes','mars'),
                        'off' => __('No','mars'),
                    )
				)
			);
            $this->sections[] = array(
                'icon' => 'el-icon-website',
                'title' => __('Styling Options', 'mars'),
                'fields' => array(
                    array(
                        'id' => 'color-header',
                        'type' => 'color',
                        'title' => __('Header Background Color', 'mars'),
                        'subtitle' => __('Pick a background color for the header (default: #ffffff).', 'mars'),
                        'default' => '#ffffff',
                        'validate' => 'color',
                    ),            
                    array(
                        'id' => 'body-background',
                        'type' => 'background',
                        'output' => array('body'),
                        'title' => __('Body Background', 'mars'),
                        'subtitle' => __('Body background with image, color, etc.', 'mars'),
                    	'default' => '#FFFFFF',
                    ),                  
                    array(
                        'id'        => 'typography-body',
                        'type'      => 'typography',
                        'title'     => __('Body Text', 'mars'),
                        'google'    => true,
                    	'font-style'	=>	false,
                    	'subsets'	=>	false,
                    	'font-weight'	=> false,
                    	'font-size'	=>	false,
                    	'line-height'	=>	false,
                    	'text-align'	=>	false,
                    	'color'		=>	false
                    ),
                    array(
                        'id'        => 'typography-headings',
                        'type'      => 'typography',
                        'title'     => __('Headings', 'mars'),
                        'google'    => true,
                    	'font-style'	=>	false,
                    	'subsets'	=>	false,
                    	'font-weight'	=> false,
                    	'font-size'	=>	false,
                    	'line-height'	=>	false,
                    	'text-align'	=>	false,
                    	'color'		=>	false
                    ), 
                    array(
                        'id'        => 'typography-menu',
                        'type'      => 'typography',
                        'title'     => __('Menu', 'mars'),
                        'google'    => true,
                    	'font-style'	=>	false,
                    	'subsets'	=>	false,
                    	'font-weight'	=> false,
                    	'font-size'	=>	false,
                    	'line-height'	=>	false,
                    	'text-align'	=>	false,
                    	'color'		=>	false
                    ),
                    array(
                        'id' => 'color-widget',
                        'type' => 'color',
                        'title' => __('Widget Title Background', 'mars'),
                        'subtitle' => __('Pick a background color for the Widget title (default: #e73737), only for Right Widget.', 'mars'),
                        'default' => '#e73737',
                        'validate' => 'color',
                    ),
                    array(
                        'id' => 'color-text-widget',
                        'type' => 'color',
                        'title' => __('Widget Title Color', 'mars'),
                        'subtitle' => __('Pick a color for the Widget title (default: #e73737), only for Right Widget', 'mars'),
                        'default' => 'hsl(0, 100%, 100%)',
                        'validate' => 'color',
                    ),                    
                    array(
                        'id' => 'color-header-navigation',
                        'type' => 'color',
                        'title' => __('Header Navigation Background', 'mars'),
                        'subtitle' => __('Pick a background color for the Header Navigation (Header Menu) (default: #4c5358).', 'mars'),
                        'default' => '#4c5358',
                        'validate' => 'color',
                    ),
                    array(
                        'id' => 'color-text-header-navigation',
                        'type' => 'color',
                        'title' => __('Header Navigation Color', 'mars'),
                        'subtitle' => __('Pick a color for the Header Navigation (Header Menu) (default: #4c5358).', 'mars'),
                        'default' => 'hsl(0, 100%, 100%)',
                        'validate' => 'color',
                    ),                    
                    array(
                        'id' => 'color-footer',
                        'type' => 'color',
                        'title' => __('Footer Background', 'mars'),
                        'subtitle' => __('Pick a background color for the footer (default: #111111).', 'mars'),
                        'default' => '#111111',
                        'validate' => 'color',
                    ),
                    array(
                        'id' => 'color-footer-text',
                        'type' => 'color',
                        'title' => __('Footer Text Color', 'mars'),
                        'subtitle' => __('Pick a color for Text in the footer (default: #ffffff).', 'mars'),
                        'default' => '#ffffff',
                        'validate' => 'color',
                    ),                    
                )
            );			
            $this->sections[] = array(
            	'title'	=>	__('Recaptcha','mars'),
            	'icon'	=>	'el-icon-cogs',
            	'fields'	=>	array(
					array(
						'id'	=>	'public_key',
						'title'	=>	__('Public Key','mars'),
						'type'	=>	'text',
						'subtitle'	=>	'This key is required if enable Recaptcha function.'
					),
					array(
						'id'	=>	'private_key',
						'title'	=>	__('Private Key','mars'),
						'type'	=>	'text',
						'subtitle'	=>	'This key is required if enable Recaptcha function.'
					)            		
            	)
            );
            $this->sections[] = array(
            	'title'	=>	__('Login/Register','mars'),
            	'icon'	=>	'el-icon-cogs',
            	'fields'	=>	array(
                    array(
                        'id' => 'loginpage',
                        'type' => 'select',
                        'data' => 'pages',
                        'title' => __('Login/Register page', 'mars'),
                    	'subtitle'	=>	__('If this page is configured, The Login/Register default page (wp-login.php) will be replaced.','mars'),
                    	'desc'	=>	__('Place [videotube_login] shortcode in Login page to display the Login/Register form.','mars')
                    ),
                    array(
                        'id' => 'login_register_captcha',
                        'type' => 'switch',
                        'title' => __('Enable Recaptcha', 'mars'),
                        "default" => 0,
                        'on' => __('Yes','mars'),
                        'off' => __('No','mars'),
                    )	                    
            	)
            );
			$this->sections[] = array(
				'title'	=>	__('Optimization','mars'),
				'icon'	=>	'el-icon-cogs',
				'desc'	=>	__('At this section, you can enable/disable SEO mode, HTTP compression.','mars'),
				'fields'	=>	array(
					array(
						'id' => 'rewrite_slug',
						'type'	=>	'text',
						'title'	=>	__('Video Slug','mars'),
						'default'	=>	'video',
						'subtitle'	=>	sprintf('This option will change default video slug, if you change this key, you must go to %s and click on Save Change button','<a href="'.admin_url('options-permalink.php').'">'.__('Settings/Permalink','mars').'</a>')
					),
                    array(
                        'id' => 'enable_seo',
                        'type' => 'switch',
                        'title' => __('Enable SEO mode.', 'mars'),
                        "default" => 0,
                        'on' => __('Yes','mars'),
                        'off' => __('No','mars'),
                    ),
                    array(
                        'id' => 'http_compression',
                        'type' => 'switch',
                        'title' => __('Enable HTTP Compression.', 'mars'),
                        "default" => 0,
                        'on' => __('Yes','mars'),
                        'off' => __('No','mars'),
                    ),
                    array(
                        'id' => 'hide_admin_bar',
                        'type' => 'switch',
                        'title' => __('Hide Admin Top Bar.', 'mars'),
                    	'desc'	=>	__('Only admin can see Admin Bar','mars'),
                        "default" => 0,
                        'on' => __('Yes','mars'),
                        'off' => __('No','mars'),
                    )
				)
			);
			$this->sections[] = array(
				'title'	=>	__('Socials','mars'),
				'desc'	=>	null,
				'icon'	=>	'el-icon-bullhorn',
				'fields'	=>	array(
                    array(
                        'id' => 'guestlike',
                        'type' => 'switch',
                        'title' => __('Allow Guest to Like', 'mars'),
                        "default" => 0,
                        'on' => __('Yes','mars'),
                        'off' => __('No','mars'),
                    ),					
					array(
						'id'	=>	'facebook',
						'title'	=>	__('Facebook','mars'),
						'type'	=>	'text',
						'desc'	=>	__('Facebook Profile or Fanpage URL','mars')
					),
					array(
						'id'	=>	'twitter',
						'title'	=>	__('Twitter','mars'),
						'type'	=>	'text',
						'desc'	=>	__('Twitter URL','mars')
					),
					array(
						'id'	=>	'google-plus',
						'title'	=>	__('Google Plus','mars'),
						'type'	=>	'text',
						'desc'	=>	__('Google Plus URL','mars')
					),
					array(
						'id'	=>	'instagram',
						'title'	=>	__('Instagram','mars'),
						'type'	=>	'text',
						'desc'	=>	__('Instagram URL','mars')
					),
					array(
						'id'	=>	'linkedin',
						'title'	=>	__('Linkedin','mars'),
						'type'	=>	'text',
						'desc'	=>	__('Linkedin URL','mars')
					),
					array(
						'id'	=>	'tumblr',
						'title'	=>	__('Tumblr','mars'),
						'type'	=>	'text',
						'desc'	=>	__('Tumblr URL','mars')
					)										
				)
			);
			//------------------ Submit Video ---------------
			$user_db = NULL;
			$users = get_users(array('role'=>null));
			foreach ( $users as $user ){
				$user_db[ $user->ID ] = $user->user_login;
			}
			$this->sections[] = array(
				'title'	=>	__('Video Uploader','mars'),
				'icon'	=>	'el-icon-cogs',
				'desc'	=>	__('This feature is affected in Submit Video functions at frontend','mars'),
				'fields'	=>	array(
                    array(
                        'id' => 'submit_permission',
                        'type' => 'switch',
                        'title' => __('Allow Guest submit the video.', 'mars'),
                        'subtitle' => __('By default, Only register can submit the video, you can limit the role in below selectbox', 'mars'),
                        "default" => 0,
                        'on' => __('Yes','mars'),
                        'off' => __('No','mars'),
                    ),
                    array(
                        'id' => 'video-type',
                        'type' => 'select',
                    	'multi' => true,
                        'title' => __('Video Type', 'redux-framework-demo'),
                        'subtitle' => __('Choose the Video Type, which is available in Submit Form at Frontend.', 'mars'),
                        'options' => array('videolink' => __('Video Link','mars'), 'embedcode' => __('Embed Code','mars'), 'videofile' => __('Video File','mars')), //Must provide key => value pairs for select options
                        'default' => 'videolink'
                    ),   
					array(
						'id'	=>	'videosize',
						'title'	=>	__('Video File Size','mars'),
						'type'	=>	'text',
						'desc'	=>	__('The maximum video file size allowed, 10MB is default size, -1 is no limit.','mars'),
						'default'	=>	10
					),
					array(
						'id'	=>	'imagesize',
						'title'	=>	__('Preview Image Size','mars'),
						'type'	=>	'text',
						'desc'	=>	__('The maximum Preview Image size allowed, 10MB is default size, -1 is no limit.','mars'),
						'default'	=>	2
					),					
                    array(
                        'id' => 'submit_redirect_to',
                        'type' => 'select',
                        'data' => 'pages',
                        'title' => __('Redirect to', 'mars'),
                        'desc' => __('Redirect the user to this page when submit the video sucessfully.', 'mars'),
                    ),                    
                    array(
                        'id' => 'submit_assigned_user',
                        'type' => 'select',
                        'title' => __('Assigned User', 'mars'),
                        'subtitle' => __('The video will be assigned for this user if you allow Guest Submit The Video.', 'mars'),
                        'options' => $user_db,
                        'default' => '1'
                    ),                    
                    array(
                        'id' => 'submit_roles',
                        'type' => 'select',
                    	'multi' => true,
                        'data' => 'roles',
                        'title' => __('Who can submit the video?', 'mars'),
                        'desc' => __('You can choose one or multi-roles to limit the permission.', 'mars'),
                    ),
                    array(
                        'id' => 'submit_status',
                        'type' => 'button_set',
                        'title' => __('Default Video Status', 'mars'),
                        'subtitle' => __('The Public status will be shown on Frontend.', 'mars'),
                        'options' => array('publish' => __('Publish','mars'), 'pending' => __('Pending','mars'), 'draft' => __('draft','mars')),
                        'default' => 'pending'
                    ),
                    array(
                        'id' => 'submit_editor',
                        'type' => 'switch',
                        'title' => __('Use WP Visual Editor', 'mars'),
                        "default" => 0,
                        'on' => __('Yes','mars'),
                        'off' => __('No','mars'),
                    ), 
                    array(
                        'id' => 'submit_captcha',
                        'type' => 'switch',
                        'title' => __('Enable Recaptcha', 'mars'),
                    	'desc'	=>	__('Make sure you have entered the valid Public Key and Private Key in <strong>Recaptcha</strong> section.','mars'),
                        "default" => 0,
                        'on' => __('Yes','mars'),
                        'off' => __('No','mars'),
                    ),
                    array(
                        'id' => 'videolayout',
                        'type' => 'button_set',
                        'title'	=>	__('Show Video Layout option','mars'),
                        'options' => array('yes' => __('Yes','mars'), 'no' => __('No','mars')),
                        'default' => 'yes'
                    ),                    
				)
			);
			
			$this->sections[] = array(
				'title'	=>	__('Subscribe','mars'),
				'icon'	=>	'el-icon-cogs',
				'fields'	=>	array(
                    array(
                        'id' => 'enable-subscribe',
                        'type' => 'switch',
                        'title' => __('Enable Subscribe Box.', 'mars'),
                    	'desc'	=>	__('Which is affected in Subscribe Box widget.','mars'),
                        "default" => 1,
                        'on' => __('Yes','mars'),
                        'off' => __('No','mars'),
                    ),
                    array(
                        'id' => 'private-policy-id',
                        'type' => 'select',
                        'data' => 'pages',
                        'title' => __('Privacy Policy Page', 'mars')
                    ), 
                    array(
                        'id' => 'subscribe_roles',
                        'type' => 'select',
                        'data' => 'roles',
                        'title' => __('Subscriber Role', 'mars'),
                    	'desc'	=>	__('<strong>subscriber</strong> role is using by default.','mars')
                    ),
				)
			);
        }
        public function setHelpTabs() {

            // Custom page help tabs, displayed using the help API. Tabs are shown in order of definition.
            $this->args['help_tabs'][] = array(
                'id' => 'redux-opts-1',
                'title' => __('Theme Information 1', 'redux-framework-demo'),
                'content' => __('<p>This is the tab content, HTML is allowed.</p>', 'redux-framework-demo')
            );

            $this->args['help_tabs'][] = array(
                'id' => 'redux-opts-2',
                'title' => __('Theme Information 2', 'redux-framework-demo'),
                'content' => __('<p>This is the tab content, HTML is allowed.</p>', 'redux-framework-demo')
            );

            // Set the help sidebar
            $this->args['help_sidebar'] = __('<p>This is the sidebar content, HTML is allowed.</p>', 'redux-framework-demo');
        }

        /**

          All the possible arguments for Redux.
          For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments

         * */
        public function setArguments() {
            $theme = wp_get_theme(); // For use with some settings. Not necessary.
            $this->args = array(
                // TYPICAL -> Change these values as you need/desire
                'opt_name' => 'videotube', // This is where your data is stored in the database and also becomes your global variable name.
                'display_name' => $theme->get('Name'), // Name that appears at the top of your panel
                'display_version' => $theme->get('Version'), // Version that appears at the top of your panel
                'menu_type' => 'submenu', //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
                'allow_sub_menu' => true, // Show the sections below the admin menu item or not
                'menu_title' => __('Theme Options', 'mars'),
                'page' => __('Theme Options', 'mars'),
                // You will need to generate a Google API key to use this feature.
                // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
                'google_api_key' => $this->google_api_key, // Must be defined to add google fonts to the typography module
                //'admin_bar' => false, // Show the panel pages on the admin bar
                'global_variable' => '', // Set a different name for your global variable other than the opt_name
                'dev_mode' => false, // Show the time the page took to load, etc
                'customizer' => true, // Enable basic customizer support
                // OPTIONAL -> Give you extra features
                'page_priority' => null, // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
                'page_parent' => 'themes.php', // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
                'page_permissions' => 'manage_options', // Permissions needed to access the options panel.
                'menu_icon' => '', // Specify a custom URL to an icon
                'last_tab' => '', // Force your panel to always open to a specific tab (by id)
                'page_icon' => 'icon-themes', // Icon displayed in the admin panel next to your menu_title
                'page_slug' => '_options', // Page slug used to denote the panel
                'save_defaults' => true, // On load save the defaults to DB before user clicks save or not
                'default_show' => false, // If true, shows the default value next to each field that is not the default value.
                'default_mark' => '', // What to print by the field's title if the value shown is default. Suggested: *
                // CAREFUL -> These options are for advanced use only
                'transient_time' => 60 * MINUTE_IN_SECONDS,
                'output' => true, // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
                'output_tag' => true, // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
                //'domain'             	=> 'redux-framework', // Translation domain key. Don't change this unless you want to retranslate all of Redux.
                //'footer_credit'      	=> '', // Disable the footer credit of Redux. Please leave if you can help it.
                // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
                'database' => '', // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
                'show_import_export' => true, // REMOVE
                'system_info' => false, // REMOVE
                'help_tabs' => array(),
                'help_sidebar' => '', // __( '', $this->args['domain'] );            
            );
            $this->args['share_icons'][] = array(
                'url' => 'https://twitter.com/wpoffice',
                'title' => 'Follow us on Twitter',
                'icon' => 'el-icon-twitter'
            );

            // Panel Intro text -> before the form
            if (!isset($this->args['global_variable']) || $this->args['global_variable'] !== false) {
                if (!empty($this->args['global_variable'])) {
                    $v = $this->args['global_variable'];
                } else {
                    $v = str_replace("-", "_", $this->args['opt_name']);
                }
                $this->args['intro_text'] = sprintf(__('<p>Did you know that Redux sets a global variable for you? To access any of your saved options from within your code you can use your global variable: <strong>$%1$s</strong></p>', 'redux-framework-demo'), $v);
            } else {
                $this->args['intro_text'] = __('<p>This text is displayed above the options panel. It isn\'t required, but more info is always better! The intro_text field accepts all HTML.</p>', 'redux-framework-demo');
            }

            // Add content after the form.
            //$this->args['footer_text'] = __('<p>This text is displayed below the options panel. It isn\'t required, but more info is always better! The footer_text field accepts all HTML.</p>', 'redux-framework-demo');
        }

    }

    new Redux_Framework_config();
}