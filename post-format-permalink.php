<?php
/*
Plugin Name: Post Format Permalink
Plugin URI: http://statikpulse.com/post-format-permalink
Description: Post Format Permalink plugin gives you the ability to include the post format slug in your permalinks. Once the plugin is activated, simply include the %format% tag in your custom permalink.
Version: 1.1.1
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
		
		if(is_admin()){
			wp_enqueue_style('style', WP_PLUGIN_URL . '/post-format-permalink/css/styles.css');
    	add_action('admin_menu', array(&$this, 'admin_settings_menu_link'));
      add_filter('plugin_row_meta', array(&$this, 'admin_plugin_links'),10,2);
		}
    
		
		global $clean_post_rewrites, $clean_rewrites, $standard_slug;
		$clean_post_rewrites = array();
		$standard_slug = get_option('post_format_standard_slug');
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
		global $standard_slug;
		if (strpos($permalink, '%format%') === FALSE) return $permalink;

		if(!is_object($post)){
			$post = get_post($post_id);
		}

		$format = get_post_format($post->ID);	
		if (empty($format)){
			$format = !empty($standard_slug) ? $standard_slug : 'standard';
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
		global $standard_slug;
	  $wp_rewrite->use_verbose_page_rules = true;
	
		$post_format_slugs = implode('|', get_post_format_slugs());
		if(!empty($standard_slug)){
			$post_format_slugs = preg_replace('|standard|', $standard_slug, $post_format_slugs, 1);	
		}
		
		while (list($k, $v) = each($post_rewrite)) {
			$new_k = preg_replace('|%format%|', '('.$post_format_slugs.')', $k, 1);
			$clean_post_rewrites[$new_k] = $v;
		}

		return $post_rewrite;
	}
	
	function admin_settings_menu_link() {
		add_options_page('Post Format Permalink Settings', 'Post Format Permalink', 'administrator', 'post-format-permalink-settings', array(&$this, 'admin_settings_panel') );
	}
	
	function admin_plugin_links($links, $file){
     if( $file == 'post-format-permalink/post-format-permalink.php') {
        $links[] = '<a href="' . admin_url( 'options-general.php?page=post-format-permalink-settings' ) . '">' . __('Settings') . '</a>';
        $links[] = '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=8ES9XYT2JDAYJ" target="_blank">Donate</a>';
     }
     return $links;
  }
	
	function admin_settings_panel() {
		global $standard_slug;
		
    if (!empty($_POST)) {
       update_option('post_format_standard_slug', $_POST['post_format_standard_slug']);
			$standard_slug = $_POST['post_format_standard_slug'];
       echo '<div id="message" class="updated fade"><p><strong>' . __('Options saved.', 'post-format-permalink') . '</strong></p></div>';
			$this->activate();
    }
    ?>

    <form method="post" action="<?php echo get_bloginfo('url'); ?>/wp-admin/options-general.php?page=post-format-permalink-settings" id="post_format_permalink_settings_form" name="post_format_permalink_settings_form">      
     <?php wp_nonce_field('update-options'); ?>
     <h1>Post Format Link Settings</h1>
     <div class="section">
        <div>
           <div class="fl">
              <label for="email">Replace "standard" slug with:</label><br />
              <input type="text" name="post_format_standard_slug" id="post_format_standard_slug" value="<?php if ( isset( $standard_slug ) ) { echo $standard_slug; } ?>" class="text-field" tabindex="1">
           </div>
           <div class="fr desc">The slug for standard posts is <em>"standard"</em>. You can remap to anything you would like such as <em>"text"</em> or <em>"note"</em>. Leave blank for <em>"standard"</em>.</div>
           <div class="clear"></div>
        </div>
     </div>       
     <input type="submit" value="<?php _e('Save Settings') ?>" tabindex="5" class="button-secondary action" />
  </form>
		<?php
	}
	
}

$post_format = new PostFormatPermalink();

?>