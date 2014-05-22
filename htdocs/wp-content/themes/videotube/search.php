<?php if( !defined('ABSPATH') ) exit;?>
<?php get_header(); ?>
	<div class="container">
		<div class="row">
			<div class="col-sm-8">
            	<div class="section-header">
            		<?php global $wp_query;?>
            		<?php if( $wp_query->found_posts > 1 ):?>
                    	<h3><?php printf( __('About %s results','mars') , $wp_query->found_posts )?></h3>
                    <?php else:?>
                    	<h3><?php printf( __('About %s result','mars') , $wp_query->found_posts )?></h3>
                    <?php endif;?>
                    <?php ?>
                </div>
				<?php if( have_posts() ):?>
				<div class="row video-section meta-maxwidth-230">
					<?php 			
					while ( have_posts() ) : the_post();
					?>
					<div class="col-sm-4 col-xs-6 item">
						<?php 
							if( has_post_thumbnail() ){
								print '<a title="'.get_the_title().'" href="'.get_permalink().'">' . get_the_post_thumbnail(null,'video-lastest', array('class'=>'img-responsive')) .'</a>';
							}
						?>
						<h3><a title="<?php the_title();?>" href="<?php the_permalink();?>"><?php the_title();?></a></h3>
						<?php do_action('mars_video_meta');?>
					</div>
					<?php endwhile;?>
				</div>
                <?php if ( function_exists( 'page_navi' ) ) page_navi(array('elm_class'=>'pagination','current_class'=>'active','current_format'=>'<a>%d</a>')); ?>
                <?php else:?>
                	<div class="alert alert-info"><?php _e('Nothing Found.','mars')?></div>
                <?php endif;?>
			</div>
			<?php get_sidebar();?>
		</div><!-- /.row -->
	</div><!-- /.container -->
<?php get_footer();?>