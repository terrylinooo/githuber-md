<?php

if ( ! defined( 'GITHUBER_PLUGIN_NAME' ) ) {
	die;
}

/**
 * View for Controller/Setting
 *
 * @author lategege
 *
 * @package Githuber
 * @since 1.2.0
 * @version 1.3.1
 */

echo __( 'Visit http|https://your-server-domain/dashboard/settings/api', 'wp-githuber-md' );

if ( ! function_exists( 'curl_init' ) ) {

	echo '<br /><span style="color: #b00000">';

	echo __( 'Uploading images to chevereto is unavailable because that <strong>PHP CURL</strong> is not installed on your system.', 'wp-githuber-md' );

	echo '</span>';
}
