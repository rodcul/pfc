<?php
/**
 * VideoTube Common Functions
 *
 * @author 		Toan Nguyen
 * @category 	Core
 * @version     1.0.0
 */
if( !defined('ABSPATH') ) exit;
if( !function_exists('get_google_apikey') ){
	function get_google_apikey(){
		global $videotube;
		$google_apikey = isset( $videotube['google-api-key'] ) ? trim( $videotube['google-api-key'] ) : null;
		return $google_apikey;
	}
}
if( !function_exists('mars_get_user_role') ){
	function mars_get_user_role( $user_id ) {
		if( !$user_id ){
			return;
		}
		$user = new WP_User( $user_id );
		if( isset( $user->roles[0] ) ){
			return $user->roles[0];	
		}
		else{
			return null;
		}
	}
}
if( !function_exists('mars_get_post_data') ){
	function mars_get_post_data( $post_id ) {
		return get_post( $post_id );
	}
}
if( !function_exists('mars_socials_url') ){
	function mars_socials_url() {
		return array(
			'facebook'	=>	__('Facebook','mars'),
			'twitter'	=>	__('twitter','mars'),
			'google-plus'	=>	__('Google Plus','mars'),
			'instagram'	=>	__('Instagram','mars'),
			'linkedin'	=>	__('Linkedin','mars'),
			'tumblr'	=>	__('Tumblr','mars')
		);
	}
}
add_filter( 'wp_title', 'mars_wp_title' );

if( !function_exists('post_orderby_options') ){
	function post_orderby_options( $post_type='post' ) {
		$orderby = array(
			'ID'	=>	__('Order by Post ID','mars'),
			'author'	=>	__('Order by author','mars'),
			'title'	=>	__('Order by title','mars'),
			'name'	=>	__('Order by Post name (Post slug)','mars'),
			'date'	=>	__('Order by date','mars'),
			'modified'	=>	__('Order by last modified date','mars'),
			'rand'	=>	__('Order by Random','mars'),
			'comment_count'	=>	__('Order by number of comments','mars')	
		);
		if( $post_type == 'video' ){
			$orderby['views']	=	__('Order by Views','mars');
			$orderby['likes']	=	__('Order by Likes','mars');
		}
		return $orderby;
	}
}
if( !function_exists('mars_wp_title') ){
	function mars_wp_title( $title ){
	  if( empty( $title ) && ( is_home() || is_front_page() ) ) {
	    return get_bloginfo('name') . ' | ' . get_bloginfo( 'description' );
	  }
	  return $title;
	}	
}

if( !function_exists('mars_theme_comment_style') ){
	function mars_theme_comment_style( $comment, $args, $depth ){
		error_reporting(0);
		$GLOBALS['comment'] = $comment;
		extract($args, EXTR_SKIP);
		?>
		<li class="comment">
			<div class="the-comment">
				<?php if ( '0' == $comment->comment_approved ) : ?>
					<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'mars' ); ?></p>
				<?php endif; ?>			
				<div class="avatar"><?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?></div>
				<div class="comment-content">
					<span class="author"><?php print $comment->comment_author;?> <small><?php print human_time_diff( get_comment_time('U'), current_time('timestamp') ) . ' ago';?></small></span>
					<?php comment_text() ?>
					<?php comment_reply_link(array_merge( $args, array('add_below' => null, 'depth' => $depth, 'max_depth' => $args['max_depth'],'reply_text'=>'<i class="fa fa-reply"></i> '.__('Reply','mars')))) ?>
					<?php if( current_user_can('add_users') ):?>
					<a href="<?php print get_edit_comment_link( $comment->comment_ID );?>" class="edit"><i class="fa fa-edit"></i> <?php _e('Edit','mars');?></a>
					<?php endif;?>
				</div>
			</div>		
		<?php 
	}
}

function mars_replace_reply_link_class($class){
    $class = str_replace("class='comment-reply-link", "class='reply", $class);
    return $class;
}
add_filter('comment_reply_link', 'mars_replace_reply_link_class');
 if( !function_exists('mars_get_post_authorID') ){
 	function mars_get_post_authorID($post_id) {
 		if( !$post_id )
 			return false;
 		$post = get_post($post_id);
 		return $post->post_author;
 	}
 }
if( !function_exists('mars_wp_nav_menu_args') ){
	function mars_wp_nav_menu_args( $args = '' ) {
		$args['container'] = false;
		return $args;
	}
	add_filter( 'wp_nav_menu_args', 'mars_wp_nav_menu_args' );	
}
if( !class_exists('Mars_Walker_Nav_Menu') ){
	class Mars_Walker_Nav_Menu extends Walker_Nav_Menu {
	   function start_lvl(&$output, $depth = 0, $args = array()) {
	      $output .= "\n<ul class=\"dropdown-menu\">\n";
	   }
	   function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
	       $item_html = '';
	       parent::start_el($item_html, $item, $depth, $args);
	
	       if ( $item->is_dropdown && $depth === 0 ) {
	           //$item_html = str_replace( '<a', '<a class="dropdown-toggle" data-toggle="dropdown"', $item_html );
	           $item_html = str_replace( '<a', '<a', $item_html );
	           $item_html = str_replace( '</a>', ' <b class="caret"></b></a>', $item_html );
	       }
	
	       $output .= $item_html;
	    }
	    function display_element($element, &$children_elements, $max_depth, $depth = 0, $args, &$output) {
	        if ( $element->current )
	        $element->classes[] = 'active';
	
	        $element->is_dropdown = !empty( $children_elements[$element->ID] );
	
	        if ( $element->is_dropdown ) {
	            if ( $depth === 0 ) {
	                $element->classes[] = 'dropdown';
	            } elseif ( $depth === 1 ) {
	                // Extra level of dropdown menu, 
	                // as seen in http://twitter.github.com/bootstrap/components.html#dropdowns
	                $element->classes[] = 'dropdown-submenu';
	            }
	        }
	    	parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
	    }
	}	
}
if( !function_exists('mars_add_parent_css') ){
	function mars_add_parent_css($classes, $item){
	     global  $dd_depth, $dd_children;
	     $classes[] = 'depth'.$dd_depth;
	     if($dd_children)
	         $classes[] = 'dropdown';
	    return $classes;
	}
	add_filter('nav_menu_css_class','mars_add_parent_css',10,2);	
}
if( !function_exists('mars_resave_real_video') ){
	function mars_resave_real_video($post_id) {
		if( get_post_type($post_id) =='video' ){
			$video_url = get_post_meta($post_id,'video_url',true);
			if( trim( $video_url ) != '' ){
				$video_data = mars_get_remote_videoid($video_url);
				if( !empty( $video_data ) ){
					foreach ( $video_data as $key=>$value ){
						update_post_meta($post_id, 'real_video_'. $key, $value);
					}
				}
			}
		}
		return $post_id;
	}
	//add_action('save_post', 'mars_resave_real_video', 100,1);
	//add_action('mars_save_post', 'mars_resave_real_video', 110, 1);
}
if( !function_exists('mars_get_remote_videoid') ){
	function mars_get_remote_videoid($url) {
		$video_id = NULL;
		if( !$url )
			return;
		if( !function_exists('parse_url') )
			return;
		$string = parse_url($url);
		$host = $string['host'];
		
		if( $host == 'vimeo.com' || $host =='www.vimeo.com' ){
			$video_id = substr($string['path'], 1);
		}
		if( $host == 'youtube.com' || $host =='www.youtube.com' ){
			parse_str( parse_url( $url, PHP_URL_QUERY ), $array_of_vars );
			$video_id = $array_of_vars['v'];			
		}
		return array(
			'id'	=>	$video_id,
			'source'	=>	$host
		);
	}
}
function modify_youtube_embed_url($html, $url, $args) {
	if (strpos($url, 'youtube')!==FALSE) {
		return str_replace("?feature=oembed", "?feature=oembed&autoplay=1", $html);
	}
	return $html;
}
add_filter('oembed_result', 'modify_youtube_embed_url', 10, 3);

if( !function_exists('videoframe') ){
	function videoframe() {
		global $post,$videotube;
		$frame = null;
		$settings = array();
		$layout = get_post_meta($post->ID,'layout',true) ? get_post_meta($post->ID,'layout',true) : 'small';
		if( $layout == 'large' ){
			### Fullwidth Video
			$settings = array(
				'width'	=>	1140,
				'height'	=>	641,
			);
		}
		else{
			### Right sidebar Video
			$settings = array(
				'width'	=>	750,
				'height'	=>	442
			);
		}
		$settings['autoplay'] = "true";
		$video_url = get_post_meta($post->ID,'video_url',true);
		if( $video_url ){
			if( function_exists( 'wp_oembed_get' ) ){
				$frame .= wp_oembed_get($video_url, $settings);
			}
		}
		elseif( get_post_meta($post->ID,'video_frame',true) !='' ){
			### The Frame.
			$frame .= get_post_meta($post->ID,'video_frame',true);
		}
		elseif( get_post_meta($post->ID,'video_file',true) !='' ){
			$autoplay = ( $videotube['autoplay'] == 1 ) ? 'autoplay="on"' : null;
			$video_file_url = wp_get_attachment_url( get_post_meta($post->ID,'video_file',true) );
			$frame .= '[video '.$autoplay.' '.$settings.' src="'.$video_file_url.'"][/video]';
		}
		return $frame;
	}
	add_filter('videoframe', 'videoframe', 10);
}

if( !function_exists('mars_orderblock_videos') ){
	function mars_orderblock_videos() {
		$order = isset( $_REQUEST['order'] ) ?  trim($_REQUEST['order']) : null;
		$sort_array = array(
			'latest'	=>	__('Latest','mars'),
			'viewed'	=>	__('Viewed','mars'),
			'liked'	=>	__('Liked','mars'),
			'comments'	=>	__('Comments','mars')
		);
		$block = '<div class="section-nav"><ul class="sorting"><li class="sort-text">'.__('Sort by:','mars').'</li>';
			foreach ( $sort_array as $key=>$value ){
				$active = ( $order == $key ) ? 'class="active"' : null;
				$block .= '<li '.$active.'><a href="?order='.$key.'">'.$value.'</a></li>';
			}
		$block .= '</ul></div>';
		print $block;
	}
	add_action('mars_orderblock_videos', 'mars_orderblock_videos');
}

if( !function_exists('mars_orderquery_videos') ){
	function mars_orderquery_videos( $query ) {
		$order = isset( $_REQUEST['order'] ) ? trim( $_REQUEST['order'] ) : null;
		if( is_tax() || is_archive() && !is_admin() ){
			if( $query->is_main_query() ){
				switch ( $order ) {
					case 'comments':
						$query->set( 'orderby', 'comment_count' );
					break;
					case 'viewed':
						$query->set( 'meta_key','count_viewed' );
						$query->set( 'orderby', 'meta_value_num' );
					break;
					case 'liked':
						$query->set( 'meta_key','like_key' );
						$query->set( 'orderby', 'meta_value_num' );
					break;
				}
			}
			$query->set( 'order', 'DESC' );
		}
		return $query;
	}
	add_filter('pre_get_posts', 'mars_orderquery_videos');
}
if( !function_exists('mars_seo_limit_leng_titlewords') ){
	function mars_seo_limit_leng_titlewords( $string, $length = 4 ) {
		$subtring = null;
		if( function_exists( 'mb_strcut' ) ){
			$subtring = mb_strcut($string, 0, $length);	
		}
		else{
			$subtring = $string;
		}
		if( strlen( $string ) > $length ){
			$subtring = $subtring . '...';
		}
		return $subtring;
	}
}

if( !function_exists('mars_limit_leng_titlewords') ){
	function mars_limit_leng_titlewords( $the_title, $length = 20 ) {
		global $post;
		if( !is_admin() ){
			if( get_post_type($post) == 'video' ){
				//return wp_trim_words( $the_title, $length, '...' );
				$subtring = mb_strcut($the_title, 0, $length);
				if( strlen( $the_title ) > $length ){
					$subtring = $subtring . '...';
				}
				return $subtring;				
			}
		}
		return $the_title;
	}
	//add_filter( 'the_title', 'mars_limit_leng_titlewords', 100,1 );
}
add_filter ( 'wp_tag_cloud', 'mars_tag_cloud_font_size_class' );  
if( !function_exists('mars_tag_cloud_font_size_class') ){
	function mars_tag_cloud_font_size_class( $taglinks ) { 
	    $tags = explode('</a>', $taglinks);
	    $regex1 = "#(.*style='font-size:)(.*)((pt|px|em|pc|%);'.*)#e"; 
	    $regex2 = "#(style='font-size:)(.*)((pt|px|em|pc|%);')#e";         
	    $regex3 = "#(.*class=')(.*)(' title.*)#e";         
	    foreach( $tags as $tag ) {         
	        $size = preg_replace($regex1, "(''.round($2).'')", $tag ); //get the rounded font size       
	        $tag = preg_replace($regex2, "('')", $tag ); //remove the inline font-size style
	        $tag = preg_replace($regex3, "('$1tag $2$3')", $tag ); //add .tag-size-{nr} class
	        $tagn[] = $tag;
	    }     
	    $taglinks = implode('</a>', $tagn);     
		return $taglinks; 
	}	
}
//---------------------------------------- Count viewed ------------------------------------------

if( !function_exists('mars_get_count_viewed') ){
	function mars_get_count_viewed() {
		global $post;
		return get_post_meta($post->ID,'count_viewed',true) ? get_post_meta($post->ID,'count_viewed',true) : 1;		
	}
}
if( !function_exists('mars_add_viewed') ){
	function mars_add_viewed() {
	    if(!isset($_SESSION)){ session_start();} 
		global $post;
		if( is_single() ){
			if( isset( $_SESSION['count_viewed'] ) ){
				if( in_array( $post->ID, $_SESSION['count_viewed'] ) ){
					return;
				}
			}
			$current_viewed = mars_get_count_viewed();
			update_post_meta($post->ID, 'count_viewed', $current_viewed + 1);
			$_SESSION['count_viewed'][] = $post->ID;			
		}
	}
	add_action('wp', 'mars_add_viewed');
}
if( !function_exists('mars_add_1firstlike') ){
	function mars_add_1firstlike( $post_id ) {
		if( get_post_type( $post_id ) =='video' ){
			$likes = mars_get_like_count( $post_id );
			if( $likes == 0 || !$likes ){
				update_post_meta($post_id, 'like_key', 1);	
			}
		}
	}
	add_action('save_post', 'mars_add_1firstlike', 9999, 1);
}
//---------------------------------------- like and dislike button ------------------------------------------
if( !function_exists('mars_get_like_count') ){
	function mars_get_like_count($post_id) {
		return get_post_meta($post_id, 'like_key',true) ? get_post_meta($post_id, 'like_key',true)  : 0;
	}	
}
if( !function_exists('mars_get_dislike_count') ){
	function mars_get_dislike_count($post_id) {
		return get_post_meta($post_id, 'dislike_key',true) ? get_post_meta($post_id, 'dislike_key',true)  : 0;
	}	
}
//---------------------------------------- like and dislike button ------------------------------------------


if( !function_exists('mars_get_current_postterm') ){
	function mars_get_current_postterm($post_id, $taxonomy){
		$terms = wp_get_post_terms($post_id,$taxonomy, array("fields" => "ids"));
		return $terms;
	}
}

if( !function_exists('mars_get_socials_count') ){
	function mars_get_socials_count($key) {
		$count = 0;
		switch ($key) {
			case 'facebook':
				if( function_exists('get_scp_facebook') ){
					$count = get_scp_facebook();
				}
			break;
			case 'twitter':
				if( function_exists('get_scp_twitter') ){
					$count = get_scp_twitter();
				}
			break;
			case 'googleplus':
				if( function_exists('get_scp_googleplus') ){
					$count = get_scp_googleplus();
				}
			break;
			case 'subscriber':
				$result = count_users();
				$count = isset( $result['avail_roles'][$key] ) ? $result['avail_roles'][$key] : 0;
			break;
		}
		return !empty( $count ) ? $count : 0;
	}
}
if(!function_exists('mars_get_editor')){
	function mars_get_editor($content, $id, $name, $display_media = false) {
		ob_start();
		$settings = array(
			'textarea_name' => $name,
			'media_buttons' => $display_media,
			'textarea_rows'	=>	5,
			'quicktags'	=>	false
		);
		// Echo the editor to the buffer
		wp_editor($content,$id, $settings);
		// Store the contents of the buffer in a variable
		$editor_contents = ob_get_clean();
		$editor_contents = str_ireplace("<br>","", $editor_contents);
		// Return the content you want to the calling function
		return $editor_contents;
	}
}
if( !function_exists('mars_render_custom_css') ){
	function mars_render_custom_css() {
		global $videotube;
		$css = NULL;
		if( isset( $videotube['custom_css'] ) && trim( $videotube['custom_css'] ) != '' ){
			$css = '<style>'.$videotube['custom_css'].'</style>';
		}
		print $css;
	}
	add_action('wp_head', 'mars_render_custom_css');
}
if( !function_exists('mars_render_custom_js') ){
	function mars_render_custom_js() {
		global $videotube;
		$js = NULL;
		if( isset( $videotube['custom_js'] ) && trim( $videotube['custom_js'] ) != '' ){
			$js .= '<script>jQuery(document).ready(function(){'.$videotube['custom_js'].'});</script>';
		}
		print $js;
	}
	add_action('wp_footer', 'mars_render_custom_js');
}
if( !function_exists('mars_add_google_analytics') ){
	function mars_add_google_analytics() {
		global $videotube;
		if( isset( $videotube['google-analytics'] ) && trim( $videotube['google-analytics'] ) != '' ){
			$code = trim( $videotube['google-analytics'] );
			print '
				<script type="text/javascript">
				var _gaq = _gaq || [];
				_gaq.push([\'_setAccount\', \''.$code.'\']);
				_gaq.push([\'_trackPageview\']);
				(function() {
				var ga = document.createElement(\'script\'); ga.type = \'text/javascript\'; ga.async = true;
				ga.src = (\'https:\' == document.location.protocol ? \'https://ssl\' : \'http://www\') + \'.google-analytics.com/ga.js\';
				var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(ga, s);
				})();
				</script>			
			';
		}
	}
	add_action('wp_footer', 'mars_add_google_analytics');
}
if( !function_exists('mars_show_favicon') ){
	function mars_show_favicon() {
		global $videotube;
		if( isset( $videotube['favicon']['url'] ) && trim( $videotube['favicon']['url'] ) !='' ){
			print '<link rel="shortcut icon" href="'.$videotube['favicon']['url'].'">';
		}
	}
	add_action('wp_head', 'mars_show_favicon');
}

if( !function_exists('mars_special_nav_class') ){
	function mars_special_nav_class($classes, $item){
	     if( in_array('current-menu-item', $classes) ){
	     	$classes[] = 'active ';
	     }
	     return $classes;
	}	
}
add_filter('nav_menu_css_class' , 'mars_special_nav_class' , 10 , 2);

if( !function_exists( 'mars_get_user_postcount' ) ){
	function mars_get_user_postcount( $user_id, $post_type="video" ) {
		global $wpdb;
		$where = get_posts_by_author_sql( $post_type, true, $user_id );
		$count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->posts $where" );
		return $count;
	}
}
if( !function_exists( 'mars_get_user_metacount' ) ){
	function mars_get_user_metacount( $user_id, $key ) {
		global $wpdb;
		$query = $wpdb->get_var( $wpdb->prepare( 
			"
				SELECT sum(meta_value) 
				FROM $wpdb->postmeta LEFT JOIN $wpdb->posts ON ( $wpdb->postmeta.post_id = $wpdb->posts.ID )
				LEFT JOIN $wpdb->users ON ( $wpdb->posts.post_author = $wpdb->users.ID )
				WHERE meta_key = %s
				AND $wpdb->users.ID = %s
				AND $wpdb->posts.post_status = %s
				AND $wpdb->posts.post_type = %s
			", 
			$key,
			$user_id,
			'publish',
			'video'
		) );
		return (int)$query > 0 ? number_format_i18n($query) : 0; 
	}
}
if( !function_exists( 'mars_viaudiofile_format' ) ){
	function mars_viaudiofile_format() {
		return array(
			// Video formats
			'asf|asx'                      => 'video/x-ms-asf',
			'wmv'                          => 'video/x-ms-wmv',
			'wmx'                          => 'video/x-ms-wmx',
			'wm'                           => 'video/x-ms-wm',
			'avi'                          => 'video/avi',
			'divx'                         => 'video/divx',
			'flv'                          => 'video/x-flv',
			'mov|qt'                       => 'video/quicktime',
			'mpeg|mpg|mpe'                 => 'video/mpeg',
			'mp4|m4v'                      => 'video/mp4',
			'ogv'                          => 'video/ogg',
			'webm'                         => 'video/webm',
			'mkv'                          => 'video/x-matroska',
			// Audio formats
			'mp3|m4a|m4b'                  => 'audio/mpeg',
			'ra|ram'                       => 'audio/x-realaudio',
			'wav'                          => 'audio/wav',
			'ogg|oga'                      => 'audio/ogg',
			'mid|midi'                     => 'audio/midi',
			'wma'                          => 'audio/x-ms-wma',
			'wax'                          => 'audio/x-ms-wax',
			'mka'                          => 'audio/x-matroska'
		);
	}
}
if( !function_exists( 'mars_imagefile_format' ) ){
	function mars_imagefile_format() {
		return array(
			// Image formats
			'jpg|jpeg|jpe'                 => 'image/jpeg',
			'gif'                          => 'image/gif',
			'png'                          => 'image/png'
		);
	}
}
if( !function_exists( 'mars_check_file_allowed' ) ){
	function mars_check_file_allowed( $file, $type = 'video' ){
		$bool = false;
		if( $type == 'video' ){
			$mimes = mars_viaudiofile_format();
		}
		else{
			$mimes = mars_imagefile_format();
		}
		$filetype = wp_check_filetype($file['name'], null);
		//echo $filetype['ext'];
		foreach ($mimes as $type => $mime){
			if (strpos($type, $filetype['ext']) !== false){
				$bool = true;
			}
		}
		return $bool;
	}
}
if( !function_exists( 'mars_check_file_size_allowed' ) ){
	function mars_check_file_size_allowed( $file, $type = 'video' ){
		global $videotube;
		if( !$file )
			return false;
		if( $type == 'video' ){
			$filesize = isset( $videotube['videosize'] ) ? (int)$videotube['videosize'] : 10;	
		}
		else{
			$filesize = isset( $videotube['imagesize'] ) ? (int)$videotube['imagesize'] : 2;
		}
		if( $filesize == -1 ){
			return true;
		}
		$byte_limit = mars_convert_mb_to_b( $filesize );
		if( $file["size"] > $byte_limit ){
			return false;
		}
		return true;
	}
}

if( !function_exists( 'mars_convert_mb_to_b' ) ){
	function mars_convert_mb_to_b( $megabyte ) {
		if( !$megabyte || $megabyte == 0 )
			return 0;
		return (int)$megabyte * 1048576;
	}
}

if( !function_exists( 'mars_insert_attachment' ) ){
	function mars_insert_attachment($file_handler, $post_id, $setthumb='false', $post_meta = '') {
		// check to make sure its a successful upload
		if ($_FILES[$file_handler]['error'] !== UPLOAD_ERR_OK) __return_false();
	
		require_once(ABSPATH . "wp-admin" . '/includes/image.php');
		require_once(ABSPATH . "wp-admin" . '/includes/file.php');
		require_once(ABSPATH . "wp-admin" . '/includes/media.php');
	
		$attach_id = media_handle_upload( $file_handler, $post_id );
	
		if ($setthumb) update_post_meta($post_id,'_thumbnail_id',$attach_id);
		if(!$setthumb && $post_meta!=''){
			update_post_meta($post_id, $post_meta, $attach_id);
		}
		return $attach_id;
	}
}
if( !function_exists( 'mars_videolayout' ) ){
	function mars_videolayout() {
		return array( 
			'small'	=>	__('Small','mars'),
			'large'	=>	__('Large','mars')
		);
	}
}
if( !function_exists( 'bootstrap_link_pages' ) ){
	/**
	 * Link Pages
	 * @author toscha
	 * @link http://wordpress.stackexchange.com/questions/14406/how-to-style-current-page-number-wp-link-pages
	 * @param  array $args
	 * @return void
	 * Modification of wp_link_pages() with an extra element to highlight the current page.
	 */
	function bootstrap_link_pages( $args = array () ) {
	    $defaults = array(
	        'before'      => '<p>' . __('Pages:','mars'),
	        'after'       => '</p>',
	        'before_link' => '',
	        'after_link'  => '',
	        'current_before' => '',
	        'current_after' => '',
	        'link_before' => '',
	        'link_after'  => '',
	        'pagelink'    => '%',
	        'echo'        => 1
	    );
	 
	    $r = wp_parse_args( $args, $defaults );
	    $r = apply_filters( 'wp_link_pages_args', $r );
	    extract( $r, EXTR_SKIP );
	 
	    global $page, $numpages, $multipage, $more, $pagenow;
	 
	    if ( ! $multipage )
	    {
	        return;
	    }
	 
	    $output = $before;
	 
	    for ( $i = 1; $i < ( $numpages + 1 ); $i++ )
	    {
	        $j       = str_replace( '%', $i, $pagelink );
	        $output .= ' ';
	 
	        if ( $i != $page || ( ! $more && 1 == $page ) )
	        {
	            $output .= "{$before_link}" . _wp_link_page( $i ) . "{$link_before}{$j}{$link_after}</a>{$after_link}";
	        }
	        else
	        {
	            $output .= "{$current_before}{$link_before}<a>{$j}</a>{$link_after}{$current_after}";
	        }
	    }
	 
	    print $output . $after;
	}	
}