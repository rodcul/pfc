<?php
/**
 * VideoTube List Videos Shortcode
 * @author 		Toan Nguyen
 * @category 	Core
 * @version     1.0.0
 */
if( !defined('ABSPATH') ) exit;

if( !class_exists('Mars_ShortcodeListVideos') ){
	class Mars_ShortcodeListVideos {
		var $post_type	=	'video';
		var $post_status	=	'publish';
		function __construct() {
			add_action('init', array($this,'add_shortcode'));
		}
		function add_shortcode(){
			add_shortcode('videotube', array($this,'videotube'));
		}
		/**
		 * Display the video, filted by the condition
		 * @param array $attr
		 * @param string $content
		 */
		function videotube( $attr, $content ) {
			$html = null;
			//wp_reset_postdata();wp_reset_query();
			$videos_query = null;
			$title = isset( $attr['title'] ) ? trim( $attr['title'] ) : null;
			$cat = isset( $attr['cat'] ) ? explode(',', $attr['cat'] )  : null;
			$tag = isset( $attr['tag'] ) ? explode(',', $attr['tag'] )  : null;
			$orderby = isset( $attr['orderby'] ) ? trim( $attr['orderby'] ) : 'ID';
			$order = isset( $attr['order'] ) ? trim( $attr['order'] ) : 'DESC';
			$show = isset( $attr['show'] ) ? (int)$attr['show'] : get_option('posts_per_page');
			$ids = isset( $attr['ids'] ) ? explode(',', $attr['ids'] )  : null;
			$author__in = isset( $attr['author'] ) ? explode(',', $attr['author'] )  : null;

			$videos_query = array(
				'post_type'	=>	$this->post_type,
				'posts_per_page'	=>	$show,
				'showposts'	=>	$show,
				'post_status'	=>	$this->post_status,
				'order'	=>	$order
			);
			### categories
			if( $cat ){
				$videos_query['tax_query'] = array(
					array(
						'taxonomy' => 'categories',
						'field' => 'id',
						'terms' => $cat
					)
				);
			}
			### video_tag
			if( $tag ){
				$videos_query['tax_query'] = array(
					array(
						'taxonomy' => 'video_tag',
						'field' => 'id',
						'terms' => $tag
					)
				);
			}
			if( $author__in ){
				$videos_query['author__in'] = $author__in;
			}
			if( $orderby == 'views' ){
				$videos_query['meta_key'] = 'count_viewed';
				$videos_query['orderby']	=	'meta_value_num';
			}
			elseif( $orderby == 'likes' ){
				$videos_query['meta_key'] = 'like_key';
				$videos_query['orderby']	=	'meta_value_num';				
			}			
			else{
				$videos_query['orderby'] = $orderby;	
			}			
						
			### Custom Video ID
			if( $ids && is_array( $ids ) ){
				unset( $videos_query['tax_query'] );
				unset( $videos_query['author__in'] );
				$videos_query['post__in']	=	explode(",", $ids);
			}
			if( $title ){
				$html .= '
	            	<div class="section-header">
	                    <h3>'.$title.'</h3>
	                </div>				
				';
			}
			$wp_query = new WP_Query( $videos_query );
			if( $wp_query->have_posts() ):
				$html .= '<div class="row video-section meta-maxwidth-230">';
					while ( $wp_query->have_posts() ): $wp_query->the_post();
						$html .= '
							<div class="col-sm-4 col-xs-6 item"><div class="item-img">';
						if(has_post_thumbnail()){
							$html .= '<a title="'.get_the_title().'" href="'.get_permalink( get_the_ID() ).'">'. get_the_post_thumbnail(null,'video-featured', array('class'=>'img-responsive')) .'</a>';
						}
						$html .= '<a href="'.get_permalink( get_the_ID() ).'"><div class="img-hover"></div></a></div>';
						$html .= '<h3><a href="'.get_permalink( get_the_ID() ).'">'.get_the_title( get_the_ID() ).'</a></h3>';
						$html .= apply_filters('mars_video_meta', null);
						$html .= '</div>';
					endwhile;
				$html .= '</div>';
				/**
				if( function_exists('page_navi') ):
					$html .= page_navi(array('elm_class'=>'pagination','current_class'=>'active','current_format'=>'<a>%d</a>'));
				endif;
				**/
			else:
				$html .= '<div class="alert alert-info">'.__('Oop...nothing.','mars').'</div>';
			endif;
			wp_reset_postdata();wp_reset_query();
			return $html;
		}
	}
	new Mars_ShortcodeListVideos();
}