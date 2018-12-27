<?php
/**
 * Githuber_Widget__TOC
 * Add a Table of Content for your article. This widget is for single-post pages only.
 *
 * @package   WordPress
 * @author    Terry Lin <terrylinooo>
 * @license   GPLv3 (or later)
 * @link      https://terryl.in
 * @copyright 2018 Terry Lin
 */

/**
 * Githuber_Widget_Toc
 */
class Githuber_Widget_Toc extends WP_Widget {

	/**
	 * Sets up a new Githuber TOC widget instance.
	 */
	public function __construct() {

		$widget_ops = array(
			'classname'                   => 'widget_githuber_toc',
			'description'                 => __( 'Add a Table of Content for your article. This widget is for single-post pages only.', 'wp-githuber-md' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'githuber-toc', __( 'Githuber: Table of Content', 'wp-githuber-md' ), $widget_ops );
		$this->alt_option_name = 'widget_githuber_toc';

		if ( is_active_widget( false, false, $this->id_base ) || is_customize_preview() ) {
			add_action( 'wp_head', array( $this, 'githuber_toc_js' ) );
		}
	}

	/**
	 * Register javascript for the Githuber TOC widget.
	 */
	public function githuber_toc_js() {
		wp_register_script( 'bootstrap-toc', GITHUBER_PLUGIN_URL . 'assets/vendor/bootstrap-toc/bootstrap-toc.min.js', array( 'jquery' ), '1.0.0' );
		wp_enqueue_script( 'bootstrap-toc' );
	}

	/**
	 * Initial TOC .
	 */
	public function githuber_toc_inline_js() {

		$inline_js = '
			jQuery( document ).ready(function( $ ) {
				Toc.init({
					$nav: $( "#toc" ),
					$scope: $( ".markdown-body" )
				});

				if ( "undefined" !== typeof $.fn.scrollspy ) {
					$( "body" ).scrollspy({
						target: "#toc"
					});
				}
			});
		';

		wp_add_inline_script( 'bootstrap-toc', $inline_js );
	}

	/**
	 * Outputs the content for the Githuber TOC instance.
	 */
	public function widget( $args, $instance ) {
		$this->githuber_toc_inline_js();

		$output = '<nav id="toc" class="toc" role="navigation"></nav>';
		echo $output;
	}

	/**
	 * Flushes the Githuber TOC widget cache.
	 */
	public function flush_widget_cache() {
		_deprecated_function( __METHOD__, '4.4.0' );
	}
}