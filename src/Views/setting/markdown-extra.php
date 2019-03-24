<?php 
if ( ! defined('GITHUBER_PLUGIN_NAME') ) die; 
/**
 * View for Controller/Setting
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Githuber
 * @since 1.2.0
 * @version 1.3.1
 */
?>

<?php 

echo __( 'Support <a href="https://michelf.ca/projects/php-markdown/extra/" target="_blank">Markdown Extra</a>.', 'wp-githuber-md' );

if ( ! class_exists( 'DOMDocument' ) ) {

	echo '<br /><span style="color: #b00000">';

	echo __( 'Markdown Extra parser requires PHP module <strong>libxml</strong> and your system does not have <strong>libxml</strong> installed. Please disable Markdown Extra.', 'wp-githuber-md' );

	echo '</span>';
}
