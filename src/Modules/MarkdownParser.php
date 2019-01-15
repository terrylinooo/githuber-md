<?php
/**
 * Module Name: MarkdownParser
 * Module Description: Parse Markdown plaintext into HTML plaintext.
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Githuber
 * @since 1.0.0
 * @version 1.4.3
 */

namespace Githuber\Module;

if ( version_compare( phpversion(), '5.3.6', '>=' ) ) {

	class MarkdownParser extends \ParsedownExtra {
		/**
		 * Constructer.
		 */
		public function __construct() {
			parent::__construct();
		}
	
		/**
		 * Remove bare <p> elements. <p>s with attributes will be preserved.
		 *
		 * @param  string $text HTML content.
		 * @return string <p>-less content.
		 */
		public function remove_bare_p_tags( $text ) {
			return preg_replace( "#<p>(.*?)</p>(\n|$)#ums", '$1$2', $text );
		}
	
		/**
		 * Teansform Markdown to HTML.
		 * 
		 * @param string $text Markdown content.
		 */
		public function transform( $text ) {
			$parsed_content = $this->text( $text );
			return $parsed_content;
		}
	}

} else {

	class MarkdownParser extends \Parsedown {

		/**
		 * Remove bare <p> elements. <p>s with attributes will be preserved.
		 *
		 * @param  string $text HTML content.
		 * @return string <p>-less content.
		 */
		public function remove_bare_p_tags( $text ) {
			return preg_replace( "#<p>(.*?)</p>(\n|$)#ums", '$1$2', $text );
		}
	
		/**
		 * Teansform Markdown to HTML.
		 * 
		 * @param string $text Markdown content.
		 */
		public function transform( $text ) {
			$parsed_content = $this->text( $text );
			return $parsed_content;
		}
	}
}

