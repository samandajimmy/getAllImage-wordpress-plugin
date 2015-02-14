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
    protected $pluginPath;
    protected $pluginUrl;
	
    public function __construct()
    {
        // Set Plugin Path
        $this->pluginPath = dirname(__FILE__);
     
        // Set Plugin URL
        $this->pluginUrl = WP_PLUGIN_URL . '/wp-getAllImage';
		
        add_shortcode('getAllImage', array($this, 'shortcode'));
    }
     
    public function shortcode($atts)
    {
		// pass the attributes to getImages function and render the images
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

/**
*   Add the 'my_plugin' query variable so WordPress
*   won't remove it.
*/
function se67095_add_query_vars($vars){
    $vars[] = "getAllImage";
    return $vars;
}

/**
*   check for  'my_plugin' query variable and do what you want if its there
*/
add_action('template_redirect', 'se67905_my_template');

function se67905_my_template($template) {
    global $wp_query;
	
	$segmentss = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$segments = explode('?', $segmentss);
	$vario = $segments[1];

    // If the 'my_plugin' query var isn't appended to the URL,
    // don't do anything and return default
    if($vario == 'images=feed'){
		$getAllImage = new getAllImage;
		echo $getAllImage->getImages();
        exit;
	}

    return $template;
}

?>
