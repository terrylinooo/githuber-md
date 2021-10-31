<?php
/**
 * Plugin Name: WP Githuber MD
 * Plugin URI:  https://github.com/terrylinooo/githuber-md
 * Description: An all-in-one Markdown plugin for your WordPress sites.
 * Version:     1.16.1
 * Author:      Terry Lin
 * Author URI:  https://terryl.in/
 * License:     GPL 3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: wp-githuber-md
 * Domain Path: /languages
 */

/**
 * Any issues, or would like to request a feature, please visit.
 * https://github.com/terrylinooo/githuber-md/issues
 *
 * Welcome to contribute your code here:
 * https://github.com/terrylinooo/githuber-md
 *
 * Thanks for using WP Githuber MD!
 * Star it, fork it, share it if you like this plugin.
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * CONSTANTS
 *
 * Those below constants will be assigned to: `/Controllers/ControllerAstruct.php`
 *
 * GITHUBER_PLUGIN_NAME          : Plugin's name.
 * GITHUBER_PLUGIN_DIR           : The absolute path of the Githuber plugin directory.
 * GITHUBER_PLUGIN_URL           : The URL of the Githuber plugin directory.
 * GITHUBER_PLUGIN_PATH          : The absolute path of the Githuber plugin launcher.
 * GITHUBER_PLUGIN_LANGUAGE_PACK : Translation Language pack.
 * GITHUBER_PLUGIN_VERSION       : Githuber plugin version number
 * GITHUBER_PLUGIN_TEXT_DOMAIN   : Githuber plugin text domain
 *
 * Expected values:
 *
 * GITHUBER_PLUGIN_DIR           : {absolute_path}/wp-content/plugins/wp-githuber-md/
 * GITHUBER_PLUGIN_URL           : {protocal}://{domain_name}/wp-content/plugins/wp-githuber-md/
 * GITHUBER_PLUGIN_PATH          : {absolute_path}/wp-content/plugins/wp-githuber-md/wp-githuber-md.php
 * GITHUBER_PLUGIN_LANGUAGE_PACK : wp-githuber-md/languages
 */

define( 'GITHUBER_PLUGIN_NAME', plugin_basename( __FILE__ ) );
define( 'GITHUBER_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'GITHUBER_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'GITHUBER_PLUGIN_PATH', __FILE__ );
define( 'GITHUBER_PLUGIN_LANGUAGE_PACK', dirname( plugin_basename( __FILE__ ) ) . '/languages' );
define( 'GITHUBER_PLUGIN_VERSION', '1.16.1' );
define( 'GITHUBER_PLUGIN_TEXT_DOMAIN', 'wp-githuber-md' );

/**
 * Developer only.
 *
 * Turnning this option on, you have to install Monolog first.
 * Run: `composer require monolog/monolog` to install Monolog.
 *
 * After finishing debugging, run: `composer remove monolog/monolog` to remove it.
 */
define( 'GITHUBER_DEBUG_MODE', false );

/**
 * Start to run Githuber plugin cores.
 */

// Githuber autoloader.
require_once GITHUBER_PLUGIN_DIR . 'src/autoload.php';

// Load helper functions
require_once GITHUBER_PLUGIN_DIR . 'src/helpers.php';

// Composer autoloader.
require_once GITHUBER_PLUGIN_DIR . 'vendor/autoload.php';

if ( is_admin() ) {

	if ( 'yes' === githuber_get_option( 'support_mardown_extra', 'githuber_extensions' ) ) {
		if ( ! class_exists( 'DOMDocument' ) ) {
			add_action( 'admin_notices', 'githuber_md_warning_libxml' );

			function githuber_md_warning_libxml() {
				echo githuber_load_view( 'message/php-libxml-warning' );
			}
		}
	}

	if ( ! function_exists( 'mb_strlen' ) ) {
		add_action( 'admin_notices', 'githuber_md_warning_mbstring' );

		function githuber_md_warning_mbstring() {
			echo githuber_load_view( 'message/php-mbstring-warning' );
		}
	}
}

if ( version_compare( phpversion(), '5.3.0', '>=' ) ) {

	/**
	 * Activate Githuber plugin.
	 */
	function githuber_activate_plugin() {

		$githuber_markdown = array(
			'enable_markdown_for_post_types' => array( 'post', 'page' ),
			'disable_revision'               => 'no',
			'disable_autosave'               => 'yes',
			'html_to_markdown'               => 'yes',
			'markdown_editor_switcher'       => 'yes',
			'fetch_remote_image'             => 'no',
			'editor_live_preview'            => 'yes',
			'editor_sync_scrolling'          => 'yes',
			'editor_html_decode'             => 'yes',
			'editor_toolbar_theme'           => 'default',
			'editor_editor_theme'            => 'default',
		);

		$setting_markdown = get_option( 'githuber_markdown' );

		// Add default setting. Only execute this action at the first time activation.
		if ( empty( $setting_markdown ) ) {
			update_option( 'githuber_markdown', $githuber_markdown, '', 'yes' );
		}
	}

	/**
	 * Deactivate Githuber plugin.
	 */
	function githuber_deactivate_plugin() {
		global $current_user;

		// Turn on Rich-text editor.
		update_user_option( $current_user->ID, 'rich_editing', 'true', true );
		delete_user_option( $current_user->ID, 'dismissed_wp_pointers', true );
	}

	register_activation_hook( __FILE__, 'githuber_activate_plugin' );
	register_deactivation_hook( __FILE__, 'githuber_deactivate_plugin' );

	if ( 'yes' === githuber_get_option( 'support_emojify', 'githuber_modules' ) ) {

		/**
		 * Disable the emoji's
		 * 
		 * The blow code is from https://wordpress.org/plugins/disable-emojis/
		 */
		function disable_emojis() {
			remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
			remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
			remove_action( 'wp_print_styles', 'print_emoji_styles' );
			remove_action( 'admin_print_styles', 'print_emoji_styles' );	
			remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
			remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );	
			remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );

			add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
			add_filter( 'wp_resource_hints', 'disable_emojis_remove_dns_prefetch', 10, 2 );
		}

		/**
		 * Filter function used to remove the tinymce emoji plugin.
		 * 
		 * @param  array $plugins
		 *
		 * @return array Difference betwen the two arrays
		 */
		function disable_emojis_tinymce( $plugins ) {
			if ( is_array( $plugins ) ) {
				return array_diff( $plugins, array( 'wpemoji' ) );
			}
			return array();
		}

		/**
		 * Remove emoji CDN hostname from DNS prefetching hints.
		 *
		 * @param  array  $urls          URLs to print for resource hints.
		 * @param  string $relation_type The relation type the URLs are printed for.
		 *
		 * @return array Difference betwen the two arrays.
		 */
		function disable_emojis_remove_dns_prefetch( $urls, $relation_type ) {
			if ( 'dns-prefetch' == $relation_type ) {
				// Strip out any URLs referencing the WordPress.org emoji location
				$emoji_svg_url_bit = 'https://s.w.org/images/core/emoji/';
				foreach ( $urls as $key => $url ) {
					if ( strpos( $url, $emoji_svg_url_bit ) !== false ) {
						unset( $urls[$key] );
					}
				}
			}
			return $urls;
		}
		
		add_action( 'init', 'disable_emojis' );
	}

	// Load main launcher class of WP Githuber MD plugin.
	$gitbuber = new Githuber();

} else {
	/**
	 * Prompt a warning message while PHP version does not meet the minimum requirement.
	 * And, nothing to do.
	 */
	function githuber_md_warning() {
		echo githuber_load_view( 'message/php-version-warning' );
	}

	add_action( 'admin_notices', 'githuber_md_warning' );
}
