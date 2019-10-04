<?php
/**
 * Module Name: Table of Content
 * Module Description: Display table of content in article section.
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Future
 * @since 1.9.0
 * @version 1.10.1
 */

namespace Future\Module;

class Toc extends ModuleAbstract {

	/**
	 * Constructer.
	 */
	public function __construct() {
		parent::__construct();
	}

	public function init() {

		add_action( 'wp_enqueue_scripts', array( $this, 'front_enqueue_scripts' ) );
		add_action( 'wp_print_footer_scripts', array( $this, 'front_print_footer_scripts' ) );

		if ( 'yes' === future_get_option( 'display_toc_in_post', 'future_modules' ) ) {

			add_filter( 'the_content', function( $string ) {

				// Only single page will display TOC.
				if ( ! is_single() ) {
					return $string;
				}
	
				$css = future_get_option( 'post_toc_float', 'future_modules' );

				if ( 'yes' === future_get_option( 'post_toc_border', 'future_modules' ) ) {
					$css .= ' with-border';
				}

				return '<div class="post-toc-block float-' . $css . '"> 
					<div class="post-toc-header">' . __( 'Table of Content', 'wp-future-md' ) . '</div>
					<nav id="md-post-toc" class="md-post-toc"></nav>
					</div>' . $string;
			}, 10, 1 );
		}
	}

	/**
	 * Register CSS style files for frontend use.
	 * 
	 * @return void
	 */
	public function front_enqueue_styles() {

	}

	/**
	 * Register JS files for frontend use.
	 * 
	 * @return void
	 */
	public function front_enqueue_scripts() {
	
		// Only single page will display TOC.
		if ( ! is_single() ) {
			return;
		}

		wp_register_script( 'future-toc', FUTURE_PLUGIN_URL . 'assets/js/jquery.toc.min.js', array( 'jquery' ), '1.0.1' );
		wp_enqueue_script( 'future-toc' );
	}

	/**
	 * Print Javascript plaintext in page footer.
	 */
	public function front_print_footer_scripts() {

		// Only single page will display TOC.
		if ( ! is_single() ) {
			return;
		}

		$script = '
			<script id="module-toc">
				(function($) {
					$(function() {
		';

		// Show TOC in post.
		if ( 'yes' == future_get_option( 'display_toc_in_post', 'future_modules' ) ) {

			$script .= '
				$("#md-post-toc").initTOC({
					selector: "h2, h3, h4, h5, h6",
					scope: ".entry-content",
				});

				$("#md-post-toc a").click(function(e) {
					e.preventDefault();
					var aid = $( this ).attr( "href" );
					$( "html, body" ).animate( { scrollTop: $(aid).offset().top - 80 }, "slow" );
				});
			';
		}

		// Show TOC in widget area.
		if ( 'yes' == future_get_option( 'is_toc_widget', 'future_modules' ) ) {

			$script .= '
				$("#md-widget-toc").initTOC({
					selector: "h2, h3, h4, h5, h6",
					scope: ".entry-content",
				});

				$("#md-widget-toc a").click(function(e) {
					e.preventDefault();
					var aid = $( this ).attr( "href" );
					$( "html, body" ).animate( { scrollTop: $(aid).offset().top - 80 }, "slow" );
				});
			';
		}

		$script .= '
					});
				})(jQuery);
			</script>
		';

		echo preg_replace( '/\s+/', ' ', $script );
	}
}
