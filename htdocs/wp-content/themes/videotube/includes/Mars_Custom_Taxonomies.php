<?php if( !defined('ABSPATH') ) exit; ?>
<?php 
if( !class_exists('Mars_Custom_Taxonomies') ){
	class Mars_Custom_Taxonomies {
		function __construct() {
			add_action('init', array($this,'cptui_register_my_taxes_categories'));
			add_action('init', array($this,'cptui_register_my_taxes_key'));
		}
		function cptui_register_my_taxes_categories() {
			register_taxonomy( 'categories',array (
			  0 => 'video',
			),
			array( 'hierarchical' => true,
					'label' => __('Video Category','mars'),
					'show_ui' => true,
					'query_var' => true,
					'show_admin_column' => true,
					'labels' => array (
					  'search_items' => __('Video Category','mars'),
					  'popular_items' => '',
					  'all_items' => '',
					  'parent_item' => '',
					  'parent_item_colon' => '',
					  'edit_item' => '',
					  'update_item' => '',
					  'add_new_item' => '',
					  'new_item_name' => '',
					  'separate_items_with_commas' => '',
					  'add_or_remove_items' => '',
					  'choose_from_most_used' => '',
					)
				)
			); 
		}
		function cptui_register_my_taxes_key() {
			register_taxonomy( 'video_tag',array (
			  0 => 'video',
			),
			array( 'hierarchical' => false,
					'label' => __('Video Tag','mars'),
					'show_ui' => true,
					'query_var' => true,
					'show_admin_column' => true,
					'labels' => array (
					  'search_items' => __('Video Tag','mars'),
					  'popular_items' => '',
					  'all_items' => '',
					  'parent_item' => '',
					  'parent_item_colon' => '',
					  'edit_item' => '',
					  'update_item' => '',
					  'add_new_item' => '',
					  'new_item_name' => '',
					  'separate_items_with_commas' => '',
					  'add_or_remove_items' => '',
					  'choose_from_most_used' => '',
					)
				) 
			); 
		}		
		
	}
	new Mars_Custom_Taxonomies();
}
