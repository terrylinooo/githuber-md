<?php 
if ( ! defined('FUTURE_PLUGIN_NAME') ) die;
/**
 * Show PHP version notice.
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Future
 * @since 1.4.3
 * @version 1.4.3
 */
$php_version = phpversion();
?>

<div class="notice notice-error is-dismissible">
	<p>
		<?php printf( __( 'The minimum required PHP version for WP Future MD is PHP <strong>5.3.6</strong>, and yours is <strong>%1s</strong>.', 'wp-future-md' ), $php_version ) ?> <br>
		<?php echo __( 'Please remove WP Future MD or upgrade your PHP version.', 'wp-future-md' ); ?>
	</p>
</div>