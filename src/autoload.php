<?php

/**
 * Githuber Class autoloader.
 *
 * @package   Githuber
 * @author    Terry Lin <terrylinooo>
 * @license   GPLv3 (or later)
 * @link      https://terryl.in
 * @copyright 2018 Terry Lin
 */

/**
 * Class autoloader
 */
spl_autoload_register( function( $class_name ) {

	$include_path = '';

	$class_name = ltrim( $class_name, '\\' );

	$wp_utils_mapping = array(         
		'Githuber'              => '../Githuber',
		'Githuber_Settings_API' => 'class-settings-api',
		'Githuber_Widget_Toc'    => 'class-widget-toc',
	);

	if ( array_key_exists( $class_name, $wp_utils_mapping ) ) {

		$include_path = GITHUBER_PLUGIN_DIR . 'src/wp_utilities/' . $wp_utils_mapping[ $class_name ] . '.php';

	} else {
		
		if ( false !== strpos( $class_name, '\\' ) ) {
			if ( false === strpos( $class_name, 'Githuber' ) ) {
				return false;
			}

			$class_name = str_replace('Controller\\', 'Controllers\\', $class_name);
			$class_name = str_replace('Model\\', 'Models\\', $class_name);
			$class_name = str_replace('Module\\', 'Modules\\', $class_name);

			$last_ns_pos = strrpos( $class_name, '\\' );
			$namespace = substr( $class_name, 0, $last_ns_pos );
			$class_name = substr( $class_name, $last_ns_pos + 1 );
			$filename  = GITHUBER_PLUGIN_DIR . '/src/' . str_replace( '\\', '/', $namespace ) . '/';
			$filename .= str_replace( '_', '/', $class_name ) . '.php';
	
			$include_path = str_replace( 'Githuber/', '', $filename );
		}
	}

	if ( ! empty( $include_path ) && is_readable( $include_path ) ) {
		require $include_path;
	}
});
