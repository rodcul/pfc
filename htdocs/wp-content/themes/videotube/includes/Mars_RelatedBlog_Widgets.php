<?php
/**
 * VideoTube Related Blogs Widget
 * Display the related blogs widget below the Main Blogs content, located in single.php
 * @author 		Toan Nguyen
 * @category 	Core
 * @version     1.0.0
 */
if( !defined('ABSPATH') ) exit;
if( !function_exists('Mars_RelatedBlog_Widgets') ){
	function Mars_RelatedBlog_Widgets() {
		register_widget('Mars_RelatedBlog_Widgets_Class');
	}
	add_action('widgets_init', 'Mars_RelatedBlog_Widgets');
}
class Mars_RelatedBlog_Widgets_Class extends WP_Widget{
	function Mars_RelatedBlog_Widgets_Class(){
		$widget_ops = array( 'classname' => 'mars-relatedblog-widgets', 'description' => __('VT Related Blog Widget', 'wpo') );
		
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'mars-relatedblog-widgets' );
		
		$this->WP_Widget( 'mars-relatedblog-widgets', __('VT Related Blog Widget', 'wpo'), $widget_ops, $control_ops );		
	}
	function widget($args, $instance){
		global $post;
		extract( $args );
		wp_reset_postdata();
		$title = apply_filters('widget_title', $instance['title'] );
		$post_orderby = isset( $instance['post_orderby'] ) ? $instance['post_orderby'] : 'ID';
		$post_order = isset( $instance['post_order'] ) ? $instance['post_order'] : 'DESC';
		$post_filter_condition = isset( $instance['post_filter_condition'] ) ? $instance['post_filter_condition'] : 'both';
		
		$post_rows = isset( $instance['rows'] ) ? (int)$instance['rows'] : 1;
		$autoplay = isset( $instance['auto'] ) ? $instance['auto'] : null;			
		
		$widget_column = 3;
		$post_shows = isset( $instance['post_shows'] ) ? (int)$instance['post_shows'] : 16;  
		$current_postID = get_the_ID();
		
		$post_category = mars_get_current_postterm($current_postID,'category');
		$post_tag = mars_get_current_postterm($current_postID,'post_tag');

		$i=0;
		$posts_query = array(
			'post_type'	=>	'post',
			'showposts'	=>	$post_shows,
			'posts_per_page'	=>	$post_shows,
			'post__not_in'	=>	array($current_postID)
		);
		if( $post_filter_condition == 'both' ){
			if( isset( $post_category ) ){
				$posts_query['category__in'] = $post_category;
			}
			if( isset( $post_tag ) ){
				$posts_query['tag__in'] = $post_tag;
			}			
		}
		if( $post_filter_condition == 'category' ){
			if( isset( $post_category ) ){
				$posts_query['category__in'] = $post_category;
			}			
		}
		if( $post_filter_condition == 'post_tag' ){
			if( isset( $post_tag ) ){
				$posts_query['tag__in'] = $post_tag;
			}			
		}		
		if( $post_orderby ){
			$posts_query['orderby'] = $post_orderby;
		}
		if( $post_order ){
			$posts_query['order']	=	$post_order;
		}
		
		$wp_query = new WP_Query( $posts_query );
		if( $wp_query->found_posts > 1 ):
		?>
			<?php if( $widget_column == 3 ):?>
          		<div id="carousel-latest-<?php print $this->id; ?>" class="carousel carousel-<?php print $this->id; ?> slide video-section" data-ride="carousel">
          	<?php elseif ( $widget_column ==2 ):?>
          		<div class="row post-section meta-maxwidth-360">
          	<?php else:?>
          		<div id="carousel-latest-<?php print $this->id; ?>" class="carousel carousel-<?php print $this->id; ?> slide video-section" <?php if($post_shows>3):?> data-ride="carousel"<?php endif;?>>
          	<?php endif;?>
          		<?php if( $widget_column == 3 ):?>
                    <div class="section-header">
                <?php elseif ( $widget_column ==2 ):?>
                	<div class="col-sm-12 section-header">
	          	<?php else:?>
	          		<div class="section-header">
	          	<?php endif;?>
	          			<?php if( $title ):?>
                        	<h3><?php print $title;?></h3>
                        <?php endif;?>
                        <?php if( $widget_column != 2 ):?>
				            <?php if( $post_shows >= 3 || $post_shows == -1 ):?>
					            <?php if( $post_shows >= $wp_query->post_count && $post_shows > 3*$post_rows ):?>
						            <ol class="carousel-indicators section-nav">
						            	<li data-target="#carousel-latest-<?php print $this->id; ?>" data-slide-to="0" class="bullet active"></li>
						                <?php 
						                	$c = 0;
						                	for ($j = 1; $j < $wp_query->post_count; $j++) {
						                		if ( $j % (3*$post_rows) == 0 && $j < $post_shows ){
							                    	$c++;
							                    	print '<li data-target="#carousel-latest-'.$this->id.'" data-slide-to="'.$c.'" class="bullet"></li> '; 
							                    }	
						                	}
						                ?>
						            </ol>
					            <?php endif;?>
				            <?php endif;?>
	                    <?php endif;?>
                    </div>
                    
                    <?php if( $widget_column == 3 ):?>
	                    <div class="latest-wrapper">
	                    	<div class="row">
			                     <div class="carousel-inner">
			                       	<?php
			                       	if( $wp_query->have_posts() ) : 
			                       		$i =0;
				                       	while ( $wp_query->have_posts() ) : $wp_query->the_post();
				                       	$i++;
				                       	?>
				                       	<?php if( $i == 1 ):?>
				                       		<div class="item active">
				                       	<?php endif;?>	
				                       		<div class="col-sm-4 col-xs-6 item <?php print $this->id; ?>-<?php print get_the_ID();?>">
				                                <?php 
				                                	if(has_post_thumbnail()){
				                                		print '<a href="'.get_permalink(get_the_ID()).'">'. get_the_post_thumbnail(null,'video-lastest', array('class'=>'img-responsive')) .'</a>';
				                                	}
				                                ?>
	                                            <h3><a href="<?php the_permalink();?>"><?php the_title();?></a></h3>
		                                     </div> 
					                    <?php
					                    if ( $i % ($post_rows*3) == 0 && $i < $post_shows ){	
					                    	?></div><div class="item"><?php 
					                    } 	             
				                       	endwhile;
				                      ?></div><?php 
			                       	endif;
			                       	?> 
			                        </div>
	                            </div>
	                    </div>
                    <?php endif;?>
                </div><!-- /#carousel-->		
		<?php 
		endif;
		wp_reset_query();wp_reset_postdata();
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['post_orderby'] = strip_tags( $new_instance['post_orderby'] );
		$instance['post_order'] = strip_tags( $new_instance['post_order'] );
		$instance['post_filter_condition'] = strip_tags( $new_instance['post_filter_condition'] );
		$instance['post_shows'] = strip_tags( $new_instance['post_shows'] );
		$instance['rows'] = strip_tags( $new_instance['rows'] );
		$instance['auto'] = strip_tags( $new_instance['auto'] );			
		return $instance;		
		
	}
	function form( $instance ){
		$defaults = array( 'title' => __('Related posts', 'mars'));
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'mars'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo ( isset( $instance['title'] ) ? $instance['title'] : null ); ?>" style="width:100%;" />
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
		    		foreach ( $this->widget_post_order() as $key=>$value ){
		    			$selected = ( $instance['post_order'] == $key ) ? 'selected' : null;
		    			?>
		    				<option <?php print $selected; ?> value="<?php print $key;?>"><?php print $value;?></option>
		    			<?php 
		    		}
		    	?>
		    </select>  
		</p>
		<p>  
		    <label for="<?php echo $this->get_field_id( 'post_filter_condition' ); ?>"><?php _e('Filter Condition:', 'mars'); ?></label>
		    <select style="width:100%;" id="<?php echo $this->get_field_id( 'post_filter_condition' ); ?>" name="<?php echo $this->get_field_name( 'post_filter_condition' ); ?>">
		    	<?php 
		    		foreach ( $this->condition() as $key=>$value ){
		    			$selected = ( $instance['post_filter_condition'] == $key ) ? 'selected' : null;
		    			?>
		    				<option <?php print $selected; ?> value="<?php print $key;?>"><?php print $value;?></option>
		    			<?php 
		    		}
		    	?>
		    </select>  
		</p>		
		<p>  
		    <label for="<?php echo $this->get_field_id( 'post_shows' ); ?>"><?php _e('Shows:', 'mars'); ?></label>
		    <input id="<?php echo $this->get_field_id( 'post_shows' ); ?>" name="<?php echo $this->get_field_name( 'post_shows' ); ?>" value="<?php echo isset( $instance['post_shows'] ) ? (int)$instance['post_shows'] : 16; ?>" style="width:100%;" />
		</p>
		<p>  
		    <label for="<?php echo $this->get_field_id( 'rows' ); ?>"><?php _e('Rows:', 'mars'); ?></label>
		    <input id="<?php echo $this->get_field_id( 'rows' ); ?>" name="<?php echo $this->get_field_name( 'rows' ); ?>" value="<?php echo (isset( $instance['rows'] )) ? (int)$instance['rows'] : 1; ?>" style="width:100%;" />
		</p>
		<p>  
		    <label for="<?php echo $this->get_field_id( 'auto' ); ?>"><?php _e('Auto Play:', 'mars'); ?></label>
		    <input type="checkbox" id="<?php echo $this->get_field_id( 'auto' ); ?>" name="<?php echo $this->get_field_name( 'auto' ); ?>" <?php  print isset( $instance['auto'] ) && $instance['auto'] =='on' ? 'checked' : null;?> />
		</p>
	<?php
	}
	function widget_post_order(){
		return array(
			'ASC'	=>	__('ASC','mars'),
			'DESC'	=>	__('DESC','mars')
		);
	}	
	function condition(){
		return array(
			'both'			=>	__('Category and Post Tag','mars'),
			'category'	=>	__('Category','mars'),
			'post_tag'		=>	__('Post Tag','mars')
		);
	}
}