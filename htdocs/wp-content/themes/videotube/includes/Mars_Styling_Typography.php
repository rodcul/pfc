<?php
/**
 * VideoTube Styling and Typography
 * Add External Style as: Color, Background.
 * @author 		Toan Nguyen
 * @category 	Core
 * @version     1.0.0
 */
if( !defined('ABSPATH') ) exit;
if( !class_exists('Mars_Styling_Typography') ){
	class Mars_Styling_Typography {
		function __construct() {
			add_action('wp_head', array($this,'restyle'));
		}
		function restyle(){
			global $videotube;
			$style = null;
			$child_style = null;
			
			$font_body =  isset( $videotube['typography-body'] ) ? $videotube['typography-body'] : null;
			$font_headings =  isset( $videotube['typography-headings'] ) ? $videotube['typography-headings'] : null;
			$font_menu = isset( $videotube['typography-menu'] ) ? $videotube['typography-menu'] : null;			
			
			if( isset( $videotube['color-header'] ) && !empty( $videotube['color-header'] ) ){
				$child_style .= 'div#header{background:'.$videotube['color-header'].'}';
			}
			if( isset( $videotube['color-header-navigation'] ) && !empty( $videotube['color-header-navigation'] ) ){
				$child_style .= '#navigation-wrapper{background:'.$videotube['color-header-navigation'].'!important;}';
				$child_style .= '.dropdown-menu{background:'.$videotube['color-header-navigation'].'!important;}';
			}
			if( isset( $videotube['color-text-header-navigation'] ) && !empty( $videotube['color-text-header-navigation'] ) ){
				$child_style .= '#navigation-wrapper ul.menu li a{color:'.$videotube['color-text-header-navigation'].'}';
			}
			if( isset( $videotube['color-widget'] ) && !empty( $videotube['color-widget'] ) ){
				$child_style .= '.widget h4.widget-title{background:'.$videotube['color-widget'].'}';
			}
			if( isset( $videotube['color-text-widget'] ) && !empty( $videotube['color-text-widget'] ) ){
				$child_style .= '.widget h4.widget-title{color:'.$videotube['color-text-widget'].'}';
			}
			
			if( isset( $videotube['color-footer'] ) && !empty( $videotube['color-footer'] ) ){
				$child_style .= '#footer{background:'.$videotube['color-footer'].'}';
			}
			if( isset( $videotube['color-footer-text'] ) && !empty( $videotube['color-footer-text'] ) ){
				$child_style .= '#footer .widget ul li a, #footer .widget p a{color:'.$videotube['color-footer-text'].'}#footer .widget p{color:'.$videotube['color-footer-text'].'}';
			}
			if( !empty( $font_body ) ){
				$child_style .= 'body{font-family:'.$font_body['font-family'].';}';
			}
			if( !empty( $font_headings ) ){
				$child_style .= 'h1,h2,h3,h4,h5,h6 {font-family:'.$font_headings['font-family'].'!important}';
			}
			if( !empty( $font_menu ) ){
				$child_style .= '#navigation-wrapper ul.menu li a{font-family:'.$font_menu['font-family'].', sans-serif!important;}';
			}
			if( !empty( $child_style ) ){
				$style .= '<style>'.$child_style.'</style>';
			}
		
			print $style;
		}
	}
	new Mars_Styling_Typography();
}