<?php
/**
 * VideoTube Submit Video
 * Add [msubmit] shortcode to create the Submit Video page.
 * @author 		Toan Nguyen
 * @category 	Core
 * @version     1.0.0
 */
if( !defined('ABSPATH') ) exit;
if( !class_exists('Mars_ShortcodeSubmitVideo') ){
	class Mars_ShortcodeSubmitVideo {
		function __construct() {
			add_action('init', array($this,'add_shortcodes'));
			add_action('wp_ajax_mars_submit_video', array($this,'action_form'));
			add_action('wp_ajax_nopriv_mars_submit_video', array($this,'action_form'));
		}
		function add_shortcodes(){
			add_shortcode('videotube_upload', array($this,'videotube_upload'));
		}
		function videotube_upload( $attr, $content ){
			global $videotube;
			global $post;	
			$html = null;
			$video_type = isset( $videotube['video-type'] ) ? $videotube['video-type'] : null;
			if( !is_array( $video_type ) ){
				$video_type = (array)$video_type;
			}
			$submit_roles = isset( $videotube['submit_roles'] ) ? (array)$videotube['submit_roles'] : 'author';
			if( count( $submit_roles ) == 1 ){
				$submit_roles = (array)$submit_roles;
			}	
			//print_r($submit_roles);
			### 0 is not allow guest, 1 is only register.
			$submit_permission = isset( $videotube['submit_permission'] ) ? $videotube['submit_permission'] : 0;
			$user_id = get_current_user_id();
			$current_user_role = mars_get_user_role( $user_id );
			### Check if Admin does not allow Visitor submit the video.
			if( $submit_permission == 0 && !$user_id ){
				/**
				$html .= '
					<div class="alert alert-warning">'.sprintf( __('Please %s/%s to continue.','mars') ,'<a href="'.wp_login_url( get_permalink( $post->ID ) ).'">'.__('Login','mars').'</a>','<a href="'.wp_registration_url().'&redirect_to='.get_permalink( $post->ID ).'">'.__('Register','mars').'</a>').'</div>
				';	
				**/
				$html .= do_shortcode('[videotube_login]');			
			}
			//elseif( $submit_permission == 0 && !in_array( $current_user_role, $submit_roles) && $current_user_role != 'administrator'){
			elseif( $submit_permission == 0 && !in_array( $current_user_role, $submit_roles)){
				$html .= '
					<div class="alert alert-warning">'.__('You don\'t have the right permission to access this feature.','mars').'</div>
				';		
			}
			else{
				$categories_html = null;
				$categories = get_terms('categories', array('hide_empty'=>0));
				 if ( !empty( $categories ) && !is_wp_error( $categories ) ){
				 	$categories_html .= '<select name="categories-multiple" id="categories-multiple" class="multi-select-categories form-control" multiple="multiple">';
				 	foreach ( $categories as $category ){
				 		$categories_html .= '<option value="'.$category->term_id.'">'.$category->name.'</option>';
				 	}
				 	$categories_html .= '</select>';
				 }
				$html .= '
					<form role="form" action="" method="post" id="mars-submit-video-form" enctype="multipart/form-data">
					  <div class="form-group post_title">
					    <label for="post_title">'.__('Video Title','mars').'</label>
					    <span class="label label-danger">'.__('Required','mars').'</span>
					    <input type="text" class="form-control" name="post_title" id="post_title">
					    <span class="help-block"></span>
					  </div>
					  <div class="form-group post_content">
					    <label for="post_content">'.__('Video Description','mars').'</label>
					    <span class="label label-danger">'.__('Required','mars').'</span>
					    ';
						if( $videotube['submit_editor'] == 1 ){
							$html .= mars_get_editor('', 'post_content', 'post_content');	
						}
						else{
							$html .= '<textarea name="post_content" id="post_content" class="form-control" rows="3"></textarea>';
						}
					  $html .= '<span class="help-block"></span>';
					  $html .= '</div>
					  <div class="form-group">
					  	<label for="post_title">'.__('Video Type','mars').'</label>
					  	<span class="label label-danger">'.__('Required','mars').'</span>';
					  	if( in_array( 'videolink', $video_type ) ){
					  		$html .= '
							  	<div class="radio">
								  	<input checked type="radio" value="video_link_type" name="chb_video_type">'.__('Link','mars').'			  	
							  	</div>					  		
					  		';
					  	}
					  	if( in_array( 'embedcode', $video_type ) ){
					  		$html .= '
							  	<div class="radio">
								  	<input type="radio" value="embed_code_type" name="chb_video_type">'.__('Embed Code','mars').'					  	
							  	</div>					  		
					  		';
					  	}
					  	if( in_array( 'videofile', $video_type ) ){
					  		$html .= '
							  	<div class="radio">
								  	<input type="radio" value="file_type" name="chb_video_type">'.__('Upload file','mars').'					  	
							  	</div>					  		
					  		';
					  	}
					  $html .= '
					  </div>'; 
					  if( in_array( 'videolink', $video_type ) ){
					  	$html .= '
						  <div class="form-group video_url video-type video_link_type">
						    <label for="video_url">'.__('Video Link','mars').'</label>
						    <span class="label label-danger">'.__('Required','mars').'</span>
						    <input type="text" class="form-control" name="video_url" id="video_url" placeholder="Example: http://www.youtube.com/watch?v=X6pQ-pNSnRE">
						    <span class="help-block"></span>
						  </div>					  	
					  	';
					  }
					  if( in_array( 'embedcode', $video_type ) ){
					  	$html .= '
						  <div class="form-group embed_code_type video-type embed_code_type" style="display:none;">
						    <label for="video_url">'.__('Embed Code','mars').'</label>
						    <span class="label label-danger">'.__('Required','mars').'</span>
						    <textarea class="form-control" name="embed_code" id="embed_code"></textarea>
						    <span class="help-block"></span>
						  </div>					  	
					  	';
					  }
					  if( in_array( 'videofile', $video_type ) ){
					  	$html .= '
						  <div class="form-group video_file video-type file_type" style="display:none;">
						    <label for="video_url">'.__('Video File','mars').'</label>
						    <span class="label label-danger">'.__('Required','mars').'</span>
							<input type="file" type="text" class="form-control" name="video_file" id="video_file">
						    <span class="help-block"></span>
						  </div>					  	
					  	';
					  }
					  $html .= '
					  <div class="form-group video_thumbnail"> 	
					  	<label for="video_url">'.__('Video Preview Image','mars').'</label>
					  	<span class="label label-info">'.__('This image is required if you submit an embed code or a video file.','mars').'</span>
					  	<input type="file" type="text" class="form-control" name="video_thumbnail" id="video_thumbnail">
					  	<span class="help-block"></span>				    
					  </div>
					  <div class="form-group">
					    <label for="key">'.__('Video Tag','mars').'</label>
					    <input type="text" class="form-control" name="video_tag" id="video_tag">
					  </div>
					  <div class="form-group categories-video">
					    <label for="category">'.__('Category','mars').'</label>';
					    $html .= $categories_html;
					  $html .= '</div>';
					  $videolayout = isset( $videotube['videolayout'] ) ? $videotube['videolayout'] : 'yes';
					  if( $videolayout == 'yes' ){
						$html .= '
	 						<div class="form-group layout"> 	
							  	<label for="layout">'.__('Layout','mars').'</label>';
									if( function_exists( 'mars_videolayout' ) ){
										$i = 0;
										foreach ( mars_videolayout() as $key=>$value ){
											$i++;
											//$html .= '<option value="'.$key.'">'.$value.'</option>';
											$html .= '<div class="radio">';
												$html .= '<input '. ( $i==1 ? 'checked' : null ) .' type="radio" name="layout" value="'.$key.'">' . $value;
											$html .= '</div>';
										}
									}
							  	$html .= '
							  	<span class="help-block"></span>				    
						  	</div>						
						';					  	
					  }
					  ### Recaptcha
					  if( $videotube['submit_captcha'] ){
					  	$error = null;
					  	if( !function_exists('recaptcha_check_answer') ){
					  		$html .= '<div class="alert alert-danger">'.__('ERROR: Please install recaptchalib Class plugin.','mars').'</div>';
					  	}
					  	elseif( empty( $videotube['public_key'] ) ){
					  		$html .= '<div class="alert alert-danger">'.__('ERROR: Please contact your Administrator for providing Public Key to continue.','mars').'</div>';
					  	}
					  	elseif( empty( $videotube['private_key'] ) ){
					  		$html .= '<div class="alert alert-danger">'.__('ERROR: Please contact your Administrator for providing Private Key to continue.','mars').'</div>';
					  	}
					  	else{
						  	$html .= '<div class="form-group recaptcha_response_field">';
						  		$html .= recaptcha_get_html($videotube['public_key'], $error);
						  		$html .= '<span class="help-block"></span>';
						  	$html .= '</div>';
					  	}
					  }
					  $html .= '<button type="submit" class="btn btn-primary"">'.__('Upload','mars').'</button>
					  <img id="loading" style="display:none;" src="'.MARS_THEME_URI.'/img/ajax-loader.gif">
					  <input type="hidden" name="current_page" value="'.$post->ID.'">
					  <input type="hidden" name="action" value="mars_submit_video">
					  '.wp_nonce_field('submit_video_act','submit_video',true,false).'
					  
					</form>
				';
				
			}
			return do_shortcode( $html );
		}
		function action_form(){
			global $videotube;
			$videosize = isset( $videotube['videosize'] ) ? (int)$videotube['videosize'] : 10;
			$post_title = wp_filter_nohtml_kses( $_POST['post_title'] );
			$video_url = isset( $_POST['video_url'] ) ? trim( $_POST['video_url'] ) : null;
			$embed_code = isset( $_POST['embed_code'] ) ? trim( $_POST['embed_code'] ) : null;
			$video_file = isset( $_FILES['video_file'] ) ? $_FILES['video_file'] : null;
			$post_content = wp_filter_nohtml_kses( $_POST['post_content'] );
			$chb_video_type = isset( $_POST['chb_video_type'] ) ? $_POST['chb_video_type'] : null;
			$video_thumbnail = isset( $_FILES['video_thumbnail'] ) ? $_FILES['video_thumbnail'] : null; 
			$video_tag = isset( $_POST['video_tag'] ) ? wp_filter_nohtml_kses( $_POST['video_tag'] ) : null;
			$video_category = isset( $_POST['video_category'] ) ? $_POST['video_category'] : null;
			$user_id = get_current_user_id() ? get_current_user_id() : $videotube['submit_assigned_user'];
			$post_status = $videotube['submit_status'] ? $videotube['submit_status'] : 'pending'; 
			$layout = isset( $_POST['layout'] ) ? $_POST['layout'] : 'small';
			if( !$post_title ){
				echo json_encode(array(
					'resp'	=>	'error',
					'message'	=>	__('Video Title is required','mars'),
					'element_id'	=>	'post_title'
				));exit;
			}
			if( !$post_content ){
				echo json_encode(array(
					'resp'	=>	'error',
					'message'	=>	__('Video Description is required','mars'),
					'element_id'	=>	'post_content'
				));exit;					
			}
			
			if( !$chb_video_type ){
				echo json_encode(array(
					'resp'	=>	'error',
					'message'	=>	__('Video Type is required','mars'),
					'element_id'	=>	'chb_video_type'
				));exit;				
			}
			
			switch ($chb_video_type) {
				case 'video_link_type':
					if( !$video_url ){
						echo json_encode(array(
							'resp'	=>	'error',
							'message'	=>	__('Video Link is required','mars'),
							'element_id'	=>	'video_url'
						));exit;				
					}
					if( !wp_oembed_get( $video_url ) ){
						echo json_encode(array(
							'resp'	=>	'error',
							'message'	=>	__('The link does not support.','mars'),
							'element_id'	=>	'video_url'
						));exit;			
					}
				break;
				
				case 'embed_code_type':
					if( !$embed_code ){
						echo json_encode(array(
							'resp'	=>	'error',
							'message'	=>	__('Embed Code is required','mars'),
							'element_id'	=>	'embed_code'
						));exit;
					}
					if( !$video_thumbnail ){
						echo json_encode(array(
							'resp'	=>	'error',
							'message'	=>	__('Video Preview Image is required','mars'),
							'element_id'	=>	'video_thumbnail'
						));exit;
					}
					if( !mars_check_file_allowed( $video_thumbnail, 'image' ) ){
						echo json_encode(array(
							'resp'	=>	'error',
							'message'	=>	__('Video Preview Image type is invalid','mars'),
							'element_id'	=>	'video_thumbnail'
						));exit;
					}					
				break;
				default:
					if( !$video_file ){
						echo json_encode(array(
							'resp'	=>	'error',
							'message'	=>	__('Video File is required.','mars'),
							'element_id'	=>	'video_file'
						));exit;
					}
					if( !mars_check_file_allowed( $video_file, 'video' ) ){
						echo json_encode(array(
							'resp'	=>	'error',
							'message'	=>	__('Video File is invalid.','mars'),
							'element_id'	=>	'video_file'
						));exit;
					}
					if( !mars_check_file_size_allowed($video_file) ){
						echo json_encode(array(
							'resp'	=>	'error',
							'message'	=>	__('The video size must be less than ' . $videosize . 'MB','mars'),
							'element_id'	=>	'video_file'
						));exit;						
					}
					if( !$video_thumbnail ){
						echo json_encode(array(
							'resp'	=>	'error',
							'message'	=>	__('Video Preview Image is required','mars'),
							'element_id'	=>	'video_thumbnail'
						));exit;
					}
					if( !mars_check_file_allowed( $video_thumbnail, 'image' ) ){
						echo json_encode(array(
							'resp'	=>	'error',
							'message'	=>	__('Video Preview Image type is invalid','mars'),
							'element_id'	=>	'video_thumbnail'
						));exit;
					}
				break;
			}

			### Check recaptcha.
			if( $videotube['submit_captcha'] == 1 ){
				if( !function_exists('recaptcha_check_answer') ){
			  		require_once 'recaptchalib.php';
			  	}
				# the response from reCAPTCHA
				$resp = null;
				# the error code from reCAPTCHA, if any
				$error = null;				
				if( !$_POST["recaptcha_response_field"] ){
					echo json_encode(array(
						'resp'	=>	'error',
						'message'	=>	__('Recaptcha is required','mars'),
						'element_id'	=>	'recaptcha_response_field'
					));exit;					
				}
				$resp = recaptcha_check_answer ($videotube['private_key'],
                                        $_SERVER["REMOTE_ADDR"],
                                        $_POST["recaptcha_challenge_field"],
                                        $_POST["recaptcha_response_field"]);
                if (!$resp->is_valid){
					echo json_encode(array(
						'resp'	=>	'error',
						'message'	=>	$error = $resp->error,
						'element_id'	=>	'recaptcha_response_field'
					));exit;
                }
			}
			
			$postarr = array(
				'post_title'	=>	$post_title,
				'post_content'	=>	$post_content,
				'post_type'	=>	'video',
				'post_author'	=>	$user_id,
				'post_status'	=>	$post_status,
				'comment_status'	=>	'open'
			);
			
			$post_id = wp_insert_post($postarr, true);
			
			if ( is_wp_error( $post_id ) ){
				echo json_encode(array(
					'resp'	=>	'error',
					'message'	=>	__('Error: Unable to create Video. Please try again.','mars')
				));exit;
			}
			
			###  update meta
			if( $layout && function_exists( 'update_field' ) ){
				update_field('field_531980e906752', $layout, $post_id);
			}
			if( $video_url && function_exists( 'update_field' ) ){
				update_field('field_530eea326c4fa', $video_url, $post_id);
			}
			elseif ( $embed_code && function_exists( 'update_field' ) ){
				update_field('field_530eea326c4fb', $embed_code, $post_id);
			}
			else{
				### Upload files.
				if( function_exists('mars_insert_attachment') ){
					mars_insert_attachment('video_file', $post_id, false, 'video_file');
				}
			}
			### Preview image
			if( $video_thumbnail ){
				### Upload files.
				if( function_exists('mars_insert_attachment') ){
					mars_insert_attachment('video_thumbnail', $post_id, true);
				}				
			}
			### update term
			if( $video_tag ){
				wp_set_post_terms($post_id, $video_tag,'video_tag',true);	
			}
			if( $video_category ){
				wp_set_post_terms($post_id, $video_category,'categories',true);
			}
			do_action('mars_save_post',$post_id);
			if( $post_status != 'publish' ){
				$redirect_to = $videotube['submit_redirect_to'] ? get_permalink( $videotube['submit_redirect_to'] ) : NULL;
				if( !$redirect_to ){
					echo json_encode(array(
						'resp'	=>	'success',
						'message'	=>	__('Congratulation, Your submit is waiting for approval.','mars'),
						'post_id'	=>	$post_id,
					));exit;
				}
				else{
					echo json_encode(array(
						'resp'	=>	'success',
						'message'	=>	__('Congratulation, Your submit is waiting for approval.','mars'),
						'post_id'	=>	$post_id,
						'redirect_to'	=>	$redirect_to
					));exit;					
				}
			}
			else{
				echo json_encode(array(
					'resp'	=>	'publish',
					'message'	=>	__('Congratulation, Your submit is published.','mars'),
					'post_id'	=>	$post_id,
					'redirect_to'	=>	get_permalink( $post_id )
				));exit;				
			}
		}
	}
	new Mars_ShortcodeSubmitVideo();
}