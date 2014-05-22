<?php
/**
 * VideoTube Post Right Widget
 * Add Video Post in Right Sidebar.
 * @author 		Toan Nguyen
 * @category 	Core
 * @version     1.0.0
 */
if( !defined('ABSPATH') ) exit;
if( !function_exists('Mars_Posts_Widget_Siderbar') ){
	function Mars_Posts_Widget_Siderbar() {
		register_widget('Mars_Posts_Widget_Siderbar_Class');
	}
	add_action('widgets_init', 'Mars_Posts_Widget_Siderbar');
}
class Mars_Posts_Widget_Siderbar_Class extends WP_Widget{
	function Mars_Posts_Widget_Siderbar_Class(){
		$widget_ops = array( 'classname' => 'mars-posts-sidebar-widget', 'description' => __('VT Right Post Widgets', 'wpo') );
		
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'mars-posts-sidebar-widget' );
		
		$this->WP_Widget( 'mars-posts-sidebar-widget', __('VT Right Post Widgets', 'wpo'), $widget_ops, $control_ops );		
	}
	function widget($args, $instance){
		$WidgetHTML = null;
		extract( $args );
		//wp_reset_postdata();
		global $post;
		$title = apply_filters('widget_title', $instance['title'] );
		$post_category = isset( $instance['post_category'] ) ? $instance['post_category'] : null;
		$post_tag = isset( $instance['post_tag'] ) ? $instance['post_tag'] : null;
		$post_orderby = isset( $instance['post_orderby'] ) ? $instance['post_orderby'] : 'ID';
		$post_order = isset( $instance['post_order'] ) ? $instance['post_order'] : 'DESC';
		$widget_column = isset( $instance['widget_column'] ) ? $instance['widget_column'] : 2;
		$post_shows = isset( $instance['post_shows'] ) ? (int)$instance['post_shows'] : 4;  
		print  $before_widget;
		print $before_title . $title . $after_title;
		$posts_query = array(
			'post_type'	=>	'post',
			'showposts'	=>	$post_shows,
			'ignore_sticky_posts'	=>	true
		);
                       	
		if( $post_category ){
			$posts_query['tax_query'] = array(
				array(
				'taxonomy' => 'category',
				'field' => 'id',
				'terms' => $post_category
				)		                       		
			);
		}
		if( $post_tag ){
			$posts_query['tax_query'][] = array(
				'taxonomy' => 'post_tag',
				'field' => 'slug',
				'terms' => explode(",", $post_tag)
			);
		}
		
		if( $post_orderby ){
			$posts_query['orderby'] = $post_orderby;	
		}
		if( $post_order ){
			$posts_query['order']	=	$post_order;
		}	
		if( isset( $post->ID ) ){
			$posts_query['post__not_in'] = array( $post->ID  );
		}
		$wp_query = new WP_Query( $posts_query );
		?>
			<?php if( $widget_column == 2 ):?>
	        <div class="row">
	        	<?php if( $wp_query->have_posts() ): while ( $wp_query->have_posts() ): $wp_query->the_post();?>
	        	<?php
	        	$postdata = mars_get_post_data( get_the_ID() );
	        	?>
	            <div id="post-right-<?php print $this->id; ?>-<?php the_ID();?>" <?php post_class('col-xs-6 item'); ?>>
	            	<?php 
	            		if( has_post_thumbnail() ){
	            			print '<a title="'.get_the_title().'" href="'.get_permalink(get_the_ID()).'">'. get_the_post_thumbnail(null,'most-video-2col', array('class'=>'img-responsive')) .'</a>';
	            		}
	            	?>
                    <div class="post-header">              	         	
	                	<h3><a title="<?php the_title()?>" href="<?php the_permalink();?>"><?php the_title();?></a></h3>
						<span class="post-meta">
							<i class="fa fa-clock-o"></i> <?php print get_the_date('F j ,Y');?>
						</span>	                	
	                </div>
	       		</div>
	       		<?php endwhile;endif;?>
	        </div>
	        <?php else:?>
	        	<?php if( $wp_query->have_posts() ): while ( $wp_query->have_posts() ): $wp_query->the_post();?>
	        	<?php
	        	$postdata = mars_get_post_data( get_the_ID() );
	        	?>	        	
			        <div id="post-right-<?php print $this->id; ?>-<?php the_ID();?>" <?php post_class('item'); ?>>
		            	<?php 
		            		if( has_post_thumbnail() ){
		            			print '<a title="'.get_the_title().'" href="'.get_permalink(get_the_ID()).'">'. get_the_post_thumbnail(null,'video-featured', array('class'=>'img-responsive')) .'</a>';
		            		}
		            	?>	            	
	                    <div class="post-header">              	         	
		                	<h3><a title="<?php the_title()?>" href="<?php the_permalink();?>"><?php the_title();?></a></h3>
							<span class="post-meta">
								<i class="fa fa-clock-o"></i> <?php print get_the_date('F j ,Y');?>
							</span>	                	
		                </div>
			        </div>	
		        <?php endwhile;endif;?>        
	        <?php endif;?>
	    <?php 		
		print $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['post_category'] = strip_tags( $new_instance['post_category'] );
		$instance['post_tag'] = strip_tags( $new_instance['post_tag'] );
		$instance['post_orderby'] = strip_tags( $new_instance['post_orderby'] );
		$instance['post_order'] = strip_tags( $new_instance['post_order'] );
		$instance['widget_column'] = strip_tags( $new_instance['widget_column'] );
		$instance['post_shows'] = strip_tags( $new_instance['post_shows'] );
		$instance['view_more'] = strip_tags( $new_instance['view_more'] );
		return $instance;		
		
	}
	function form( $instance ){
		$defaults = array( 'title' => __('Right Sidebar Posts', 'mars'));
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'mars'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo ( isset( $instance['title'] ) ? $instance['title'] : null ); ?>" style="width:100%;" />
		</p>		
		<p>  
		    <label for="<?php echo $this->get_field_id( 'video_category' ); ?>"><?php _e('Video Category:', 'mars'); ?></label>
		    	<?php 
					wp_dropdown_categories($args = array(
							'show_option_all'    => 'All',
							'orderby'            => 'ID', 
							'order'              => 'ASC',
							'show_count'         => 1,
							'hide_empty'         => 1, 
							'child_of'           => 0,
							'echo'               => 1,
							'selected'           => isset( $instance['post_category'] ) ? $instance['post_category'] : null,
							'hierarchical'       => 0, 
							'name'               => $this->get_field_name( 'post_category' ),
							'id'                 => $this->get_field_id( 'post_category' ),
							'taxonomy'           => 'category',
							'hide_if_empty'      => true,
							'class'              => 'postform mars-dropdown',
			    		)
		    		);
		    	?>
		</p>
		<p>  
		    <label for="<?php echo $this->get_field_id( 'post_tag' ); ?>"><?php _e('Post Tag:', 'mars'); ?></label>
		    <input placeholder="<?php _e('Eg: tag1,tag2,tag3','mars');?>" id="<?php echo $this->get_field_id( 'post_tag' ); ?>" name="<?php echo $this->get_field_name( 'post_tag' ); ?>" value="<?php echo ( isset( $instance['post_tag'] ) ? $instance['post_tag'] : null ); ?>" style="width:100%;" />
		</p>
		<p>  
		    <label for="<?php echo $this->get_field_id( 'post_orderby' ); ?>"><?php _e('Orderby:', 'mars'); ?></label>
		    <select style="width:100%;" id="<?php echo $this->get_field_id( 'post_orderby' ); ?>" name="<?php echo $this->get_field_name( 'post_orderby' ); ?>">
		    	<?php 
		    		foreach ( post_orderby_options() as $key=>$value ){
		    			$selected = ( $instance['post_orderby'] == $key ) ? 'selected' : null;
		    			?>
		    				<option <?php print $selected; ?> value="<?php print $key;?>"><?php print $value;?></option>
		    			<?php 
		    		}
		    	?>
		    </select>
		</p>
		<p>  
		    <label for="<?php echo $this->get_field_id( 'post_order' ); ?>"><?php _e('Order:', 'mars'); ?></label>
		    <select style="width:100%;" id="<?php echo $this->get_field_id( 'post_order' ); ?>" name="<?php echo $this->get_field_name( 'post_order' ); ?>">
		    	<?php 
		    		foreach ( $this->widget_video_order() as $key=>$value ){
		    			$selected = ( $instance['post_order'] == $key ) ? 'selected' : null;
		    			?>
		    				<option <?php print $selected; ?> value="<?php print $key;?>"><?php print $value;?></option>
		    			<?php 
		    		}
		    	?>
		    </select>  
		</p>								 
		<p>  
		    <label for="<?php echo $this->get_field_id( 'widget_column' ); ?>"><?php _e('Widget Column:', 'mars'); ?></label>
		    <select style="width:100%;" id="<?php echo $this->get_field_id( 'widget_column' ); ?>" name="<?php echo $this->get_field_name( 'widget_column' ); ?>">
		    	<?php 
		    		foreach ( $this->widget_video_column() as $key=>$value ){
		    			$selected = ( $instance['widget_column'] == $key ) ? 'selected' : null;
		    			?>
		    				<option <?php print $selected; ?> value="<?php print $key;?>"><?php print $value;?></option>
		    			<?php 
		    		}
		    	?>
		    </select>  
		</p>
		<p>  
		    <label for="<?php echo $this->get_field_id( 'post_shows' ); ?>"><?php _e('Shows:', 'mars'); ?></label>
		    <input id="<?php echo $this->get_field_id( 'post_shows' ); ?>" name="<?php echo $this->get_field_name( 'post_shows' ); ?>" value="<?php echo isset( $instance['post_shows'] ) ? (int)$instance['post_shows'] : 4; ?>" style="width:100%;" />
		</p>								
	<?php		
	}
	function widget_video_column(){
		return array(
			'2'	=>	__('2 Columns','mars'),
			'1'	=>	__('1 Column','mars')
		);
	}
	function widget_video_order(){
		return array(
			'DESC'	=>	__('DESC','mars'),
			'ASC'	=>	__('ASC','mars')
		);
	}		
}

