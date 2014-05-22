<?php
/**
 * VideoTube Optimization
 * Add the optimazation featured in Frontend.
 * @author 		Toan Nguyen
 * @category 	Core
 * @version     1.0.0
 */
if( !defined('ABSPATH') ) exit;
if( !class_exists('Mars_Optimization') ){
	class Mars_Optimization {
		function __construct() {
			global $videotube;
			if( $videotube['http_compression'] && !is_admin() ){
				add_action('init', array($this,'http_compression'));
			}
			if( $videotube['hide_admin_bar'] && !current_user_can('add_users') ){
				add_filter('show_admin_bar', '__return_false');
			}
			if( $videotube['enable_seo'] ){
				add_action('wp_head', array($this,'enable_seo'));
			}
		}
		function enable_seo(){
			global $post;
			global $wp_query;
			$post_datas = mars_get_post_data( $post->ID ); 
			$meta = NULL;
			$layout = get_post_meta($post->ID,'layout',true) ? get_post_meta($post->ID,'layout',true) : 'small';
			if( $layout == 'large' ){
				### Fullwidth Video
				$width = 1140;
				$height = 641;
			}
			else{
				### Right sidebar Video
				$width = 750;
				$height = 442;						
			}
			### meta title
			$meta .= '<meta name="title" content="'.get_wp_title_rss().'">';
			### meta description, keywords
			if( is_home() || is_front_page() || is_archive() || is_category() ){
				$meta .= '<meta name="description" content="'.get_bloginfo('description').'">';
				$meta .= '<meta name="keywords" content="'.get_bloginfo('name').'">';
			}
			elseif( get_post_type( $post->ID ) == 'video' ){
				$meta .= '<meta name="description" content="'.mars_seo_limit_leng_titlewords( wp_filter_nohtml_kses($post_datas->post_content, 30 )).'">';
				$meta .= '<meta name="keywords" content="'.$this->wp_get_post_terms_string($post->ID, 'video_tag').'">';
			}
			else{
				$meta .= '<meta name="description" content="'.mars_seo_limit_leng_titlewords( wp_filter_nohtml_kses( $post_datas->post_content, 30 )).'">';
				$meta .= '<meta name="keywords" content="'.$this->wp_get_post_terms_string($post->ID, 'post_tag').'">';				
			}
			

			### og tag.
			$meta .= '<meta property="og:site_name" content="'.get_wp_title_rss().'">';	
			if( is_home() && !is_front_page() ){
				$meta .= '<meta property="og:url" content="'.home_url().'">';
				$meta .= '<meta property="og:title" content="'.get_bloginfo('name').'">';
				$meta .= '<meta property="og:description" content="'.get_bloginfo('description').'">';
			}
			if( is_archive() ){
				$meta .= '<meta property="og:url" content="'.get_post_type_archive_link( get_post_type() ).'">';
				$meta .= '<meta property="og:title" content="'.$wp_query->get_queried_object()->name.'">';
				$meta .= '<meta property="og:description" content="'.$wp_query->get_queried_object()->name.'">';
			}
			if( is_tax() || is_category() || is_tag() ){
				$meta .= '<meta property="og:url" content="'.get_term_link($wp_query->get_queried_object()->term_id, $wp_query->get_queried_object()->taxonomy).'">';
				$meta .= '<meta property="og:title" content="'.$wp_query->get_queried_object()->name.'">';
				$meta .= '<meta property="og:description" content="'.$wp_query->get_queried_object()->name.'">';
			}
			if( is_single() || is_singular('video') || is_page() ){
				$meta .= '<meta property="og:url" content="'.get_permalink( $post->ID ).'">';
				$meta .= '<meta property="og:title" content="'.$post_datas->post_title.'">';
				if( is_singular('video') ){
					$meta .= '<meta property="og:type" content="video">';
				}
				if( has_post_thumbnail( $post->ID ) ){
					$thumb_id = get_post_thumbnail_id( $post->ID );
					$thumb_url = wp_get_attachment_image_src($thumb_id,'full', true);
					$meta .= '<meta property="og:image" content="'.$thumb_url[0].'">';
				}				
				$meta .= '<meta property="og:description" content="'.$post_datas->post_title.'">';
				if( get_post_type( $post->ID ) == 'video' ){
					$video_file_url = wp_get_attachment_url( get_post_meta($post->ID,'video_file',true) );
					if( $video_file_url ){
						$meta .= '<meta property="og:video" content="'. $video_file_url .'">';
					}
					else{
						$meta .= '<meta property="og:video" content="'. get_permalink( $post->ID ).'">';
					}
					$meta .= '
						<meta property="og:video:type" content="application/x-shockwave-flash">
						<meta property="og:video:width" content="'.$width.'">
						<meta property="og:video:height" content="'.$height.'">
					';
				}
			}
			print $meta;
		}
		function wp_get_post_terms_string( $post_id, $taxonomy ){
			$term_list = wp_get_post_terms($post_id, $taxonomy, array("fields" => "names"));
			return implode(",", $term_list);
		}
		function http_compression() {
			// Dont use on Admin HTML editor
			if (stripos($uri, '/js/tinymce') !== false) 
				return false;
				
			// Check if ob_gzhandler already loaded
			if (ini_get('output_handler') == 'ob_gzhandler')
				return false;
				
			// Load HTTP Compression if correct extension is loaded
			if (extension_loaded('zlib')) 
					if(!ob_start("ob_gzhandler")) ob_start();
		}	
	}
	new Mars_Optimization();
}