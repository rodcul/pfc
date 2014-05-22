<?php
/**
 * VideoTube Main Videos Widget
 * Add Main Videos Widget in Home Page, 2-3 column a other options is supported.
 * @author 		Toan Nguyen
 * @category 	Core
 * @version     1.0.0
 */
if( !defined('ABSPATH') ) exit;
if( !function_exists('Mars_MainVideos_Widgets') ){
	function Mars_MainVideos_Widgets() {
		register_widget('Mars_MainVideos_Widgets_Class');
	}
	add_action('widgets_init', 'Mars_MainVideos_Widgets');
}
class Mars_MainVideos_Widgets_Class extends WP_Widget{
	function Mars_MainVideos_Widgets_Class(){
		$widget_ops = array( 'classname' => 'mars-mainvideo-widgets', 'description' => __('VT Main Videos Widget', 'wpo') );
		
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'mars-mainvideo-widgets' );
		
		$this->WP_Widget( 'mars-mainvideo-widgets', __('VT Main Videos Widget', 'wpo'), $widget_ops, $control_ops );		
	}
	function widget($args, $instance){
		extract( $args );
		$title = apply_filters('widget_title', $instance['title'] );
		$video_category = isset( $instance['video_category'] ) ? $instance['video_category'] : null;
		$video_tag = isset( $instance['video_tag'] ) ? $instance['video_tag'] : null;
		$video_date = isset( $instance['date'] ) ? $instance['date'] : null;
		$video_orderby = isset( $instance['video_orderby'] ) ? $instance['video_orderby'] : 'ID';
		$video_order = isset( $instance['video_order'] ) ? $instance['video_order'] : 'DESC';
		$widget_column = isset( $instance['widget_column'] ) ? $instance['widget_column'] : 3;
		$video_rows = isset( $instance['rows'] ) ? (int)$instance['rows'] : 1;
		$autoplay = isset( $instance['auto'] ) ? $instance['auto'] : null;		
		$video_shows = isset( $instance['video_shows'] ) ? (int)$instance['video_shows'] : 16;  
		$i=0;
		$videos_query = array(
			'post_type'	=>	'video',
			'showposts'	=>	$video_shows,
			'posts_per_page'	=>	$video_shows
		);
                       	
		if( $video_category ){
			$videos_query['tax_query'] = array(
				array(
				'taxonomy' => 'categories',
				'field' => 'id',
				'terms' => $video_category
				)		                       		
			);
		}
		if( $video_tag ){
			$videos_query['tax_query'][] = array(
				'taxonomy' => 'video_tag',
				'field' => 'slug',
				'terms' => explode(",", $video_tag)
			);
		}
		if( $video_orderby ){
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
		if( $video_order ){
			$videos_query['order']	=	$video_order;
		}
		if( $video_date ){
			$dateime = explode("-", $video_date);
			$videos_query['date_query'] = array(
				array(
					'year'  => isset( $dateime[0] ) ? $dateime[0] : null,
					'month' => isset( $dateime[1] ) ? $dateime[1] : null,
					'day'   => isset( $dateime[2] ) ? $dateime[2] : null,
				)
			);
		}				
		$wp_query = new WP_Query( $videos_query );
		$colum = ( $widget_column == 3 ) ? 3 : 1;
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
                        	<h3><i class="fa fa-play"></i> <?php print $title;?></h3>
                        <?php endif;?>
                        <?php if( $widget_column != 2 ):?>
				            <?php if( $video_shows >= 3 || $video_shows == -1 ):?>
					            <?php if( $video_shows >= $wp_query->post_count && $video_shows > $colum*$video_rows ):?>
						            <ol class="carousel-indicators section-nav">
						            	<li data-target="#carousel-latest-<?php print $this->id; ?>" data-slide-to="0" class="bullet active"></li>
						                <?php 
						                	$c = 0;
						                	for ($j = 1; $j < $wp_query->post_count; $j++) {
						                		if ( $j % ($colum*$video_rows) == 0 && $j < $video_shows ){
							                    	$c++;
							                    	print '<li data-target="#carousel-latest-'.$this->id.'" data-slide-to="'.$c.'" class="bullet"></li> '; 
							                    }	
						                	}
						                ?>				                
						            </ol>
					            <?php endif;?>
				            <?php endif;?>
	                    <?php else:?>
	                    	<?php if( $instance['view_more'] ):?>
								<div class="section-nav">
									<a href="<?php print $instance['view_more'];?>" class="viewmore"><?php _e('View More','mars');?> <i class="fa fa-angle-double-right"></i></a>
								</div>
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
				                       		<div id="video-main-<?php print $this->id; ?>-<?php the_ID();?>" class="col-sm-4 col-xs-6 item video-<?php print get_the_ID();?>">
				                                <div class="item-img">
													<?php 
														if(has_post_thumbnail()){
															print '<a title="'.get_the_title().'" href="'.get_permalink(get_the_ID()).'">'. get_the_post_thumbnail(null,'video-lastest', array('class'=>'img-responsive')) .'</a>';
														}
													?>
													<a href="<?php echo get_permalink(get_the_ID()); ?>"><div class="img-hover"></div></a>
												</div>
	                                            <h3><a title="<?php the_title();?>" href="<?php the_permalink();?>"><?php the_title();?></a></h3>
												<?php print apply_filters('mars_video_meta', null);?>
		                                     </div> 
					                    <?php
					                    if ( $i % (3*$video_rows) == 0 && $i < $video_shows ){
					                    //if ( $i % ( $widget_column*2 ) == 0 ){
					                    	?></div><div class="item"><?php 
					                    } 	             
				                       	endwhile;
				                      ?></div><?php 
			                       	endif;
			                       	?> 
			                        </div>
	                            </div>
	                    </div>
                   	<?php elseif( $widget_column == 2 ):?>
                   		<?php if( $wp_query->have_posts() ) : while ( $wp_query->have_posts() ) : $wp_query->the_post();?>                	
						<div class="col-sm-6 item">
							<div class="item-img">
							<?php 
								if(has_post_thumbnail()){
									print '<a href="'.get_permalink().'" title="'.get_the_title().'">'. get_the_post_thumbnail(null,'video-featured', array('class'=>'img-responsive')) . '</a>';
								}
                                ?>
								<a href="<?php echo get_permalink(get_the_ID()); ?>"><div class="img-hover"></div></a>
							</div>                                
							<h3><a href="<?php the_permalink();?>"><?php the_title();?></a></h3>
							<?php print apply_filters('mars_video_meta',null);?>
						</div>
						<?php endwhile;endif;?>
                    <?php else:?>
					<!-- 1 colum -->
                    <div class="gaming-wrapper">
                       	<div class="carousel-inner carousel-<?php print $this->id;?>">
	                       	<?php
	                       	if( $wp_query->have_posts() ) : 
		                       	while ( $wp_query->have_posts() ) : $wp_query->the_post();
		                       	$i++;
		                       	?>
		                       	<?php if( $i ==1 ):?>
		                       		<div class="item active">
		                       	<?php endif;?>
                                    <div class="row">
                                        <div class="col-sm-5 item list">
                                        	 <div class="item-img">
                                        	<?php 
                                        		if( has_post_thumbnail() ){
                                        			print '<a href="'.get_permalink().'" title="'.get_the_title().'">'. get_the_post_thumbnail(null,'video-category-featured', array('class'=>'img-responsive')) . '</a>';
                                        		}
                                        	?>
                                        		<a href="<?php echo get_permalink(get_the_ID()); ?>"><div class="img-hover"></div></a>
                                        	</div>                                        	
                                        </div>
                                        <div class="col-sm-7 item list <?php print $this->id; ?>-<?php print get_the_ID();?>">
                                            <h3><a title="<?php the_title();?>" href="<?php the_permalink();?>"><?php the_title();?></a></h3>
											<?php print apply_filters('mars_video_meta', null);?>
                                            <?php the_excerpt();?> 
                                            <p><a href="<?php the_permalink();?>"><i class="fa fa-play-circle"></i><?php _e('Watch Video','mars')?></a></p>
                                        </div>
                                    </div>
				                    <?php
				                    //if ( $i % 3 == 0 && $i < 18 ){
				                    if ( $i % $video_rows == 0 && $i < $video_shows ){
				                    	?></div><div class="item"><?php 
				                    } 
			                       	endwhile;
				                      ?></div>
				             <?php endif;?>
				             </div>
			         </div>
					<!-- end 1 colum -->
                    <?php endif;?>
                </div><!-- /#carousel-->
				<?php if( $autoplay == 'on' ):?>
				<script>
				(function($) {
				  "use strict";
					jQuery('#carousel-latest-<?php print $this->id; ?>').carousel({
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
		$instance['video_category'] = strip_tags( $new_instance['video_category'] );
		$instance['video_tag'] = strip_tags( $new_instance['video_tag'] );
		$instance['date'] = strip_tags( $new_instance['date'] );
		$instance['video_orderby'] = strip_tags( $new_instance['video_orderby'] );
		$instance['video_order'] = strip_tags( $new_instance['video_order'] );
		$instance['widget_column'] = strip_tags( $new_instance['widget_column'] );
		$instance['video_shows'] = strip_tags( $new_instance['video_shows'] );
		$instance['rows'] = strip_tags( $new_instance['rows'] );
		$instance['auto'] = strip_tags( $new_instance['auto'] );
		$instance['view_more'] = strip_tags( $new_instance['view_more'] );
		return $instance;		
		
	}
	function form( $instance ){
		$defaults = array( 'title' => __('Latest Videos', 'mars'));
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'mars'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
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
							'selected'           => isset( $instance['video_category'] ) ? $instance['video_category'] : null,
							'hierarchical'       => 0, 
							'name'               => $this->get_field_name( 'video_category' ),
							'id'                 => $this->get_field_id( 'video_category' ),
							'taxonomy'           => 'categories',
							'hide_if_empty'      => true,
							'class'              => 'postform mars-dropdown',
			    		)
		    		);
		    	?>
		</p>
		<p>  
		    <label for="<?php echo $this->get_field_id( 'video_tag' ); ?>"><?php _e('Video Tag:', 'mars'); ?></label>
		    <input placeholder="<?php _e('Eg: tag1,tag2,tag3','mars');?>" id="<?php echo $this->get_field_id( 'video_tag' ); ?>" name="<?php echo $this->get_field_name( 'video_tag' ); ?>" value="<?php echo ( isset( $instance['video_tag'] ) ? $instance['video_tag'] : null ); ?>" style="width:100%;" />
		</p>
		<p>  
		    <label for="<?php echo $this->get_field_id( 'date' ); ?>"><?php _e('Date (Show posts associated with a certain time and date period, (yy-mm-dd)):', 'mars'); ?></label>
		    <input class="vt-datetime" id="<?php echo $this->get_field_id( 'date' ); ?>" name="<?php echo $this->get_field_name( 'date' ); ?>" value="<?php echo ( isset( $instance['date'] ) ? $instance['date'] : null ); ?>" style="width:100%;" />
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
		    <label for="<?php echo $this->get_field_id( 'video_shows' ); ?>"><?php _e('Shows:', 'mars'); ?></label>
		    <input id="<?php echo $this->get_field_id( 'video_shows' ); ?>" name="<?php echo $this->get_field_name( 'video_shows' ); ?>" value="<?php echo (isset( $instance['video_shows'] )) ? (int)$instance['video_shows'] : 16; ?>" style="width:100%;" />
		</p>
		<p>  
		    <label for="<?php echo $this->get_field_id( 'rows' ); ?>"><?php _e('Rows (Available with 3 or 1 Column):', 'mars'); ?></label>
		    <input id="<?php echo $this->get_field_id( 'rows' ); ?>" name="<?php echo $this->get_field_name( 'rows' ); ?>" value="<?php echo (isset( $instance['rows'] )) ? (int)$instance['rows'] : 1; ?>" style="width:100%;" />
		</p>
		<p>  
		    <label for="<?php echo $this->get_field_id( 'auto' ); ?>"><?php _e('Auto Play:', 'mars'); ?></label>
		    <input type="checkbox" id="<?php echo $this->get_field_id( 'auto' ); ?>" name="<?php echo $this->get_field_name( 'auto' ); ?>" <?php  print isset( $instance['auto'] ) && $instance['auto'] =='on' ? 'checked' : null;?> />
		</p>		
		<p>  
		    <label for="<?php echo $this->get_field_id( 'view_more' ); ?>"><?php _e('View more link (available for 2 columns.):', 'mars'); ?></label>
		    <input id="<?php echo $this->get_field_id( 'view_more' ); ?>" name="<?php echo $this->get_field_name( 'view_more' ); ?>" value="<?php echo ( isset( $instance['view_more'] ) ? $instance['view_more'] : null ); ?>" style="width:100%;" />
		</p>
	<?php
	}
	function widget_video_column(){
		return array(
			'3'	=>	__('3 Columns - Carousel','mars'),
			'2'	=>	__('2 Columns','mars'),
			'1'	=>	__('1 Column - Carousel','mars'),
		);
	}
	function widget_video_order(){
		return array(
			'DESC'	=>	__('DESC','mars'),
			'ASC'	=>	__('ASC','mars')
		);
	}	
}