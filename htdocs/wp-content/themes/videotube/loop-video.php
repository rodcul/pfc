	<div class="col-sm-4 col-xs-6 item">
		<div class="item-img">
		<?php 
			if( has_post_thumbnail() ){
				print '<a href="'.get_permalink(get_the_ID()).'">'. get_the_post_thumbnail(null,'video-lastest', array('class'=>'img-responsive')) . '</a>';
			}
		?>
			<a href="<?php echo get_permalink(get_the_ID()); ?>"><div class="img-hover"></div></a>
		</div>
		<h3><a href="<?php the_permalink();?>"><?php the_title();?></a></h3>
		<?php print apply_filters('mars_video_meta',null);?>
	</div>
