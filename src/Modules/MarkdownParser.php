<?php
/**
 * Module Name: MarkdownParser
 * Module Description: Parse Markdown plaintext into HTML plaintext.
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Githuber
 * @since 1.1.0
 * @version 1.1.0
 */

namespace Githuber\Module;
use ParsedownExtra;

class MarkdownParser extends ParsedownExtra {

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
