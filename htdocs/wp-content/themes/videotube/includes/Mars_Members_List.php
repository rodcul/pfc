<?php
if( !defined('ABSPATH') ) exit;
if( !class_exists( 'Mars_Members_List' ) ){
	class Mars_Members_List {
		function __construct() {
			add_action('init', array( $this, 'add_shortcode' ));
		}
		function add_shortcode(){
			add_shortcode('videotube_members', array( $this, 'members' ));
		}
		function members( $attr, $content = null ){
			return '<div id="vt-members"><img src="'.MARS_THEME_URI.'/img/ajax-loader.gif"></div>';
		}
		function query_members(){
			global $wpdb;
			$show = isset( $attr['show'] ) ? (int)$attr['show'] : 20;
			$orderby = isset( $attr['orderby'] ) ? trim( $attr['orderby'] ) : 'video';
			$order = isset( $attr['order'] ) ? trim( $attr['order'] ) : 'DESC';
			$user_array = array(
				'number'	=>	$show,
				'orderby'	=>	'post_count',
				'order' => 'DESC'
			);
			$users = get_users($user_array);
			if( !empty( $users ) ){
				foreach ( $users as $user ){
					$user_data = get_user_by('id', $user->ID);
					$content .= '
	                    <div class="channel-header">
							
							<div class="channel-image"><a href="'.get_author_posts_url( $user->ID ).'">'.get_avatar($user->ID).'</a></div>
							
							<div class="channel-info">
								<h3>'.$user_data->display_name.'</h3>
								
								<span class="channel-item"><strong>'.__('Videos:','mars').'</strong> '.mars_get_user_postcount($user->ID).'</span>
								<span class="channel-item"><strong>'.__('Likes:','mars').'</strong> '.mars_get_user_metacount($user->ID, 'like_key').'</span>
								<span class="channel-item"><strong>'.__('Views:','mars').'</strong> '.mars_get_user_metacount($user->ID, 'count_viewed').'</span>
							</div>
						</div>
					';
				}
			}
			return $content;
		}	
	}
	new Mars_Members_List();
}