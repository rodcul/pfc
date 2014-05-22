<?php if( !defined('ABSPATH') ) exit;?>
<?php get_header();
global $post, $videotube;
$guestlike = isset( $videotube['guestlike'] ) ? $videotube['guestlike'] : 1;
the_post();
$layout = get_post_meta($post->ID,'layout',true) ? get_post_meta($post->ID,'layout',true) : 'small';
?>
	<?php if( $layout == 'large' ):?>
		<div class="video-wrapper">
			<div class="container">
				<div class="video-info">
	                <h1><?php the_title();?></h1>
	                <span class="views"><i class="fa fa-eye"></i><?php print get_post_meta($post->ID,'count_viewed',true);?></span>
	                <a href="javascript:void(0)" class="likes-dislikes" action="like" id="<?php print $post->ID;?>"><span class="likes"><i class="fa fa-thumbs-up"></i><label class="likevideo<?php print $post->ID;?>"><?php if(function_exists('mars_get_like_count')) { print mars_get_like_count($post->ID); } ?></label></span></a>
	            </div>          
                <div class="videoWrapper player">
                	<?php print do_shortcode( apply_filters('videoframe',null) );?>
                </div>
                <div id="lightoff"></div>
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
		                <a <?php if( !get_current_user_id() ):?>data-toggle="modal" data-target="#loginmodal" class="option"<?php endif;?> href="javascript:void(0)" class="likes-dislikes" action="like" id="<?php print $post->ID;?>"><span class="likes"><i class="fa fa-thumbs-up"></i><label class="likevideo<?php print $post->ID;?>"><?php if(function_exists('mars_get_like_count')) { print mars_get_like_count($post->ID); } ?></label></span></a>
	                </div>
	                <div class="videoWrapper player">
	                	<?php print do_shortcode( apply_filters('videoframe',null) );?>
	                </div>     
	                <div id="lightoff"></div>
				<?php endif;?>
				<?php 
					$defaults = array(
						'before' => '<ul class="pagination">',
						'after' => '</ul>',
						'before_link' => '<li>',
						'after_link' => '</li>',
						'current_before' => '<li class="active">',
						'current_after' => '</li>',
						'previouspagelink' => '&laquo;',
						'nextpagelink' => '&raquo;'
					);  
					bootstrap_link_pages( $defaults );
				?>				
            	<div class="row video-options">
                    <div class="col-xs-3">
                        <a href="javascript:void(0)" class="option comments-scrolling">
                            <i class="fa fa-comments"></i>
                            <span class="option-text"><?php _e('Comments','mars')?></span>
                        </a>
                    </div>
                    
                    <div class="col-xs-3">
                        <a href="javascript:void(0)" class="option share-button" id="off">
                            <i class="fa fa-share"></i>
                            <span class="option-text"><?php _e('Share','mars')?></span>
                        </a>
                    </div>
                    
                    <div class="col-xs-3">
                        <a <?php if( !get_current_user_id() && $guestlike == 0 ):?>data-toggle="modal" data-target="#loginmodal" class="option"<?php endif;?> class="option likes-dislikes" href="javascript:void(0)" action="like" id="<?php print $post->ID;?>" id="buttonlike" video="<?php print $post->ID;?>">
                            <i class="fa fa-thumbs-up"></i>
                            <span class="option-text likes-dislikes">
                            	<label class="likevideo<?php print $post->ID;?>"><?php if(function_exists('mars_get_like_count')) { print mars_get_like_count($post->ID); } ?></label>
                            </span>
                        </a>
                        <?php if( $guestlike == 0 && !get_current_user_id() ):?>
                        <!-- Login Modal -->
						<div class="modal fade" id="loginmodal" tabindex="-1" role="dialog" aria-labelledby="loginmodal-label" aria-hidden="true">
						  <div class="modal-dialog">
						    <div class="modal-content">
						      <div class="modal-header">
						        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						        <h4 class="modal-title" id="loginmodal-label"><?php _e('Please login to continue.','mars');?></h4>
						      </div>
						      <div class="modal-body">
						      	<?php wp_login_form();?>
						      </div>
						    </div>
						  </div>
						</div>                        
                        <!-- End modal -->
                        <?php endif;?>
                    </div>
                    <div class="col-xs-3">
						<!-- LIGHT SWITCH -->
						<a href="javascript:void(0)" class="option switch-button">
                            <i class="fa fa-lightbulb-o"></i>
							<span class="option-text"><?php _e('Turn off Light','mars')?></span>
                        </a>	
                    </div>
                </div>	
				<!-- IF SHARE BUTTON IS CLICKED SHOW THIS -->
				<?php
					$post_data = mars_get_post_data($post->ID); 
					$url_image = has_post_thumbnail() ? wp_get_attachment_url( get_post_thumbnail_id($post->ID)) : null;
					$current_url = get_permalink( $post->ID );
					$current_title = $post_data->post_title;
					$current_trimHTML_Content = esc_html($post_data->post_content);
				?>
				<div class="row social-share-buttons">
					<div class="col-xs-12">
						<a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php print $current_url;?>"><img src="<?php echo get_template_directory_uri(); ?>/img/facebook.png" alt="" /></a>
						<a target="_blank" href="https://twitter.com/home?status=<?php print $current_url;?>"><img src="<?php echo get_template_directory_uri(); ?>/img/twitter.png" alt="" /></a>
						<a target="_blank" href="https://plus.google.com/share?url=<?php print $current_url;?>"><img src="<?php echo get_template_directory_uri(); ?>/img/googleplus.png" alt="" /></a>
						<a target="_blank" href="https://pinterest.com/pin/create/button/?url=<?php print $current_url;?>&media=<?php print $url_image;?>&description=<?php print $current_trimHTML_Content;?>"><img src="<?php echo get_template_directory_uri(); ?>/img/pinterest.png" alt="" /></a>
						<a target="_blank" href="http://www.reddit.com/submit?url"><img src="<?php echo get_template_directory_uri(); ?>/img/reddit.png" alt="" /></a>
						<a target="_blank" href="https://www.linkedin.com/shareArticle?mini=true&url=<?php print $current_url;?>&title=<?php print $current_title;?>&summary=<?php print $current_trimHTML_Content;?>&source=<?php print home_url();?>"><img src="<?php echo get_template_directory_uri(); ?>/img/linkedin.png" alt="" /></a>
						<a target="_blank" href="http://www.odnoklassniki.ru/dk?st.cmd=addShare&st._surl=<?php print $current_url;?>&title=<?php print $current_title;?>"><img src="<?php echo get_template_directory_uri(); ?>/img/odnok.png" alt="" /></a>
						<a target="_blank" href="http://vkontakte.ru/share.php?url=<?php print $current_url;?>"><img src="<?php echo get_template_directory_uri(); ?>/img/vkontakte.png" alt="" /></a>
						<a href="mailto:?Subject=<?php print $current_title;?>&Body=<?php printf( __('I saw this and thought of you! %s','mars'), $current_url );?>"><img src="<?php echo get_template_directory_uri(); ?>/img/email.png" alt="" /></a>
					</div>
				</div>
				<div class="video-details">
					<?php 
						$author = get_the_author_meta('display_name', mars_get_post_authorID($post->ID));
					?>
					<span class="date"><?php printf(__('Published on %s by %s','mars'), get_the_date('M d, Y'), '<a href="'.get_author_posts_url(get_the_author_meta('ID')).'">'.$author.'</a>' );?></span>
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
