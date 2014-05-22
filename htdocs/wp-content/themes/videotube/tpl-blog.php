<?php if( !defined('ABSPATH') ) exit;?>
<?php
/**
 * Template Name: Blog Page
 */
get_header();?>
	<div class="container">
		<div class="row">
			<div class="col-sm-8">
				<?php 
					$paged = get_query_var('paged') ? get_query_var('paged') : 1;
					$wp_query = new WP_Query(array('post_type'=>'post', 'paged'=>$paged));
					if( $wp_query->have_posts() ) : while ( $wp_query->have_posts() ) : $wp_query->the_post();
							get_template_part('loop','post');
						endwhile;
						?>
		                <ul class="pager">
		                	<?php posts_nav_link(null,'<li class="previous">'.__('&larr; Older','mars').'</a></li>','<li class="next">'.__('Newer &rarr;','mars').'</a></li>'); ?>
		                </ul>						
						<?php 
					else:
						print  '<h3>'.__('Not found.','mars').'</h3>';
					endif;?>
			</div>
			<?php get_sidebar();?>
		</div><!-- /.row -->
	</div><!-- /.container -->
<?php get_footer();?>