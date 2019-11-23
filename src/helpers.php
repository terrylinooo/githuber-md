<?php
/**
 * Global helper functions.
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Githuber
 * @since 1.0.0
 * @version 1.2.0
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

	global $post;

	$post_id = null;

	if ( ! empty( $post ) )  {
		$post_id = $post->ID;
	} elseif ( ! empty( $_REQUEST['post'] ) ) {
		$post_id = $_REQUEST['post'];
	} elseif ( ! empty( $_REQUEST['post_ID'] ) ) {
		$post_id = $_REQUEST['post_ID'];
	}
	
	return $post_id;
}

/**
 * Check current user's permission.
 *
 * @param string $action User action.
 *
 * @return bool
 */
function githuber_current_user_can( $action ) {
	global $post;

	if ( current_user_can( $action, $post->ID ) ) {
		return true;
	}
	return false;
}

/**
 * Load view files.
 *
 * @param string $template_path The specific template's path.
 * @param array  $data              Data is being passed to.
 *
 * @return string
 */
function githuber_load_view( $template_path, $data = array() ) {
	$view_file_path = GITHUBER_PLUGIN_DIR . 'src/Views/' . $template_path . '.php';

	if ( ! empty( $data ) ) {
		extract( $data );
	}

	if ( file_exists( $view_file_path ) ) {
		ob_start();
		require $view_file_path;
		$result = ob_get_contents();
		ob_end_clean();
		return $result;
	}
	return null;
}

/**
 * Get post type on current screen.
 *
 * @return string
 */
function githuber_get_current_post_type() {
	global $post, $typenow, $current_screen;

	$post_type = null;

	if ( ! empty( $post ) && ! empty( $post->post_type ) ) {
		$post_type = $post->post_type;
	} elseif ( ! empty( $typenow ) ) {
		$post_type = $typenow;
	} elseif ( ! empty( $current_screen ) && ! empty( $current_screen->post_type ) ) {
		$post_type = $current_screen->post_type;
	} elseif ( ! empty( $_REQUEST['post_type'] ) ) {
		$post_type = sanitize_key( $_REQUEST['post_type'] );
	} elseif ( ! empty( $_REQUEST['post'] ) ) {
		$post_type = get_post_type( $_REQUEST['post'] );
	}
	return $post_type;
}

/**
 * Load utility files.
 *
 * @param string $filename
 *
 * @return string
 */
function githuber_load_utility( $filename ) {
	$include_path  = GITHUBER_PLUGIN_DIR . 'src/wp_utilities/class-' . $filename . '.php';

	if ( ! empty( $include_path ) && is_readable( $include_path ) ) {
		require $include_path;
	}
}

/**
 * Record Markdown processing logs for debug propose.
 *
 * @param string $message
 * @param array  $data
 *
 * @return void
 */
function githuber_logger( $message, $data = array() ) {
	if ( GITHUBER_DEBUG_MODE ) {
		\Githuber\Controller\Monolog::logger( $message, $data );
	}
}