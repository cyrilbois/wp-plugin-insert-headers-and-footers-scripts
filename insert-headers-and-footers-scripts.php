<?php
/**
 * Plugin Name: Insert Headers And Footers Scripts
 * Plugin URI: https://github.com/cyrilbois/wp-plugin-insert-headers-and-footers-scripts
 * Description: Allows you to insert script or CSS in the header or footer on your entire site, or on specific posts or pages.
 * Version: 1.0.0
 * Author: Cyril Bois
 * Author URI: https://github.com/cyrilbois
 * Text Domain: insert-headers-and-footers-scripts
 * Domain Path:  languages
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace ExtendsClass;
 
// Security: If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class InsertHeadersAndFootersInWordpress {
	public $name		= null;
	public $displayName	= null;
	public $message 	= null;
	public $error		= null;
	public $view		= null;
	private $path		= null;	
	public static function create() {
		return new InsertHeadersAndFootersInWordpress();
	}
	public function __construct() {
		$this->name			= 'insert-headers-and-footers-scripts';
		$this->displayName	= 'Insert Headers and Footers Scripts';
		$this->path			= plugin_dir_path( __FILE__ );
		add_action('admin_init', [$this, 'adminInit']);
		add_action('admin_menu', [$this, 'adminMenu']);
		add_action( 'wp_head', function () {
			$this->inject('header');
		});
		add_action( 'wp_footer', function () {
			$this->inject('footer');
		});
		add_action('add_meta_boxes', [$this, 'postCustomBox']);	
		add_action('save_post', [$this, 'savePostSettings']);
    }
	function adminInit() {
		register_setting($this->name, 'header');
		register_setting($this->name, 'footer');
	}	
	function adminMenu() {
		add_submenu_page( 'options-general.php', $this->displayName, $this->displayName, 'manage_options', $this->name, [$this, 'adminPanel']);
	}
	function adminPanel() {
		// Only admin user can access this page
		if (!current_user_can('administrator')) {
			echo __( 'Sorry, you are not allowed to access this admin page.', $this->name );
			return;
		}
		if (isset($_REQUEST['submit'])) {
			$this->saveAdminSettings($message, $error);
		}
		
		$this->view = 'admin';
		$this->header = esc_html(get_option('header'));
		$this->footer = esc_html(get_option('footer'));
		include_once( $this->path . '/views/panel.php');
	}
	function saveAdminSettings() {
		// Security: Check nonce
		if (!wp_verify_nonce($_REQUEST[$this->name.'nonce'], $this->name)) {
			$this->error = __('Settings not been saved, nonce is wrong.', $this->name);
		} else {
			update_option('header', stripslashes_deep($_REQUEST[$this->name.'header']));
			update_option('footer', stripslashes_deep($_REQUEST[$this->name.'footer']));
			$this->message = __('Settings have been saved.', $this->name);
		}
	}
	function savePostSettings($postId) {
		update_post_meta($postId, 'header', $_POST[$this->name.'header']);
		update_post_meta($postId, 'footer', $_POST[$this->name.'footer']);
	}
	function postCustomBox() {
		foreach (['post', 'page'] as $screen) {
			add_meta_box(
				$this->name.'custom-box',
				$this->displayName,
				[$this, 'postCustomBoxPanel'], 
				$screen
			);
		}
	}
	function postCustomBoxPanel($post) {
		$this->view = 'post';
		$this->header = esc_html(get_post_meta($post->ID, 'header', true));
		$this->footer = esc_html(get_post_meta($post->ID, 'footer', true));
		include_once( $this->path . '/views/panel.php');
	}
	function inject($section) {
		// Does not send data in some cases
		if (is_admin() || is_feed() || is_robots() || is_trackback()) {
			return;
		}
		// Global header / footer
		$data = get_option($section);
		if (!empty($data)) {
			echo $data;
		}
		// Post header / footer
		if ( is_single() || is_page() ) {
			$data = get_post_meta(get_the_ID(), $section, true);
			if (!empty($data)) {
				echo $data;
			}
		}
	}
}

\ExtendsClass\InsertHeadersAndFootersInWordpress::create();

?>