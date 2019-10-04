<?php
/**
 * WP Future MD
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Future
 * @since 1.0.0
 * @version 1.11.7
 */



/**
 * Plugin Name: WP Future MD
 * Plugin URI:  https://github.com/terrylinooo/future-md
 * Description: An all-in-one Markdown plugin for your WordPress sites.
 * Version:     1.11.7
 * Author:      Terry Lin
 * Author URI:  https://terryl.in/
 * License:     GPL 3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: wp-future-md
 * Domain Path: /languages
 */

/**
 * Any issues, or would like to request a feature, please visit.
 * https://github.com/terrylinooo/future-md/issues
 *
 * Welcome to contribute your code here:
 * https://github.com/terrylinooo/future-md
 *
 * Thanks for using WP Future MD!
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
 * FUTURE_PLUGIN_NAME          : Plugin's name.
 * FUTURE_PLUGIN_DIR           : The absolute path of the Future plugin directory.
 * FUTURE_PLUGIN_URL           : The URL of the Future plugin directory.
 * FUTURE_PLUGIN_PATH          : The absolute path of the Future plugin launcher.
 * FUTURE_PLUGIN_LANGUAGE_PACK : Translation Language pack.
 * FUTURE_PLUGIN_VERSION       : Future plugin version number
 * FUTURE_PLUGIN_TEXT_DOMAIN   : Future plugin text domain
 *
 * Expected values:
 *
 * FUTURE_PLUGIN_DIR           : {absolute_path}/wp-content/plugins/wp-future-md/
 * FUTURE_PLUGIN_URL           : {protocal}://{domain_name}/wp-content/plugins/wp-future-md/
 * FUTURE_PLUGIN_PATH          : {absolute_path}/wp-content/plugins/wp-future-md/wp-future-md.php
 * FUTURE_PLUGIN_LANGUAGE_PACK : wp-future-md/languages
 */

define( 'FUTURE_PLUGIN_NAME', plugin_basename( __FILE__ ) );
define( 'FUTURE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'FUTURE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'FUTURE_PLUGIN_PATH', __FILE__ );
define( 'FUTURE_PLUGIN_LANGUAGE_PACK', dirname( plugin_basename( __FILE__ ) ) . '/languages' );
define( 'FUTURE_PLUGIN_VERSION', '1.11.7' );
define( 'FUTURE_PLUGIN_TEXT_DOMAIN', 'wp-future-md' );

/**
 * Developer only.
 *
 * Turnning this option on, you have to install Monolog first.
 * Run: `composer require monolog/monolog` to install Monolog.
 *
 * After finishing debugging, run: `composer remove monolog/monolog` to remove it.
 */
define( 'FUTURE_DEBUG_MODE', false);

/**
 * Start to run Future plugin cores.
 */

// Future autoloader.
require_once FUTURE_PLUGIN_DIR . 'src/autoload.php';

// Load helper functions
require_once FUTURE_PLUGIN_DIR . 'src/helpers.php';

// Composer autoloader.
require_once FUTURE_PLUGIN_DIR . 'vendor/autoload.php';

if ( is_admin() ) {
	if ( 'yes' === future_get_option( 'support_mardown_extra', 'future_extensions' ) ) {
		if ( ! class_exists( 'DOMDocument' ) ) {
			add_action( 'admin_notices', 'future_md_warning_libxml' );

			function future_md_warning_libxml() {
				echo future_load_view( 'message/php-libxml-warning' );
			}
		}
	}

	if ( ! function_exists( 'mb_strlen' ) ) {
		add_action( 'admin_notices', 'future_md_warning_mbstring' );

		function future_md_warning_mbstring() {
			echo future_load_view( 'message/php-mbstring-warning' );
		}
	}
}

if ( version_compare( phpversion(), '5.3.0', '>=' ) ) {

	/**
	 * Activate Future plugin.
	 */
	function future_activate_plugin() {
		global $current_user;

		$future_markdown = array(
			'enable_markdown_for_post_types' => array( 'post', 'page' ),
			'disable_revision'               => 'no',
			'disable_autosave'               => 'yes',
			'html_to_markdown'               => 'yes',
			'markdown_editor_switcher'       => 'yes',
			'editor_live_preview'            => 'yes',
			'editor_sync_scrolling'          => 'yes',
			'editor_html_decode'             => 'yes',
			'editor_toolbar_theme'           => 'default',
			'editor_editor_theme'            => 'default',
		);

		$setting_markdown = get_option( 'future_markdown' );

		// Add default setting. Only execute this action at the first time activation.
		if ( empty( $setting_markdown ) ) {
			update_option( 'future_markdown', $future_markdown, '', 'yes' );
		}
	}

	/**
	 * Deactivate Future plugin.
	 */
	function future_deactivate_plugin() {
		global $current_user;

		// Turn on Rich-text editor.
		update_user_option( $current_user->ID, 'rich_editing', 'true', true );
		delete_user_option( $current_user->ID, 'dismissed_wp_pointers', true );
	}

	register_activation_hook( __FILE__, 'future_activate_plugin' );
	register_deactivation_hook( __FILE__, 'future_deactivate_plugin' );

	// Load main launcher class of WP Future MD plugin.
	$gitbuber = new Future();

} else {
	/**
	 * Prompt a warning message while PHP version does not meet the minimum requirement.
	 * And, nothing to do.
	 */
	function future_md_warning() {
		echo future_load_view( 'message/php-version-warning' );
	}

	add_action( 'admin_notices', 'future_md_warning' );
}