<?php if( !defined('ABSPATH') ) exit;?>
<?php get_header();
global $mars;
global $post;
the_post();
$layout = get_post_meta($post->ID,'layout',true) ? get_post_meta($post->ID,'layout',true) : 'small';
?>
	<?php if( $layout == 'large' ):?>
		<div class="video-wrapper">
			<div class="container">
				<div class="video-info">
	                <h1><?php the_title();?></h1>
	                <span class="views"><i class="fa fa-eye"></i><?php print get_post_meta($post->ID,'count_viewed',true);?></span>
	                <a href="javascript:void(0)" class="likes-dislikes" action="dislike" id="<?php print $post->ID;?>"><span class="dislikes"><i class="fa fa-thumbs-down"></i><label class="dislikevideo<?php print $post->ID;?>"><?php if(function_exists('mars_get_dislike_count')) { print mars_get_dislike_count($post->ID); }?></label></span></a>
	                <a href="javascript:void(0)" class="likes-dislikes" action="like" id="<?php print $post->ID;?>"><span class="likes"><i class="fa fa-thumbs-up"></i><label class="likevideo<?php print $post->ID;?>"><?php if(function_exists('mars_get_like_count')) { print mars_get_like_count($post->ID); } ?></label></span></a>
	            </div>          
	            <?php do_action('mars_showing_videoframe');?>
			</div>
		</div>
	<?php endif;?>
	<div class="container">
		<div class="row">
			<div class="col-sm-8">
				<?php if( $layout == 'small' ):?>
	            	<div class="video-info small">
	                    <h1><?php the_title();?></h1>
		                <span class="views"><i class="fa fa-eye"></i><?php print get_post_meta($post->ID,'count_viewed',true);?></span>
		                <a href="javascript:void(0)" class="likes-dislikes" action="dislike" id="<?php print $post->ID;?>"><span class="dislikes"><i class="fa fa-thumbs-down"></i><label class="dislikevideo<?php print $post->ID;?>"><?php if(function_exists('mars_get_dislike_count')) { print mars_get_dislike_count($post->ID); }?></label></span></a>
		                <a href="javascript:void(0)" class="likes-dislikes" action="like" id="<?php print $post->ID;?>"><span class="likes"><i class="fa fa-thumbs-up"></i><label class="likevideo<?php print $post->ID;?>"><?php if(function_exists('mars_get_like_count')) { print mars_get_like_count($post->ID); } ?></label></span></a>
	                </div>
					<?php do_action('mars_showing_videoframe');?>			
				<?php endif;?>
				<div class="alert" style="display:none"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>				
            	<div class="row video-options">
                    <div class="col-xs-3">
                        <a href="javascript:void(0)" class="option comments-scrolling">
                            <i class="fa fa-comments"></i>
                            <span class="option-text"><?php _e('Comments','mars')?></span>
                        </a>
                    </div>
                    
                    <div class="col-xs-3">
                        <a href="javascript:void(0)" class="option">
                            <i class="fa fa-share"></i>
                            <span class="option-text"><?php _e('Share','mars')?></span>
                        </a>
                    </div>
                    
                    <div class="col-xs-3">
                        <a href="javascript:void(0)" class="option likes-dislikes" action="like" id="<?php print $post->ID;?>" id="buttonlike" video="<?php print $post->ID;?>">
                            <i class="fa fa-thumbs-up"></i>
                            <span class="option-text likes-dislikes">
                            	<label class="likevideo<?php print $post->ID;?>"><?php if(function_exists('mars_get_like_count')) { print mars_get_like_count($post->ID); } ?></label>
                            </span>
                        </a>
                    </div>                   
                    <div class="col-xs-3">
                        <a href="javascript:void(0)" class="option likes-dislikes" action="dislike" id="<?php print $post->ID;?>" id="buttondislike" video="<?php print $post->ID;?>">
                            <i class="fa fa-thumbs-down"></i>
                            <span id="dislike" class="option-text likes-dislikes">
                            	<label class="dislikevideo<?php print $post->ID;?>"><?php if(function_exists('mars_get_dislike_count')) { print mars_get_dislike_count($post->ID); }?></label>
                            </span>
                        </a>
                    </div>
                </div>
				<div class="video-details">
					<?php 
						$author = get_the_author_meta('display_name', mars_get_post_authorID($post->ID));
					?>
                    <span class="date"><?php printf('Published on %s', get_the_date('M d, Y') );?></span>
                    <div class="post-entry"><?php the_content();?></div>
                    <span class="meta"><?php print the_terms( $post->ID, 'categories', '<span class="meta-info">'.__('Category','mars').'</span> ', ' ' ); ?></span>
                    <span class="meta"><?php print the_terms( $post->ID, 'video_tag', '<span class="meta-info">'.__('Tag','mars').'</span> ', ' ' ); ?></span>
                </div>
				<?php dynamic_sidebar('mars-video-single-below-sidebar');?>
				<?php 
					if ( comments_open() || get_comments_number() ) {
						comments_template();
					}
				?>
			</div>
			<?php get_sidebar();?>
		</div><!-- /.row -->
	</div><!-- /.container -->
<?php get_footer();?>
