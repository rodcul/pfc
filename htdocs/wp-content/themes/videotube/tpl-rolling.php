<?php 
/**
 * Template Name: Rolling Page
 */
?>
<?php if( !defined('ABSPATH') ) exit;?>
<?php get_header();?>
	<div class="container">
		<div class="row">
			<div class="col-sm-8">
                <div class="carousel slide video-section">
                    <div class="gaming-wrapper loading-wrapper">
                    	<?php 
                    		global $post;
                    		$current_indexpage = $post->ID;
                    		$post_type = get_post_meta($current_indexpage,'videotube_post_type',true) ? get_post_meta($current_indexpage,'videotube_post_type',true) : 'video';
                    		$paged = get_query_var('paged') ? get_query_var('paged') : 1;
                    		$wp_query = new WP_Query( array('post_type'=> $post_type ,'paged'=>$paged) );
                    		if( $wp_query->have_posts() ) : while ( $wp_query->have_posts() ) : $wp_query->the_post();
                    	?>
                    	<div id="<?php the_ID();?>" <?php post_class('row');?>>
                    		<div class="col-sm-5 item list">
                    			<?php if( $post_type == 'video' ):?>
                    			<div class="item-img">
                    			<?php endif;?>
				                	<?php 
				                		if( has_post_thumbnail() ){
				                			print '<a href="'.get_permalink(get_the_ID()).'">'. get_the_post_thumbnail(NULL,'video-category-featured', array('class'=>'img-responsive')) .'</a>';
				                		}
				                	?>
				                	<?php if( $post_type == 'video' ):?>
				                	<a href="<?php echo get_permalink(get_the_ID()); ?>"><div class="img-hover"></div></a>
		                		</div>
		                		<?php endif;?>
                    		</div>
                    		<div class="col-sm-7 item list">
                    			<?php if( $post_type == 'post' ):?><div class="post-header"><?php endif;?>
                    			<h3><a href="<?php the_permalink();?>"><?php the_title();?></a></h3>
                    			<?php if( $post_type == 'post' ):?>
                    				<?php print apply_filters('mars_blog_metas',null);?>
                    				</div>
                    			<?php endif;?>
                    			<?php if( $post_type == 'video' ):?>
                    				<?php print apply_filters('mars_video_meta',null);?>
                    				<?php the_excerpt();?>
                    			<?php endif;?>
                    			<?php if( $post_type == 'video' ):?>
                    			<p><a href="<?php the_permalink();?>"><i class="fa fa-play-circle"></i><?php _e('Watch Video','mars')?></a></p>
                    			<?php else:?>
                    				<div class="post-entry">
                    					<?php the_excerpt();?>
                    					<a href="<?php the_permalink();?>" class="readmore"><?php _e('Read More','mars');?></a>
                    				</div>
                    			<?php endif;?>
                    		</div>
                    	</div>
                    	<?php endwhile;?>
                    	<button style="display: none;" type="button" class="btn btn-lg loading-more-icon"><img src="<?php print MARS_THEME_URI;?>/img/ajax-loader.gif"></button>
                    	<div id="videotube-loading-rolling" post_type="<?php print $post_type;?>" current_indexpage="<?php print $current_indexpage;?>" next_paged="no" paged="<?php print $paged;?>" post_id=<?php print get_the_ID();?> class="old-post-field-<?php print get_the_ID();?>"></div>
		                <?php else:?>
		                	<div class="alert alert-info"><?php _e('Oop...nothing.','mars')?></div>
		                <?php endif;?>
                    </div>
                </div>
			</div><!-- /.video-section -->
			<?php get_sidebar();?>
		</div><!-- /.row -->
	</div><!-- /.container -->
<script type="text/javascript">
(function($) {
  "use strict";
	jQuery(document).ready(function(){
		//$.removeCookie('loading');
		$(window).scroll(function() {
			//$.cookie('loading', 'yes');
			//var loading = $.cookie('loading');
			if( $.cookie('loading') == undefined ){
				$.cookie('loading','yes');
			}
		    if($(window).scrollTop() > $(document).height()/2 ) {
		    	vt_loading_more( $.cookie('loading') );
		    }
		})
	});
})(jQuery);
</script>	
<?php get_footer();?>