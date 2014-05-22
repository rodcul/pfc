<?php
/**
 * VideoTube Tags Cloud
 * Add Tags Cloud widget, video_key and tag taxonomy is supported.
 * @author 		Toan Nguyen
 * @category 	Core
 * @version     1.0.0
 */
if( !defined('ABSPATH') ) exit;
if( !function_exists('Mars_KeyCloud_Widgets') ){
	function Mars_KeyCloud_Widgets() {
		register_widget('Mars_KeyCloud_Widgets_Class');
	}
	add_action('widgets_init', 'Mars_KeyCloud_Widgets');
}
class Mars_KeyCloud_Widgets_Class extends WP_Widget{
	function Mars_KeyCloud_Widgets_Class(){
		$widget_ops = array( 'classname' => 'mars-keycloud-widgets', 'description' => __('VT Tags Cloud (Popular Keys) Widget', 'wpo') );
		
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'mars-keycloud-widgets' );
		
		$this->WP_Widget( 'mars-keycloud-widgets', __('VT Tags Cloud', 'wpo'), $widget_ops, $control_ops );		
	}
	function widget($args, $instance){
		extract( $args );
		$title = apply_filters('widget_title', $instance['title'] );
		$socials = apply_filters('mars_social_profile_link', null);	
		$tag_cloud = array(
		    'smallest'                  => 8, 
		    'largest'                   => 22,
		    'unit'                      => 'pt', 
		    'number'                    => 20,  
		    'format'                    => 'flat',
		    'separator'                 => ' ',
		    'orderby'                   => 'name', 
		    'order'                     => 'ASC',
		    'exclude'                   => null, 
		    'include'                   => null, 
		    'link'                      => 'view', 
			'taxonomy'  => array('post_tag','video_tag'), 
		    'echo'                      => false
		);				
		print  $before_widget;
		print $before_title . $title . $after_title;	
			print wp_tag_cloud($tag_cloud);
		print $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$socials = apply_filters('mars_social_profile_link', $socials);
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		return $instance;		
		
	}
	function form( $instance ){
		$defaults = array( 'title' => __('Tags Cloud', 'mars'));
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'mars'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>								
	<?php		
	}
}

