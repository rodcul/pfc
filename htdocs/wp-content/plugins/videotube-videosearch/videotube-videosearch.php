<?php
/*
Plugin Name: VideoTube Video Search
Plugin URI: http://themeforest.net/item/videotube-a-responsive-video-wordpress-theme/7214445
Description: Only display the video result in search page. 
Author: Toan Nguyen
Author URI: http://marstheme.com/
Version: 1.0
*/
if( !defined('ABSPATH') ) exit;
if( !function_exists('mars_filter_video_search') ){
	function mars_filter_video_search( $query ){
	  if ( !is_admin() && $query->is_main_query() && is_search() ) {
	    if ($query->is_search) {
	      $query->set('post_type', 'video');
	    }
	  }
		return $query;
	}
	add_filter('pre_get_posts', 'mars_filter_video_search');
}