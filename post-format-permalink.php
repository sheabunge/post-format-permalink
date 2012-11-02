<?php
/**
 * Plugin Name: Post Format Permalink
 * Plugin URI: https://github.com/bungeshea/post-format-permalink
 * Description: Include the post format slug in your permalinks. Simply use the <code>%post_format%</code> tag as part of your custom permalink.
 * Version: 1.2
 * Author: Shea Bunge
 * Author URI: http://bungeshea.com
 */

add_filter( 'post_link', 'post_format_permalink', 10, 2 );
add_filter( 'post_type_link', 'post_format_permalink', 10, 2 );
 
function post_format_permalink( $permalink, $post_id ) {
	
	// if we're not using the %post_format% tag in out permalinks, bail early
    if ( strpos($permalink, '%post_format%') === FALSE ) return $permalink;
     
	// get the post object
	$post = get_post( $post_id );
	if ( ! $post ) return $permalink;
		
	// get post format slug
	$format = get_post_format( $post->ID );
	
	// set the slug for standard posts
	if ( empty( $format ) )
		$format = apply_filters( 'post_format_standard_slug', 'standard' );
 
	// apply the post format slug to the permalink
    return str_replace( '%post_format%', $format, $permalink );
}