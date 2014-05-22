<?php if( !defined('ABSPATH') ) exit;?>
<?php get_header();?>
	<div class="container">
		<div class="row">
			<div class="col-sm-8">
            	<div class="section-header">
                   <h3><?php _e('Archive','mars');?></h3>
                </div>				
				<?php if( have_posts() ) : ?>
				<?php while ( have_posts() ) : the_post(); ?>
					<?php get_template_part('loop','post');?>
				<?php endwhile;?>
                <ul class="pager">
                	<?php posts_nav_link(' ','<li class="previous">'.__('&larr; Older','mars').'</a></li>',' <li class="next">'.__('Newer &rarr;','mars').'</a></li>'); ?>
                </ul>
				<?php else:?>
					<h3><?php _e('Not found.','mars');?></h3>
				<?php endif;?>
			</div>
			<?php get_sidebar();?>
		</div><!-- /.row -->
	</div><!-- /.container -->			
<?php get_footer();?>