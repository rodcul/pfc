<?php if( !defined('ABSPATH') ) exit;?>
<?php 
/**
 * Template Name: Main Video Page
 */
?>
<?php get_header();?>
	<?php dynamic_sidebar('mars-featured-videos-sidebar');?>
	<div class="container">
		<div class="row">
			<div class="col-sm-8">
				<?php dynamic_sidebar('mars-home-videos-sidebar');?>
			</div><!-- /.video-section -->
			<?php get_sidebar();?>
		</div><!-- /.row -->
	</div><!-- /.container -->
<?php get_footer();?>