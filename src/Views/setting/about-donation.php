<?php 
if ( ! defined('GITHUBER_PLUGIN_NAME') ) die; 
/**
 * View for Controller/Setting
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Githuber
 * @since 1.7.2
 * @version 1.7.2
 */
?>

<div style="border: 1px #dddddd solid; background-color: #ffffff; padding: 10px; display: inline-block;">
    <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=donate%40terryl.in&item_name=WordPress+Plugin+-+WP+Githuber+MD&currency_code=USD&source=url" target="_blank">
        <img src="<?php echo GITHUBER_PLUGIN_URL . 'assets/images/donate_qr.png' ?>">
    </a>
</div>
<p><?php _e( 'If you think this plugin is useful to you, buy me a coffee.<br />Payment gateway provided by PayPal.', 'wp-githuber-md' ); ?></p>
<ol class="donate-note">
    <li><?php printf( __( 'Top 5 donators, including their names or company name and URLs, will be listed on <a href="%s">my homepage</a>.', 'wp-githuber-md' ), 'https://terryl.in/'); ?></li>
    <li><?php printf( __( 'All donators will be listed on  <a href="%s">Thank You</a> page.', 'wp-githuber-md' ), 'https://terryl.in/thank-you/'); ?></li>
</ol>