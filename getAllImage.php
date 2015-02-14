<?php
/*
Plugin Name: Get All Image
Plugin URI: 
Description: To get all of your image in gallery
Version: 0
Author: Jimmy Samanda Rasu
Author URI: 
License: samandajimmy
.
This plugin is for my  use only
.
*/

class getAllImage {
	
    public function __construct()
    {
        add_shortcode('getAllImage', array($this, 'shortcode'));
    }
     
    public function shortcode($atts)
    {
    	
		return $this->getImages();
    }
	
	public function get_images_from_media_library() {
		$args = array(
			'post_type' => 'attachment',
			'post_mime_type' =>'image',
			'post_status' => 'inherit',
			'posts_per_page' => -1,
			'orderby' => 'rand'
		);
		$query_images = new WP_Query( $args );
		$images = array();
		foreach ( $query_images->posts as $image) {
			$images[] = array(
				'img' => $image->guid,
				'thumb' => wp_get_attachment_image($image->ID, 'thumbnail', false, $attr)
			);
		}
		$test = get_post_gallery();
		return $images;
	}
	
	public function getImages()
	{
		$imgs = $this->get_images_from_media_library();
		$html = '<div id="media-gallery">';

		foreach($imgs as $img) {

			//$html .= '<a href="' . $img['img'] . '"><img width="200" src="' . $img['img'] . '" alt="" /></a>';

			$html .= '<a href="'. $img['img'] .'">' . $img['thumb'] . '</a>';

		}

		$html .= '</div>';

		return $html;
	}
}
 
$getAllImage = new getAllImage();

function get_AllImage()
{
    $getAllImage = new getAllImage;
    echo $getAllImage->getImages();
}

add_filter( 'query_vars', 'se67095_add_query_vars');

function se67095_add_query_vars($vars){
    $vars[] = "getAllImage";
    return $vars;
}

add_action('template_redirect', 'se67905_my_template');

function se67905_my_template($template) {
    global $wp_query;
	
	$segmentss = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$segments = explode('?', $segmentss);
	$vario = $segments[1];
	
    if($vario == 'images=feed'){
		$getAllImage = new getAllImage;
		echo $getAllImage->getImages();
        exit;
	}

    return $template;
}

?>
