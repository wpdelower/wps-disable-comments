<?php

/**
 * WPS Disable Comments
 *
 * @package           wps_disable_comments
 * @author            WordPress Satkhira Community, Delower Hossain
 * @copyright         2023 WordPress Satkhira Community
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       WPS Disable Comments
 * Plugin URI:        https://www.wpsatkhira.com
 * Description:       PS Disable Comments is a powerful WordPress plugin designed to give website administrators full control over comments on their WordPress websites. With this plugin, you can easily manage and customize the commenting system to suit your website's needs, whether you want to completely disable comments globally or on specific post types.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Delower Hossain, WordPress Satkhira Community
 * Author URI:        https://www.wpsatkhira.com
 * Text Domain:       wps-dc
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

 
//Avoiding Direct File Access

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Load plugin textdomain.
 */
function wps_dc_load_textdomain() {
    load_plugin_textdomain( 'wpc-dc', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
  }
  add_action( 'plugins_loaded', 'wps_dc_load_textdomain' );


// WPS Disable Commnets Plugin 

add_action('admin_init', function () {
    // Redirect any user trying to access comments page
    global $pagenow;
     
    if ($pagenow === 'edit-comments.php') {
        wp_safe_redirect(admin_url());
        exit;
    }
 
    // Remove comments metabox from dashboard
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
 
    // Disable support for comments and trackbacks in post types
    foreach (get_post_types() as $post_type) {
        if (post_type_supports($post_type, 'comments')) {
            remove_post_type_support($post_type, 'comments');
            remove_post_type_support($post_type, 'trackbacks');
        }
    }
});
 
// Close comments on the front-end
add_filter('comments_open', '__return_false', 20, 2);
add_filter('pings_open', '__return_false', 20, 2);
 
// Hide existing comments
add_filter('comments_array', '__return_empty_array', 10, 2);
 
// Remove comments page in menu
add_action('admin_menu', function () {
    remove_menu_page('edit-comments.php');
});
 
// Remove comments links from admin bar
add_action('init', function () {
    if (is_admin_bar_showing()) {
        remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
    }
});
