<?php
/*
Plugin Name: Recaptcha Class
Plugin URI: http://themeforest.net/user/phpface
Description: Recaptcha Class
Author: Toan Nguyen
Version: 1.0.0
Author URI: http://themeforest.net/user/phpface
*/
if( !defined( 'ABSPATH' ) ) exit;
if( !class_exists( 'VideoTube_Recaptchalib' ) ){
	class VideoTube_Recaptchalib {
		function __construct() {
			add_action('init', array( $this, 'init' ) );
		}
		function init(){
			if( !function_exists('recaptcha_check_answer') ){
		  		require_once 'class/recaptchalib.php';
		  	}			
		}
	}
	new VideoTube_Recaptchalib();
}