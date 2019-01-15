<?php 
if ( ! defined('GITHUBER_PLUGIN_NAME') ) die; 
/**
 * Show PHP version notice.
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Githuber
 * @since 1.4.3
 * @version 1.4.3
 */
$php_version = phpversion();
?>

<div class="notice notice-error is-dismissible">
	<p>
		<?php printf( __( 'The minimum required PHP version for WP Githuber MD is PHP <strong>5.3.6</strong>, and yours is <strong>%1s</strong>.', 'wp-githuber-md' ), $php_version ) ?> <br>
		<?php echo __( 'Please remove WP Githuber MD or upgrade your PHP version.', 'wp-githuber-md' ); ?>
	</p>
</div>