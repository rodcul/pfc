<?php
/**
 * VideoTube Featured Widget
 * Add Video Featured Widget
 * @author 		Toan Nguyen
 * @category 	Core
 * @version     1.0.0
 */
if( !defined('ABSPATH') ) exit;
if( !function_exists('Mars_FeaturedPosts_Widgets') ){
	function Mars_FeaturedPosts_Widgets() {
		register_widget('Mars_FeaturedPosts_Widgets_Class');
	}
	add_action('widgets_init', 'Mars_FeaturedPosts_Widgets');
}
class Mars_FeaturedPosts_Widgets_Class extends WP_Widget{
	function Mars_FeaturedPosts_Widgets_Class(){
		$widget_ops = array( 'classname' => 'mars-featuredpost-widgets', 'description' => __('VT Featured Posts Widget, used in Featured Sidebar.', 'wpo') );
		
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'mars-featuredpost-widgets' );
		
		$this->WP_Widget( 'mars-featuredpost-widgets', __('VT Featured Posts Widget', 'wpo'), $widget_ops, $control_ops );		
	}
	function widget($args, $instance){
		extract( $args );
		$title = apply_filters('widget_title', $instance['title'] );
		$post_category = isset( $instance['post_category'] ) ? $instance['post_category'] : null;
		$post_tag = isset( $instance['post_tag'] ) ? $instance['post_tag'] : null;
		$post_date = isset( $instance['date'] ) ? $instance['date'] : null;
		$post_orderby = isset( $instance['post_orderby'] ) ? $instance['post_orderby'] : 'ID';
		$post_order = isset($instance['post_order']) ? $instance['post_order'] : 'DESC';
		$post_ids = isset( $instance['ids'] ) ? $instance['ids'] : null;
		$post_shows = isset( $instance['post_shows'] ) ? (int)$instance['post_shows'] : 9;  
		$post_sticky = isset( $instance['post_sticky'] ) ? $instance['post_sticky'] : null;
		$post_rows = isset( $instance['rows'] ) ? (int)$instance['rows'] : 1;
		$autoplay = isset( $instance['auto'] ) ? $instance['auto'] : null;
		$i=0;
		$posts_query = array(
			'post_type'	=>	'post',
			'posts_per_page'	=>	$post_shows,
		);
		if( $post_sticky =='on' ){
			$sticky = get_option( 'sticky_posts' );
			$posts_query['post__in']	=	$sticky;
		}
		else{
			$posts_query['ignore_sticky_posts']	=	true;
		}
		if( $post_category ){
			$posts_query['tax_query'][] = array(
				'taxonomy' => 'category',
				'field' => 'id',
				'terms' => array((int)$post_category)	                       		
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
		if( $post_ids ){
			$posts_query['post__in']	=	explode(",", $post_ids);
		}
		if( $post_date ){
			$dateime = explode("-", $post_date);
			$posts_query['date_query'] = array(
				array(
					'year'  => isset( $dateime[0] ) ? $dateime[0] : null,
					'month' => isset( $dateime[1] ) ? $dateime[1] : null,
					'day'   => isset( $dateime[2] ) ? $dateime[2] : null,
				)
			);
		}		
		$wp_query = new WP_Query( $posts_query );
		?>
		    <div id="carousel-featured-<?php print $this->id; ?>" class="carousel carousel-<?php print $this->id; ?> slide" data-ride="carousel">
		        <div class="container section-header">
		        	<?php if( $title ): ?>
		            <h3><i class="fa fa-pencil"></i> <?php print $title;?></h3>
		            <?php endif;?>
		            <?php if( $post_shows > 3 || $post_shows == -1 ):?>
			            <?php if( $post_shows >= $wp_query->post_count && $post_shows > 3*$post_rows ):?>
				            <ol class="carousel-indicators section-nav">
				            	<li data-target="#carousel-featured-<?php print $this->id; ?>" data-slide-to="0" class="bullet active"></li>
				                <?php 
				                	$c = 0;
				                	for ($j = 1; $j < $wp_query->found_posts; $j++) {
				                		if ( $j % (3*$post_rows) == 0 && $j < $post_shows ){
					                    	$c++;
					                    	print '<li data-target="#carousel-featured-'.$this->id.'" data-slide-to="'.$c.'" class="bullet"></li> '; 
					                    }	
				                	}
				                ?>
				            </ol>
			            <?php endif;?>
		            <?php endif;?>
		        </div>
		        <div class="featured-wrapper">
		            <div class="container">
		            	<div class="row">
		                     <div class="carousel-inner">
		                       	<?php
		                       	$i=0;
		                       	$wp_query = new WP_Query( $posts_query );
		                       	if( $wp_query->have_posts() ) : 
			                       	while ( $wp_query->have_posts() ) : $wp_query->the_post();
			                       	$i++;
			                       	?>
			                       	<?php if( $i ==1 ):?>
			                       		<div class="item active">
			                       	<?php endif;?>	
			                                <div id="video-featured-<?php the_ID()?>" class="col-sm-4 <?php print $this->id; ?>-<?php print get_the_ID();?>">
				                                <?php 
				                                	if(has_post_thumbnail()){
				                                		print '<a title="'.get_the_title().'" href="'.get_permalink(get_the_ID()).'">'. get_the_post_thumbnail(null,'video-featured', array('class'=>'img-responsive')) .'</a>';
				                                	}
				                                ?>				                                
			                                    <div class="feat-item">
			                                        <div class="feat-info post post-info-<?php print get_the_ID();?>">
			                                        	<div class="post-header">
				                                            <h3><a title="<?php the_title();?>" href="<?php the_permalink();?>"><?php the_title();?></a></h3>
				                                            <span class="post-meta">
				                                            	<i class="fa fa-clock-o"></i> <?php print get_the_date('F j ,Y');?>
				                                            </span>
			                                            </div>
			                                        </div>
													
			                                    </div>
			                                </div> 
				                    <?php
				                    if ( $i % (3*$post_rows) == 0 && $i < $post_shows ){
				                    	?></div><div class="item"><?php 
				                    } 	             
			                       	endwhile;
			                      ?></div><?php 
		                       	endif;
		                       	?> 
		                        </div>
		                  </div>
		            </div>
		        </div>
			</div><!-- /#carousel-featured -->
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
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['post_category'] = strip_tags( $new_instance['post_category'] );
		$instance['post_tag'] = strip_tags( $new_instance['post_tag'] );
		$instance['date'] = strip_tags( $new_instance['date'] );
		$instance['post_orderby'] = strip_tags( $new_instance['post_orderby'] );
		$instance['post_order'] = strip_tags( $new_instance['post_order'] );
		$instance['post_shows'] = strip_tags( $new_instance['post_shows'] );
		$instance['ids'] = strip_tags( $new_instance['ids'] );
		$instance['post_sticky'] = strip_tags( $new_instance['post_sticky'] );
		$instance['rows'] = strip_tags( $new_instance['rows'] );
		$instance['auto'] = strip_tags( $new_instance['auto'] );
		return $instance;		
		
	}
	function form( $instance ){
		$defaults = array( 'title' => __('Featured Posts', 'mars'));
		$instance['post_category'] = isset( $instance['post_category'] ) ? $instance['post_category'] : null;
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'mars'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo ( isset( $instance['title'] ) ? $instance['title']: null ); ?>" style="width:100%;" />
		</p>		
		<p>  
		    <label for="<?php echo $this->get_field_id( 'post_category' ); ?>"><?php _e('Post Category:', 'mars'); ?></label>
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
							'class'              => 'regular-text mars-dropdown',
			    		)
		    		);
		    	?>
		</p>
		<p>  
		    <label for="<?php echo $this->get_field_id( 'post_tag' ); ?>"><?php _e('Post Tag:', 'mars'); ?></label>
		    <input placeholder="<?php _e('Eg: tag1,tag2,tag3','mars');?>" id="<?php echo $this->get_field_id( 'post_tag' ); ?>" name="<?php echo $this->get_field_name( 'post_tag' ); ?>" value="<?php echo ( isset( $instance['post_tag'] ) ? $instance['post_tag'] : null ); ?>" style="width:100%;" />
		</p>
		<p>  
		    <label for="<?php echo $this->get_field_id( 'date' ); ?>"><?php _e('Date (Show posts associated with a certain time and date period, (yy-mm-dd)):', 'mars'); ?></label>
		    <input class="vt-datetime" id="<?php echo $this->get_field_id( 'date' ); ?>" name="<?php echo $this->get_field_name( 'date' ); ?>" value="<?php echo ( isset( $instance['date'] ) ? $instance['date'] : null ); ?>" style="width:100%;" />
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
		    <label for="<?php echo $this->get_field_id( 'ids' ); ?>"><?php _e('Post IDs:', 'mars'); ?></label>
		    <input id="<?php echo $this->get_field_id( 'ids' ); ?>" name="<?php echo $this->get_field_name( 'ids' ); ?>" value="<?php echo ( isset( $instance['ids'] ) ) ? (int)$instance['ids'] : null; ?>" style="width:100%;" />
		</p>										 
		<p>  
		    <label for="<?php echo $this->get_field_id( 'post_shows' ); ?>"><?php _e('Shows:', 'mars'); ?></label>
		    <input id="<?php echo $this->get_field_id( 'post_shows' ); ?>" name="<?php echo $this->get_field_name( 'post_shows' ); ?>" value="<?php echo (isset( $instance['post_shows'] )) ? (int)$instance['post_shows'] : 16; ?>" style="width:100%;" />
		</p>
		<p>  
		    <label for="<?php echo $this->get_field_id( 'post_sticky' ); ?>"><?php _e('Show Sticky Posts:', 'mars'); ?></label>
		    <input type="checkbox" id="<?php echo $this->get_field_id( 'post_sticky' ); ?>" name="<?php echo $this->get_field_name( 'post_sticky' ); ?>" <?php  print isset( $instance['post_sticky'] ) && $instance['post_sticky'] =='on' ? 'checked' : null;?> />
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
}