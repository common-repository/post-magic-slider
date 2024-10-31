<?php 
//show_magic_slider_post
function show_magic_slider_post($post_id) {	
ob_start();
 $attachment_ids = magic_slider_get_gallery_imgs_ids($post_id);
    		if(!empty($attachment_ids)) { ?>

    			<link rel="stylesheet" type="text/css" 
    			href="<?php echo plugins_url( 'flexslider.css', __FILE__ ) ?>">

    			<link rel="stylesheet" type="text/css" 
    			href="<?php echo plugins_url( 'flexslider-rtl.css', __FILE__ ) ?>">
    			<div class="flexslider">
  				<ul class="slides">
    			<?php 
    			foreach ($attachment_ids as $img_id) {
    				//$t=wp_get_attachment_image_src( $img_id, 'medium' ); 
					$t2=wp_get_attachment_image_src( $img_id, 'large' ); 
					echo '
					
					    <li>
					      <img src="'.$t2[0].'" />
					    </li>';
    				
    			}

    			?>
    			 </ul>
			</div>
	
		  <script src="<?php echo plugins_url( 'jquery.flexslider-min.js', __FILE__ ) ?>"></script>		
				<?php
					
				$data =  get_option('MagicPostSlider'); 
				$location = (isset($data['location']))? esc_html($data['location']) : '';
				$animation_slid = (isset($data['animation_slid']))? esc_html($data['animation_slid']) : '';
				$speed_slid = (isset($data['speed_slid']))? esc_html($data['speed_slid']) : '';
				$slider_dir = (isset($data['slider_dir']))? esc_html($data['slider_dir']) : '';
				$slider_hover = (isset($data['slider_hover']))? esc_html($data['slider_hover']) : '';
				?>				
		<script type="text/javascript">
					 jQuery(document).ready(function($){
					 	var lang = document.documentElement.lang;
					 	if (lang == 'ar')
					 	{
					 	$('.flexslider').flexslider({
		   				 animation: "<?php echo $animation_slid ?>",
		   				direction: "<?php echo $slider_dir ?>",
						slideshowSpeed: "<?php echo $speed_slid?>",
						pauseOnHover : <?php echo $slider_hover ?>
		   				});	
					 	} 

					 	else {
					 	$('.flexslider').flexslider({
		   				 animation: "<?php echo $animation_slid ?>",
		   				direction: "<?php echo $slider_dir ?>",
						slideshowSpeed: "<?php echo $speed_slid?>",
						pauseOnHover : <?php echo $slider_hover ?>
		  					});
					 	}
		  				
						})
					</script>
			<?php 
    		}
    	
    $contents = ob_get_contents();
		ob_end_clean();
		return $contents;

}

function magic_slider_get_gallery_imgs_ids($post_id){
	$attachment_ids_str = get_post_meta( $post_id,'magic_slider_gallery_id',true);
	$attachment_ids = preg_split("/[,]/",$attachment_ids_str);
	for ($i=1; $i<count($attachment_ids) ; $i++){
		$attachment_ids_arr[] = $attachment_ids[$i];
	}
	return $attachment_ids_arr;
}
?>