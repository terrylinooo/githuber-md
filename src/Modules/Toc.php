<?php
/**
 * Module Name: Table of Content
 * Module Description: Display table of content in article section.
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Githuber
 * @since 1.9.0
 * @version 1.9.0
 */

namespace Githuber\Module;

class Toc extends ModuleAbstract {

	/**
	 * Constructer.
	 */
	public function __construct() {
		parent::__construct();
	}

	public function init() {
		// No ideas about Toc right now.
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

	}

	/**
	 * Print Javascript plaintext in page footer.
	 */
	public function front_print_footer_scripts() {
		$script = '

		';
		echo $script;
	}

	/**
	 * TOC parser
	 *
	 * @param string $html_string
	 * @return string
	 */
	public function parser( $html_string ) {

		preg_match_all('#<h[4-6]*[^>]*>.*?<\/h[4-6]>#', $html_string, $match);

		$toc = implode( "\n", $match[0] );
		$toc = str_replace( '<a name="', '<a href="#', $toc );
		$toc = str_replace( '</a>', '', $toc );
		$toc = preg_replace( '#<h([4-6])>#', '<li class="toc$1">',$toc );
		$toc = preg_replace( '#<\/h[4-6]>#', '</a></li>',$toc );

		$toc = '
			<div class="post-toc"> 
				<p class="post-toc-header">' . __( 'Table of Content', 'wp-githuber-md' ) . '</p>
				<hr />
				<ul>
					'.$toc.'
				</ul>
			</div>
			<br /><br />
		';

		return $toc;
	}
}
