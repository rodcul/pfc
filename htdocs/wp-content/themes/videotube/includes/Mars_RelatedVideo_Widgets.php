<?php
/**
 * VideoTube Related Videos Widget
 * Display the related videos widget below the Main video content, located in single-video.php
 * @author 		Toan Nguyen
 * @category 	Core
 * @version     1.0.0
 */
if( !defined('ABSPATH') ) exit;
if( !function_exists('Mars_RelatedVideos_Widgets') ){
	function Mars_RelatedVideos_Widgets() {
		register_widget('Mars_RelatedVideos_Widgets_Class');
	}
	add_action('widgets_init', 'Mars_RelatedVideos_Widgets');
}
class Mars_RelatedVideos_Widgets_Class extends WP_Widget{
	function Mars_RelatedVideos_Widgets_Class(){
		$widget_ops = array( 'classname' => 'mars-relatedvideo-widgets', 'description' => __('VT Related Videos Widget', 'wpo') );
		
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'mars-relatedvideo-widgets' );
		
		$this->WP_Widget( 'mars-relatedvideo-widgets', __('VT Related Videos Widget', 'wpo'), $widget_ops, $control_ops );		
	}
	function widget($args, $instance){
		global $post;	
		extract( $args );
		wp_reset_postdata();
		$title = apply_filters('widget_title', $instance['title'] );
		$video_orderby = isset( $instance['video_orderby'] ) ? $instance['video_orderby'] : 'ID';
		$video_order = isset( $instance['video_order'] ) ? $instance['video_order'] : 'DESC';
		$video_filter_condition = isset( $instance['video_filter_condition'] ) ? $instance['video_filter_condition'] : 'both';
		$video_rows = isset( $instance['rows'] ) ? (int)$instance['rows'] : 1;
		$autoplay = isset( $instance['auto'] ) ? $instance['auto'] : null;		
		
		$widget_column = 3;
		$video_shows = isset( $instance['video_shows'] ) ? (int)$instance['video_shows'] : 16;  
		$current_videoID = get_the_ID();
		
		$video_category = mars_get_current_postterm($current_videoID,'categories');
		$video_tag = mars_get_current_postterm($current_videoID,'video_tag');

		$i=0;
		$videos_query = array(
			'post_type'	=>	'video',
			'showposts'	=>	$video_shows,
			'posts_per_page'	=>	$video_shows,
			'post__not_in'	=>	array($current_videoID)
		);
        if( $video_filter_condition == 'both' ){

        	if( !empty( $video_category ) ){
				$videos_query['tax_query'] = array(
					array(
						'taxonomy' => 'categories',
						'field' => 'id',
						'terms' => $video_category
					)
				);
			}
			if( !empty( $video_tag ) ){
				$videos_query['tax_query'] = array(
					array(
						'taxonomy' => 'video_tag',
						'field' => 'id',
						'terms' => $video_tag
					)
				);
			}
        }
         if( $video_filter_condition == 'categories' ){
            if( !empty( $video_category ) ){
				$videos_query['tax_query'] = array(
					array(
						'taxonomy' => 'categories',
						'field' => 'id',
						'terms' => $video_category
					)
				);
			}         	
         }
        
	    if( $video_filter_condition == 'video_tag' ){
	    	if( !empty( $video_tag ) ){
				$videos_query['tax_query'] = array(
					array(
						'taxonomy' => 'video_tag',
						'field' => 'id',
						'terms' => $video_tag
					)
				);
			}         	
         }        
        
		if( isset( $video_orderby ) ){
			if( $video_orderby == 'views' ){
				$videos_query['meta_key'] = 'count_viewed';
				$videos_query['orderby']	=	'meta_value_num';
			}
			elseif( $video_orderby == 'likes' ){
				$videos_query['meta_key'] = 'like_key';
				$videos_query['orderby']	=	'meta_value_num';				
			}
			else{
				$videos_query['orderby'] = $video_orderby;	
			}
		}
		if( isset( $video_order ) ){
			$videos_query['order']	=	$video_order;
		}
		if( isset( $post->ID ) ){
			$videos_query['post__not_in'] = array( $post->ID  );
		}		
		$wp_query = new WP_Query( $videos_query );
		if( $wp_query->found_posts > 1 ):
		?>
			<?php if( $widget_column == 3 ):?>
          		<div id="carousel-latest-<?php print $this->id; ?>" class="carousel carousel-<?php print $this->id; ?> slide video-section" data-ride="carousel">
          	<?php elseif ( $widget_column ==2 ):?>
          		<div class="row video-section meta-maxwidth-360">
          	<?php else:?>
          		<div id="carousel-latest-<?php print $this->id; ?>" class="carousel carousel-<?php print $this->id; ?> slide video-section" <?php if($video_shows>3):?> data-ride="carousel"<?php endif;?>>
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
				            <?php if( $video_shows >= 3 || $video_shows == -1 ):?>
					            <?php if( $video_shows >= $wp_query->post_count && $video_shows > 3*$video_rows ):?>
						            <ol class="carousel-indicators section-nav">
						            	<li data-target="#carousel-latest-<?php print $this->id; ?>" data-slide-to="0" class="bullet active"></li>
						                <?php 
						                	$c = 0;
						                	for ($j = 1; $j < $wp_query->post_count; $j++) {
						                		if ( $j % (3*$video_rows) == 0 && $j < $video_shows ){
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
				                       			<div class="item-img">
				                                <?php 
				                                	if(has_post_thumbnail()){
				                                		print '<a href="'.get_permalink(get_the_ID()).'">'. get_the_post_thumbnail(null,'video-lastest', array('class'=>'img-responsive')).'</a>';
				                                	}
				                                ?>
													<a href="<?php echo get_permalink(get_the_ID()); ?>"><div class="img-hover"></div></a>
												</div>				                                
	                                            <h3><a title="<?php the_title();?>" href="<?php the_permalink();?>"><?php the_title();?></a></h3>
												<?php print apply_filters('mars_video_meta',null);?>
		                                     </div> 
					                    <?php
					                    //if ( $i % 3 == 0 ){
					                    if ( $i % ($video_rows*3) == 0 && $i < $video_shows ){	
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
				<?php if( $autoplay == 'on' ):?>
				<script>
				(function($) {
				  "use strict";
					jQuery('.carousel-<?php print $this->id; ?>').carousel({
						 pause: false
					});
				})(jQuery);
				</script>				
				<?php endif;?>
		<?php 
		endif;
		wp_reset_query();wp_reset_postdata();
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['video_orderby'] = strip_tags( $new_instance['video_orderby'] );
		$instance['video_order'] = strip_tags( $new_instance['video_order'] );
		$instance['video_filter_condition'] = strip_tags( $new_instance['video_filter_condition'] );
		$instance['video_shows'] = strip_tags( $new_instance['video_shows'] );
		$instance['rows'] = strip_tags( $new_instance['rows'] );
		$instance['auto'] = strip_tags( $new_instance['auto'] );		
		return $instance;		
		
	}
	function form( $instance ){
		$defaults = array( 'title' => __('Related Videos', 'mars'));
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'mars'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo ( isset( $instance['title'] ) ? $instance['title'] : null ); ?>" style="width:100%;" />
		</p>
		<p>  
		    <label for="<?php echo $this->get_field_id( 'video_orderby' ); ?>"><?php _e('Orderby:', 'mars'); ?></label>
		    <select style="width:100%;" id="<?php echo $this->get_field_id( 'video_orderby' ); ?>" name="<?php echo $this->get_field_name( 'video_orderby' ); ?>">
		    	<?php 
		    		foreach ( post_orderby_options('video') as $key=>$value ){
		    			$selected = ( $instance['video_orderby'] == $key ) ? 'selected' : null;
		    			?>
		    				<option <?php print $selected; ?> value="<?php print $key;?>"><?php print $value;?></option>
		    			<?php 
		    		}
		    	?>
		    </select>  
		</p>
		<p>  
		    <label for="<?php echo $this->get_field_id( 'video_order' ); ?>"><?php _e('Order:', 'mars'); ?></label>
		    <select style="width:100%;" id="<?php echo $this->get_field_id( 'video_order' ); ?>" name="<?php echo $this->get_field_name( 'video_order' ); ?>">
		    	<?php 
		    		foreach ( $this->widget_video_order() as $key=>$value ){
		    			$selected = ( $instance['video_order'] == $key ) ? 'selected' : null;
		    			?>
		    				<option <?php print $selected; ?> value="<?php print $key;?>"><?php print $value;?></option>
		    			<?php 
		    		}
		    	?>
		    </select>  
		</p>
		<p>  
		    <label for="<?php echo $this->get_field_id( 'video_filter_condition' ); ?>"><?php _e('Filter Condition:', 'mars'); ?></label>
		    <select style="width:100%;" id="<?php echo $this->get_field_id( 'video_filter_condition' ); ?>" name="<?php echo $this->get_field_name( 'video_filter_condition' ); ?>">
		    	<?php 
		    		foreach ( $this->condition() as $key=>$value ){
		    			$selected = ( $instance['video_filter_condition'] == $key ) ? 'selected' : null;
		    			?>
		    				<option <?php print $selected; ?> value="<?php print $key;?>"><?php print $value;?></option>
		    			<?php 
		    		}
		    	?>
		    </select>  
		</p>		
		<p>  
		    <label for="<?php echo $this->get_field_id( 'video_shows' ); ?>"><?php _e('Shows:', 'mars'); ?></label>
		    <input id="<?php echo $this->get_field_id( 'video_shows' ); ?>" name="<?php echo $this->get_field_name( 'video_shows' ); ?>" value="<?php echo isset( $instance['video_shows'] ) ? (int)$instance['video_shows'] : 16; ?>" style="width:100%;" />
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
	function widget_video_order(){
		return array(
			'ASC'	=>	__('ASC','mars'),
			'DESC'	=>	__('DESC','mars')
		);
	}
	function condition(){
		return array(
			'both'			=>	__('Video Category and Video Tag','mars'),
			'categories'	=>	__('Video Category','mars'),
			'video_tag'		=>	__('Video Tag','mars')
		);
	}
}