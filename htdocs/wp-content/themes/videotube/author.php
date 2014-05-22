<?php if( !defined('ABSPATH') ) exit;?>
<?php global $videotube;?>
<?php get_header();?>
	<div class="container">
		<div class="row">
			<div class="col-sm-8">
            	<div class="section-header">
            		<?php print apply_filters('videotube_author_header',null);?>
                </div>
				<?php if( have_posts() ) : ?>
					<?php print apply_filters('videotube_author_loop_before', null);?>
						<?php while ( have_posts() ) : the_post();?>
							<?php apply_filters('videotube_author_loop_content',null);?>
						<?php endwhile;?>
					<?php print apply_filters('videotube_author_loop_after', null);?>
					<?php 
						if( $videotube['enable_channelpage'] == 1 ){
							if ( function_exists( 'page_navi' ) ){
								page_navi(array('elm_class'=>'pagination','current_class'=>'active','current_format'=>'<a>%d</a>'));
							}
							else{
								?>
					                <ul class="pager">
					                	<?php posts_nav_link(' ','<li class="previous">'.__('&larr; Older','mars').'</a></li>',' <li class="next">'.__('Newer &rarr;','mars').'</a></li>'); ?>
					                </ul>								
								<?php 
							}
						}
						else{?>
			                <ul class="pager">
			                	<?php posts_nav_link(' ','<li class="previous">'.__('&larr; Older','mars').'</a></li>',' <li class="next">'.__('Newer &rarr;','mars').'</a></li>'); ?>
			                </ul>							
						<?php }
					?>
				<?php else:?>
					<h3><?php _e('Nothing found.','mars');?></h3>
				<?php endif;?>
			</div>
			<?php get_sidebar();?>
		</div><!-- /.row -->
	</div><!-- /.container -->
<?php get_footer();?>