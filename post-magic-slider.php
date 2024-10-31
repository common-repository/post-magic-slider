<?php
/**
 * @package post-magic-slider
 * @version 1.3
 */
/*
Plugin Name: Post Magic Slider
Plugin URI: http://wordpress.org/plugins/post-magic-slider
Description: Add flexslider to any post type.
Version: 1.3
Author: AboZain, Mohammed J. AlBanna, aymanmustafa, Fayeq
Author URI: https://profiles.wordpress.org/abozain
*/


add_action( 'admin_menu', 'magec_reg_menu' );
include_once('show_magic_slider.php');
function magec_reg_menu(){
	add_options_page( 'Post Magic Slider', 'Post Magic Slider', 'administrator', 'magic-slider', 'MagicSlider_fun'); 
}

function MagicSlider_fun(){

	//echo 'my test';
	//print_r($_POST);
	if(isset($_POST['post_types']) && ($_POST['post_types']) ){
		
				$data['post_types'] =  $_POST['post_types'] ;
                $data['location'] = sanitize_text_field( $_POST['location'] );
				$data['animation_slid'] = sanitize_text_field( $_POST['animation_slid'] );
				$data['speed_slid'] = sanitize_text_field( $_POST['speed_slid'] );
				$data['slider_dir'] = sanitize_text_field( $_POST['slider_dir'] );
				$data['slider_hover'] = sanitize_text_field( $_POST['slider_hover'] );  

                update_option('MagicPostSlider', $data);		
		echo '<br /> <br /> <h2 style="
  color: green;
  background-color: white;
  height: 15px;
  width: 95%;
  padding: 20px;">Saved Successfully</h2>';
	}else{
		$data =  get_option('MagicPostSlider'); 
		//print_r($data);
	}
	$post_types_arr = is_array($data['post_types'])? $data['post_types'] : array();
	$location = (isset($data['location']))? esc_html($data['location']) : '';
	$animation_slid = (isset($data['animation_slid']))? esc_html($data['animation_slid']) : '';
	$speed_slid = (isset($data['speed_slid']))? esc_html($data['speed_slid']) : '';
	//slider_dir slider_hover
	$slider_dir = (isset($data['slider_dir']))? esc_html($data['slider_dir']) : '';
	$slider_hover = (isset($data['slider_hover']))? esc_html($data['slider_hover']) : '';
	?>
        <div class="wrap">
            <?php screen_icon('edit-pages'); ?>
			<h2>Magic Post Slider</h2>
            <h4>Magic Post Slider</h4>
            <form method="post" action="">
				<?php settings_fields( 'disable-settings-group' ); ?>
            	<?php do_settings_sections( 'disable-settings-group' ); ?>
			
			<label> <h4>Post Types</h4> </label>
			<?php 
			$post_types = get_post_types( '', 'names' ); 
			foreach ( $post_types as $post_type ) {
				if(in_array($post_type, array('attachment', 'revision'))) continue;
			?>
				<input type="checkbox" name="post_types[]" value="<?php echo $post_type ?>" <?php if(in_array($post_type, $post_types_arr )) { echo 'checked'; } ?>><?php echo $post_type ?><br>
			<?php } ?>
			<br/>
			<label> <h4>Silder Appear on: </h4> </label>
				<input type="radio" name="location" value="Top Content" <?php if($location == 'Top Content') { echo 'checked'; } ?>>Top Content<br>
				<input type="radio" name="location" value="Bottom Content" <?php if($location == 'Bottom Content') { echo 'checked'; } ?>>Bottom Content<br>
				<input type="radio" name="location" value="Short Code" <?php if($location == 'Short Code') { echo 'checked'; } ?>>Short Code   :   [MagicSlider]<br>
				<br/>
			<label> <h4>Silder Animation: </h4> </label>
			
			<input type="radio" name="animation_slid" value="fade" <?php if($animation_slid == 'fade') { echo 'checked'; } ?>> fade  </br> 
			<input type="radio" name="animation_slid" value="slide" <?php if($animation_slid == 'slide') { echo 'checked'; } ?>> slide </br>
			</br>
			<label> <h4>Silder Speed: </h4> </label>
			
			<input type="text" name="speed_slid" value="<?php if($speed_slid ) { echo $speed_slid; } else echo '7000'; ?>">   </br> 
			</br>
			<label> <h4>Silder Direction: </h4> </label>
			<select name="slider_dir">
				  <option value="horizontal" <?php if($slider_dir == 'horizontal') { echo 'selected'; } ?>>Horizontal</option>
				  <option value="vertical" <?php if($slider_dir == 'vertical') { echo 'selected'; } ?>>Vertical</option>
  
			</select>
			  </br> 
			<label> <h4>Silder Pause On Hover: </h4> </label>
			<select name="slider_hover">
				  <option value="true" <?php if($slider_hover == 'true') { echo 'selected'; } ?>>Active</option>
				  <option value="flase" <?php if($slider_hover == 'false') { echo 'selected'; } ?>>NOn-Active</option>
  
			</select>
			  </br>
			 <?php submit_button(); ?>
            </form>
        </div>	
		
		<br/>
		<?php
}
///////////////////////////////////////
$data =  get_option('MagicPostSlider'); 
$location = (isset($data['location']))? esc_html($data['location']) : '';

add_shortcode('MagicSlider', 'MagicSlider_shortcode_func');
function MagicSlider_shortcode_func() {
	return show_magic_slider_post(get_the_ID());
}


 if ($location == 'Bottom Content') {
	add_filter('the_content', 'Magic_slider_add_my_content');
	function Magic_slider_add_my_content($content) {
	$my_custom_text = show_magic_slider_post(get_the_ID());
	if(is_single() && !is_home()) {
	$content .= $my_custom_text;
	
	}
	return $content;
	}
}

else if ($location == 'Top Content') {
	add_filter('the_content', 'Magic_slider_add_my_content_after');
	function Magic_slider_add_my_content_after($content) {
	$my_custom_text = show_magic_slider_post(get_the_ID());
	if(is_single() && !is_home()) {
	$my_custom_text .= $content;
	}
	return $my_custom_text;
	}
}
//////////////////////////////////

function magic_slider_add_gallery() {

	$data =  get_option('MagicPostSlider'); 
	$post_types_arr = is_array($data['post_types'])? $data['post_types'] : array();
	if(!empty($post_types_arr)){
		foreach ( $post_types_arr as $post_type ) {
		   add_meta_box( 'magic_slider_gallery',
			 __('Images Gallery', 'magic_slider'), 
			 'magic_slider_gallery_output',
			  $post_type );  
		}
	}
}
add_action( 'add_meta_boxes', 'magic_slider_add_gallery' );


/********
*
*	Meta Boxes
*
*******/

/**
 * Outputs the content of the meta box
 */
function magic_slider_gallery_output( $post ) {

  
    $all_meta = get_post_meta( $post->ID );
    
    ?>
  
  	<style> 
#imgs img{
    	width:150px;
    	height:150px;
    }
    .img_galle{
    	margin-right:50px;
    	margin-bottom:20px;
    	float:right;
    	position: relative;
    }
    .img_remove{
    	   width: 25px !important;
			  height: 25px !important;
			  background: #ff0000;
			  color: #ffffff;
			  position: absolute;
			  left: 0px;
			  cursor: pointer;
			  text-align: center;
			  display: block;
			  font-size: 15px;
			  padding-top: 5px;
			  font-weight: bold;
    }

  	</style>
    <p> 
        <input type="hidden" name="magic_slider_gallery_id" id="magic_slider_gallery_id" value="<?php if (!empty($all_meta['magic_slider_gallery_id'][0])) echo $all_meta['magic_slider_gallery_id'][0]; ?>" />
    </p>
    <p>
        <button type="button" id="upload_gallery_button" data-uploader_title="Images Gallery" data-uploader_button_text="upload" ><?php _e( 'upload images' )?></button><span><?php _e( 'Upload Images Album' )?></span>
    </p>
    <p>
    	<ul id="imgs" >
    	<?php $attachment_ids = magic_slider_get_gallery_imgs_ids($post->ID);
    		if(!empty($attachment_ids['0'])){
    			foreach ($attachment_ids as $img_id) {
    				$t=wp_get_attachment_image_src( $img_id, 'thumbnail' ); 
    				echo "<li  class='img_galle' id='img_".$img_id."'><i class='img_remove' onclick='remove_func(".$img_id.")'>X</i>
					<img  src='".$t[0]."' ></li>";
    			}
    		}
    	?>
    		
    	</ul>

    	<div style="clear:both; width:100%; height:1px;"></div>
    </p>

    <script type="text/javascript">
    	// Uploading files
		var file_frame;

		  jQuery('#upload_gallery_button').live('click', function( event ){

		    event.preventDefault();

		    // If the media frame already exists, reopen it.
		    if ( file_frame ) {
		      file_frame.open();
		      return;
		    }

		    // Create the media frame.
		    file_frame = wp.media.frames.file_frame = wp.media({
		      title: jQuery( this ).data( 'uploader_title' ),
		      button: {
		        text: jQuery( this ).data( 'uploader_button_text' ),
		      },
		      multiple: true  // Set to true to allow multiple files to be selected
		    });


			  file_frame.on( 'select', function() {

			    var selection = file_frame.state().get('selection');
			    var imgs='';

			    //jQuery('#magic_slider_gallery_id').val("");
			    //jQuery('#imgs').empty();


			    selection.map( function( attachment ) {

			      attachment = attachment.toJSON();

				   jQuery("#imgs").append("<li class='img_galle' id='img_"+attachment.id+"'><i class='img_remove' onclick='remove_func("+attachment.id+")'>X</i><img src='"+attachment.url+"' ></li>");
				   var imgs = jQuery('#magic_slider_gallery_id').val();
			       imgs +=','+attachment.id;
			       jQuery('#magic_slider_gallery_id').val(imgs);
			    });
			  });

		    // Finally, open the modal
		    file_frame.open();
		  });
		function remove_func($img_id) {
			    jQuery("#img_"+$img_id).remove();

			    var images = jQuery('#magic_slider_gallery_id').val();
			    images = images.substr(1);
			    var image_ids=images.split(',');
			    var ids='';
			    jQuery.each(image_ids, function( index, value ) {
			    	if($img_id != value ){
				  		ids +=','+value;
				  	}
				});
				
				 jQuery('#magic_slider_gallery_id').val(ids);
				 //alert('0');
			}
    </script>
   
 
    <?php  
}//end Outputs the content of the meta box

/**
 * Save the custom meta uploader gallery 
 */
function magic_slider_uploader_gallery_meta_save( $post_id ) {
 
    
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
   
    
    if ( $is_autosave || $is_revision  ) {
        return;
    }
    /* Save  attachment ids meta-box */
    if ( isset( $_POST['magic_slider_gallery_id'] ) /*&& !empty($_POST['magic_slider_gallery_id'])*/ ){

		update_post_meta( $post_id, 'magic_slider_gallery_id', $_POST['magic_slider_gallery_id'] );
   		
    }
    
}
add_action( 'save_post', 'magic_slider_uploader_gallery_meta_save' );
?>