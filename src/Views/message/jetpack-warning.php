<?php 
if ( ! defined('GITHUBER_PLUGIN_NAME') ) die; 
/**
 * Show conflict notice.
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Githuber
 * @since 1.4.5
 * @version 1.5.0
 */

?>

<div class="notice notice-error is-dismissible">
	<p>
		<?php echo __( 'WP Gitbuber MD suggests you to disable Jetpack Markdown (or other Markdown plugins that use Jetpack Markdown module) to prevent conflicts.', 'wp-githuber-md' ); ?>
	</p>
</div>