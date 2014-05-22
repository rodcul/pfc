<?php if( !defined('ABSPATH') ) exit;?>
<?php
/**
 * Template Name: Page Full Width
 */
?>
<?php get_header(); ?>
	<div class="container">
		<div class="row">
		 <div <?php post_class();?>>
			<?php the_post();?>
                    <?php get_template_part('content','page');?>
                </div><!-- /.post -->
                
				<?php dynamic_sidebar('mars-blog-single-bellow-content-sidebar');?>
			<?php 
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
			?>	
		</div><!-- /.row -->
	</div><!-- /.container -->
<?php get_footer();?>