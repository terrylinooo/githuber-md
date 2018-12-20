<?php
/**
 * Githuber MD
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Githuber
 * @since 1.0.0
 * @version 1.0.0
 */

/**
 * Plugin Name: Githuber MD
 * Plugin URI:  https://github.com/terrylinooo/githuber-md
 * Description: Githuber MD is an all-in-one Markdown plugin for your WordPress sites.
 * Version:     1.1.0
 * Author:      Terry Lin
 * Author URI:  https://terryl.in/
 * License:     GPL 3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: githuber-md
 * Domain Path: /languages
 */

/**
 * Any issues, or would like to request a feature, please visit.
 * https://github.com/terrylinooo/githuber-md/issues
 * 
 * Welcome to contribute your code here:
 * https://github.com/terrylinooo/githuber-md
 *
 * Thanks for using Githuber MD!
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
 * GITHUBER_PLUGIN_DIR           : {absolute_path}/wp-content/plugins/githuber-enhanced-plugin/
 * GITHUBER_PLUGIN_URL           : {protocal}://{domain_name}/wp-content/plugins/githuber-enhanced-plugin/
 * GITHUBER_PLUGIN_PATH          : {absolute_path}/wp-content/plugins/githuber-enhanced-plugin/githuber-enhanced-plugin.php
 * GITHUBER_PLUGIN_LANGUAGE_PACK : githuber-enhanced-plugin/languages
 */

define( 'GITHUBER_PLUGIN_NAME', plugin_basename( __FILE__ ) );
define( 'GITHUBER_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'GITHUBER_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'GITHUBER_PLUGIN_PATH', __FILE__ );
define( 'GITHUBER_PLUGIN_LANGUAGE_PACK', dirname( plugin_basename( __FILE__ ) ) . '/languages' );
define( 'GITHUBER_PLUGIN_VERSION', '1.1.0' );
define( 'GITHUBER_PLUGIN_TEXT_DOMAIN', 'githuber-md' );

/**
 * Start to run Githuber plugin cores.
 */

// Githuber autoloader.
require_once GITHUBER_PLUGIN_DIR . 'src/autoload.php';

// Load helper functions
require_once GITHUBER_PLUGIN_DIR . 'src/helpers.php';

// Composer autoloader.
require_once GITHUBER_PLUGIN_DIR . 'vendor/autoload.php';

// Load main launcher class of Githuber enhanced plugin.
$gitbuber = new Githuber();

// Let's go!
$gitbuber->init();
