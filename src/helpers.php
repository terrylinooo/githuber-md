<?php
/**
 * Global helper functions.
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Githuber
 * @since 1.0.0
 * @version 1.0.0
 */

/**
* Get the value of a settings field.
*
* @param string $option  settings field name.
* @param string $section the section name this field belongs to.
* @param string $default default text if it's not found.
* @return mixed
*/
function githuber_get_option( $option, $section, $default = '' ) {
	$options = get_option( $section );

	if ( isset( $options[ $option ] ) ) {
		return $options[ $option ];
	}
	return $default;
}

/**
 * Get current Post ID.
 *
 * @return int
 */
function githuber_get_current_post_id() {
	global $post, $wp_posts;

	if ( ! empty( $post ) )  {
		return $post->ID;
	}
}