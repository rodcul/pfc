<?php
/**
 * VideoTube Search Form Widget
 * VideoTube Search Form Widget
 * @author 		Toan Nguyen
 * @category 	Core
 * @version     1.0.0
 */
if( !defined('ABSPATH') ) exit;
if( !function_exists('Mars_SearchForm_Widgets') ){
	function Mars_SearchForm_Widgets() {
		register_widget('Mars_SearchForm_Widgets_Class');
	}
	add_action('widgets_init', 'Mars_SearchForm_Widgets');
}
class Mars_SearchForm_Widgets_Class extends WP_Widget{
	function Mars_SearchForm_Widgets_Class(){
		$widget_ops = array( 'classname' => 'mars-searchform-widgets', 'description' => __('VT Search Form in Header', 'wpo') );
		
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'mars-searchform-widgets' );
		
		$this->WP_Widget( 'mars-searchform-widgets', __('VT Search Form', 'wpo'), $widget_ops, $control_ops );		
	}
	function widget($args, $instance){
		extract( $args );
		$title = apply_filters('widget_title', $instance['title'] );
		?>
			<form method="get" action="<?php print home_url();?>">	
				<div class="col-sm-6" id="header-search">
					<span class="glyphicon glyphicon-search search-icon"></span>
					<input value="<?php print get_search_query();?>" name="s" type="text" placeholder="<?php _e('Search here...','mars')?>" id="search">
				</div>
			</form>		
		<?php 	
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		return $instance;				
	}
	function form( $instance ){
		$defaults = array( 'title' => __('Search Form','mars'));
		$instance = wp_parse_args( (array) $instance, $defaults ); 
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'mars'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo ( isset( $instance['title'] ) ? $instance['title'] : null ); ?>" style="width:100%;" />
		</p>										
	<?php		
	}
}


