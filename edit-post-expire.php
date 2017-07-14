<?php

/*
Plugin Name: Edit Post Expire
Plugin URI: http://shinephp.com/edit-post-expire-wordpress-plugin
Description: Prohibit authors edit published posts after a specified time interval
Version: 1.0
Author: Vladimir Garagulya
Author URI: http://shinephp.com
License: GPLv2
*/


/* Copyright 2012
Vladimir Garagulya
(email : vladimir@shinephp.com)
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301
USA
*/

if ( !function_exists("get_option" ) ) {
  exit;  // Silence is golden, direct call is prohibited
}

$epe_wp_version = get_bloginfo('version');  // as global $wp_version could be unavailable.
if (version_compare( $epe_wp_version, "2.8","<" ) ) {  
	if ( is_admin() && (!defined('DOING_AJAX') || !DOING_AJAX) ) {
		require_once ABSPATH.'/wp-admin/includes/plugin.php';
		deactivate_plugins( __FILE__ );
		$exit_msg = __('Edit Post Expire requires WordPress 2.8 or newer.', 'edit-post-expire').' <a href="http://codex.wordpress.org/Upgrading_WordPress">'.__('Please update!', 'ure').'</a>';
    wp_die( $exit_msg );
	} else {
		return;
	}
}

if (version_compare(PHP_VERSION, '5.2.4', '<')) {
	if ( is_admin() && (!defined('DOING_AJAX') || !DOING_AJAX) ) {
		require_once ABSPATH.'/wp-admin/includes/plugin.php';
		deactivate_plugins( __FILE__ );
		$exit_msg = __('Edit Post Expire requires PHP 5.2.4 or newer.', 'edit-post-expire').' <a href="http://codex.wordpress.org/Upgrading_WordPress">'.__('Please update!', 'ure').'</a>';
    wp_die( $exit_msg );
	} else {
		return;
	}
}

if (!class_exists('Edit_Post_Expire')) {
	define('EDIT_POST_EXPIRE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
	load_plugin_textdomain('edit_post_expire','', EDIT_POST_EXPIRE_PLUGIN_DIR . DIRECTORY_SEPARATOR. 'lang');
	require_once(EDIT_POST_EXPIRE_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'includes'. DIRECTORY_SEPARATOR . 'class-edit-post-expire-library.php');
	require_once(EDIT_POST_EXPIRE_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'includes'. DIRECTORY_SEPARATOR . 'class-edit-post-expire.php');
}


?>
