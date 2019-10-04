<?php 
if ( ! defined('FUTURE_PLUGIN_NAME') ) die;
/**
 * Show PHP module notice.
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Future
 * @since 1.7.0
 * @version 1.7.0
 */
$php_version = phpversion();
?>

<div class="notice notice-error is-dismissible" style="margin-top: 15px;">
	<p>
		<?php printf( __( 'Markdown parser requires PHP module <strong>mbstring</strong> and your system does not have <strong>mbstring</strong> installed. Please ask for your web hosting provider to help you.', 'wp-future-md' ), $php_version ) ?> <br>
	</p>
</div>