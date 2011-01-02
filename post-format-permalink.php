<?php
/*
Plugin Name: Post Format Permalink
Plugin URI: http://statikpulse.com/post-format-permalink
Description: Post Format Permalink plugin gives you the ability to include the post format slug in your permalinks. Once the plugin is activated, simply include the %format% tag in your custom permalink.
Version: 1.0
Author: Yan Sarazin 
Author URI: http://statikpulse.com
*/

/*  Copyright 2010 Yan Sarazin  (email : yan@statikpulse.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class PostFormatPermalink {
	
	function __construct() {
		register_activation_hook(__FILE__, array(&$this, 'activate'));
		register_deactivation_hook(__FILE__, array(&$this, 'deactivate'));

		add_filter('pre_post_link', array(&$this, 'generate_permalink'), 10, 2);
		add_filter('post_rewrite_rules', array(&$this, 'rewrite_rules'));
		add_filter('generate_rewrite_rules', array(&$this, 'generate_rewrite_rules'));
		
		global $clean_post_rewrites, $clean_rewrites;
		$clean_post_rewrites = array();
	}
	
	function activate() {
		global $wp_rewrite;
		$wp_rewrite->flush_rules();
	}
	
	function deactivate() {
		remove_filter('post_link', array(&$this, 'generate_permalink'));
		remove_filter('post_rewrite_rules', array(&$this, 'rewrite_rules'));
		remove_filter('generate_rewrite_rules', array(&$this, 'generate_rewrite_rules'));

		global $wp_rewrite;
		$wp_rewrite->flush_rules();
	}
	
	function generate_permalink($permalink, $post) {
		if (strpos($permalink, '%format%') === FALSE) return $permalink;

		if(!is_object($post)){
			$post = get_post($post_id);
		}

		$format = get_post_format($post->ID);	
		if (empty($format)){
			$format = 'standard';
		}

		return str_replace('%format%', $format, $permalink);
	}
	
	function generate_rewrite_rules($wp_rewrite) {
		global $clean_post_rewrites;
		$wp_rewrite->rules = $wp_rewrite->rules + $clean_post_rewrites;
	}
	
	function rewrite_rules($post_rewrite) {
		global $clean_post_rewrites;

	  global $wp_rewrite;
	  $wp_rewrite->use_verbose_page_rules = true;

		while (list($k, $v) = each($post_rewrite)) {
			$new_k = preg_replace('|%format%|', '('.implode('|', get_post_format_slugs()).')', $k, 1);
			$clean_post_rewrites[$new_k] = $v;
		}

		return $post_rewrite;
	}
	
}

$post_format = new PostFormatPermalink();

?>