<?php 
if ( ! defined('GITHUBER_PLUGIN_NAME') ) die; 
/**
 * Show PHP module notice.
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Githuber
 * @since 1.7.0
 * @version 1.7.0
 */
$php_version = phpversion();
?>

<div class="notice notice-error is-dismissible" style="margin-top: 15px;">
	<p>
		<?php echo __( 'Markdown Extra parser requires PHP module <strong>libxml</strong> and your system does not have <strong>libxml</strong> installed. Please disable Markdown Extra.', 'wp-githuber-md' ); ?> <br>
	</p>
</div>