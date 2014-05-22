<?php
/**
 * VideoTube StayConnected widget
 * Add StayConnected widget.
 * @author 		Toan Nguyen
 * @category 	Core
 * @version     1.0.0
 */
if( !defined('ABSPATH') ) exit;
if( !function_exists('Mars_Connected_Widget') ){
	function Mars_Connected_Widget() {
		register_widget('Mars_Connected_Widget_Class');
	}
	add_action('widgets_init', 'Mars_Connected_Widget');
}
class Mars_Connected_Widget_Class extends WP_Widget{
	function Mars_Connected_Widget_Class(){
		$widget_ops = array( 'classname' => 'mars-connected-widget', 'description' => __('VT Stay Connected Box, placed in footer', 'wpo') );
		
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'mars-connected-widget' );
		
		$this->WP_Widget( 'mars-connected-widget', __('VT Stay Connected Box', 'wpo'), $widget_ops, $control_ops );		
	}
	function widget($args, $instance){
		extract( $args );
		global $videotube;
		$title = apply_filters('widget_title', $instance['title'] );
		print  $before_widget;
		print $before_title . $title . $after_title;	
			print '<ul class="list-unstyled social">';
				$social_array = mars_socials_url();
				foreach ( $social_array as $key=>$value ){
					if( !empty( $videotube[$key] ) ){
						print '<li><a href="'.$videotube[$key].'"><i class="fa fa-'.$key.'"></i> '.$value.'</a></li>';
					}
				}
				print '<li><a href="'.get_bloginfo('rss_url').'"><i class="fa fa-rss"></i> RSS</a></li>';
			print '</ul>';
		print $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );		
		return $instance;
	}
	function form( $instance ){
		$defaults = array( 'title' => __('Stay Connected', 'mars'));
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'mars'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>
	<?php		
	}	
}

