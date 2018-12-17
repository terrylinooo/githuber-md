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
		'Githuber'                      => '../Githuber',
		'Githuber_Walker'               => 'class-githuber-walker',
		'Githuber_Post_Type_Repository' => 'class-githuber-post-type-repository',
		'Githuber_Widget_Toc'           => 'class-githuber-widget-toc',
		'WeDevs_Settings_API'           => 'class-settings-api'
	);

	if ( array_key_exists( $class_name, $wp_utils_mapping ) ) {

		$include_path = GITHUBER_PLUGIN_DIR . 'src/wp_utilities/' . $wp_utils_mapping[ $class_name ] . '.php';

	} else {
		
		if ( false !== strpos( $class_name, '\\' ) ) {
			if ( false === strpos( $class_name, 'Githuber' ) ) {
				return false;
			}

			$class_name = str_replace(
				['Controller\\', 'Model\\', 'Module\\'], 
				['Controllers\\', 'Models\\', 'Modules\\'], 
				$class_name
			);

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

/**
 * Function autoloader
 */
function func_autoload() {

	$autoload_list = array(
		'functions',
		'shortcode',
		'theme-op'
	);

	foreach ( $autoload_list as $filename ) {
		$include_path  = GITHUBER_PLUGIN_DIR . 'src/wp_utilities/githuber-' . $filename . '.php';

		if ( ! empty( $include_path ) && is_readable( $include_path ) ) {
			require $include_path;
		}
	}
}

func_autoload();