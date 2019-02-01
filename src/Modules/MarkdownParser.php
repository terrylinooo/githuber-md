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
 * @version 1.5.2
 */

namespace Githuber\Module;
use ParsedownExtra;

class MarkdownParser extends ParsedownExtra {

	/**
	 * Constructer.
	 */
	public function __construct() {
		parent::__construct();

		$this->InlineTypes['%'] = array( 'Figure' );
		$this->inlineMarkerList = '!%"*_&[:<>`~\\';
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

	/**
	 * Extend ParseDown for HTML 5 figure tag.
	 *
	 * @param array $excerpt
	 * @return array
	 */
	protected function inlineFigure( $excerpt ) {

        if ( !isset( $excerpt['text'][1] ) || '[' !== $excerpt['text'][1] ) {
            return;
        }

        $excerpt['text']= substr($excerpt['text'], 1);

        $link = $this->inlineLink($excerpt);

        if ( null === $link ) {
            return;
		}

		$attr_href  = $link['element']['attributes']['href'];
		$attr_text  = $link['element']['text'];
		$attr_title = $link['element']['attributes']['title'];
		
		$markup = '<figure>';

		$markup .= '<img src="' . $attr_href . '" alt="' . $attr_text . '">';

		if ( ! empty( $attr_title ) ) {
			$markup .= '<figcaption>' . $attr_title . '</figcaption>';
		}

		$markup .= '</figure>';

        $inline = array(
			'extent'  => $link['extent'] + 1,
			'markup'  => $markup,
            'element' => array(
                'name'       => 'img',
                'attributes' => array(
                    'src' => $attr_href,
                    'alt' => $attr_text,
                ),
            ),
        );

        return $inline;
    }
}
