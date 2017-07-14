<?php
/**
 * Edit Post Expire plugin main class
 * 
 * @author Vladimir Garagulya	
 * @package edit-post-expire
 * @subpackage EditPostExpire
 */

class Edit_Post_Expire {
	
	// common code staff, including options data processor
  private $lib = null;
	
	/**
	 * class constructor
	 * 
	 */
	function __construct() {

    // activation action
    register_activation_hook(__FILE__, array(&$this, 'setup'));
    
    // deactivation action
    register_deactivation_hook(__FILE__, array(&$this, 'cleanup'));
    
		$this->lib = new EditPostExpireLibrary('edit_post_expire');
		
    add_action( 'admin_init', array( &$this, 'init' ), 1 );
    
    // add own submenu 
    add_action('admin_menu',  array(&$this, 'create_menu'));        

		$this->init();
		
	}
	// end of __construct()


	
	
	/**
	 * Plugin initialization
	 * 
	 */
	function init() {
		
    // add a Settings link in the installed plugins page
    add_filter('plugin_action_links', array(&$this, 'plugin_action_links'), 10, 2);
    //add_filter('plugin_row_meta', array(&$this, 'plugin_row_meta'), 10, 2);         
		add_filter('map_meta_cap', array(&$this, 'map_meta_cap'), 1, 4);
		
	}
	// end of init()

	
	function plugin_action_links($links, $file) {
		
		$plugin = plugin_basename( EDIT_POST_EXPIRE_PLUGIN_DIR . DIRECTORY_SEPARATOR .'edit-post-expire.php' );
		if ( $file == $plugin ) {
        $settings_link = "<a href='options-general.php?page=edit-post-expire.php'>".__('Settings','edit-post-expire')."</a>";
        array_unshift( $links, $settings_link );
    }
		
		return $links;		
	}
	// end of plugin_action_links()
	
	
	function plugin_row_meta($links, $file) {
		
		return $links;
		
	}
	// end of plugin_row_meta()
	
	
	function create_menu() {
		if ( function_exists('add_menu_page') ) {        
			add_options_page(__( 'Edit Post Expire Settings', 'edit_post_expire' ), __( 'Edit Post Expire', 'edit_post_expire' ), 'manage_options', 'edit-post-expire.php', array( &$this, 'settings' ));    
		}
	}
	// end of create_menu()
	
	
	function settings() {
    if (isset($_POST['edit_post_expire_update'])) {  // process update from the options form
      $nonce=$_REQUEST['_wpnonce'];
      if (!wp_verify_nonce($nonce, 'edit_post_expire') ) {
        die("Security check");
      }
      
      $minutes_after = $this->lib->get_request_var('minutes_after', 'post', 'int');
      $this->lib->put_option('minutes_after', $minutes_after);
      $this->lib->flush_options();
      $this->lib->show_message('Options are updated');
    } else { // get options from the options storage
      $minutes_after = $this->lib->get_option('minutes_after', 5);
    }
        
    require_once(EDIT_POST_EXPIRE_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'includes'. DIRECTORY_SEPARATOR . 'settings.php');
		
	}
	// end of settings()
	
	
	/**
	 * Change WordPress map_meta_cap() default behavior according to plugin purpose
	 * 
	 * @param array $caps
	 * @param string $cap
	 * @param int $user_id
	 * @param array $args
	 * @return array of strings
	 * 
	 */
	function map_meta_cap($caps, $cap, $user_id, $args) {


		if (empty($args[0]) || !is_numeric($args[0])) {	// no information about current post
			return $caps;
		}

		$post = get_post($args[0]);
		if (!is_object($post) || 'publish' !== $post->post_status) {	// apply to the published posts only
			return $caps;
		}

		// prevent recursion
		remove_filter('map_meta_cap', array(&$this, 'map_meta_cap'), 1, 4);

		if (!current_user_can('administrator')) {	// apply for non-admin users only
			// Period of time
			$lockable = '+' . $this->lib->get_option('minutes_after', '5') . ' minutes';
			// Now
			$cur_time = strtotime(gmdate('Y-m-d H:i:59'));
			// post time
			$post_time = strtotime($post->post_modified_gmt);
			// Add lockable time to post time
			$lock_time = strtotime($lockable, $post_time);

			foreach ($caps as $key => $capability) {
				if ((( 'edit_post' === $capability ) ||
								( 'edit_posts' === $capability ) ||
								( 'edit_published_posts' === $capability ) ||								
								( 'delete_post' === $capability ) ||
								( 'delete_posts' === $capability ) ||
								( 'delete_published_posts' === $capability )) ) {
					// Compare current time with lockable time
					if ($cur_time >= $lock_time) { // block post editing
						$caps[$key] = 'do_not_allow';
					}
				} // if ()
			} // foreach ()
		}	// if (!current_user()
	// restore removed filter
		add_filter('map_meta_cap', array(&$this, 'map_meta_cap'), 1, 4);

		return $caps;
	}
	// end of map_meta_cap()
	
	
	// execute on plugin activation
	function setup() {
		
	}
	// end of setup()
	
	
	// execute on plugin deactivation
	function cleanup() {
		
	}
	// end of setup()
	
	
}  // end of class Edit_Post_Expire

new Edit_Post_Expire();
?>