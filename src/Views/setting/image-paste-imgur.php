<?php 
if ( ! defined('FUTURE_PLUGIN_NAME') ) die;
/**
 * View for Controller/Setting
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Future
 * @since 1.2.0
 * @version 1.3.1
 */
?>

<?php 

echo __( 'Required while the choosed storage space is <u>imgur.com</u>. If you don\'t have one, <a href="https://api.imgur.com/oauth2/addclient" target="_blank">sign up</a> here.', 'wp-future-md' );

if ( ! function_exists( 'curl_init') ) {

	echo '<br /><span style="color: #b00000">';

	echo __( 'Uploading images to Imgur is unavailable because that <strong>PHP CURL</strong> is not installed on your system.', 'wp-future-md' );

	echo '</span>';
}
