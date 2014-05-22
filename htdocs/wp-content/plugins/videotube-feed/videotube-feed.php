<?php
/*
Plugin Name: VideoTube Feed
Plugin URI: http://themeforest.net/item/videotube-a-responsive-video-wordpress-theme/7214445
Description: Add the Video in WP Feed.
Author: Toan Nguyen
Author URI: http://marstheme.com/
Version: 1.0
*/
if( !defined('ABSPATH') ) exit;
if( !function_exists( 'videotube_add_video_feed' ) ){
	function videotube_add_video_feed($qv) {
		if (isset($qv['feed']) && !isset($qv['post_type']))
			$qv['post_type'] = array('post', 'video');
		return $qv;
	}
	add_filter('request', 'videotube_add_video_feed');	
}
