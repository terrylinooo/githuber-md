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
 * @version 1.11.2
 */

namespace Githuber\Module;
use Parsedown;

class MarkdownParser extends Parsedown {

	// Stores shortcodes we remove and then replace
	protected $preserve_text_hash = array();

	/**
	 * Preserve shortcodes, untouched by Markdown.
	 * This requires use within a WordPress installation.
	 * @var boolean
	 */
	public $preserve_shortcodes = true;

	/**
	 * Preserve single-line <code> blocks.
	 * @var boolean
	 */
	public $preserve_inline_code_blocks = true;

	/**
	 * Constructer.
	 */
	public function __construct() {

		$is_html5_figure = githuber_get_option( 'support_html_figure', 'githuber_extensions' );

		if ( 'no' !== $is_html5_figure ) {
			$this->InlineTypes['%'] = array( 'Figure' );
			$this->inlineMarkerList = '!%"*_&[:<>`~\\';
		}

		$is_allow_shortcode = githuber_get_option( 'allow_shortcode', 'githuber_preferences' );

		if ( 'no' === $is_allow_shortcode ) {
			$this->preserve_shortcodes = false;
		}
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

		// Preserve anything inside a single-line <code> element
		if ( $this->preserve_inline_code_blocks ) {
			$text = $this->single_line_code_preserve( $text );
		}
		// Remove all shortcodes so their interiors are left intact
		if ( $this->preserve_shortcodes ) {
			$text = $this->shortcode_preserve( $text );
		}

		$parsed_content = $this->text( $text );

		$parsed_content = $this->do_restore( $parsed_content );

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
	
	/**
	 * The below methods are from Jetpack: Markdown modular
	 * 
	 * @link https://github.com/Automattic/jetpack/blob/master/_inc/lib/markdown/gfm.php
	 * @license GPL
	 */

	/**
	 * Retrieve the shortcode regular expression for searching.
	 * @return string A regex for grabbing shortcodes.
	 */
	protected function get_shortcode_regex() {
		$pattern = get_shortcode_regex();

		// don't match markdown link anchors that could be mistaken for shortcodes.
		$pattern .= '(?!\()';

		return "/$pattern/s";
	}

	/**
	 * Called to preserve WP shortcodes from being formatted by Markdown in any way.
	 *
	 * @param  string $text Text in which to preserve shortcodes
	 * @return string Text with shortcodes replaced by a hash that will be restored later
	 */
	protected function shortcode_preserve( $text ) {
		$text = preg_replace_callback( $this->get_shortcode_regex(), array( $this, 'do_remove_text' ), $text );
		return $text;
	}

	/**
	 * Regex callback for text preservation
	 *
	 * @param  array $m Regex $matches array
	 * @return string    A placeholder that will later be replaced by the original text
	 */
	protected function do_remove_text( $m ) {
		return $this->hash_block( $m[0] );
	}
	/**
	 * Call this to store a text block for later restoration.
	 *
	 * @param  string $text Text to preserve for later
	 * @return string  Placeholder that will be swapped out later for the original text
	 */
	protected function hash_block( $text ) {
		$hash                              = md5( $text );
		$this->preserve_text_hash[ $hash ] = $text;
		$placeholder                       = $this->hash_maker( $hash );

		return $placeholder;
	}

	/**
	 * Preserve inline code block contents by HTML encoding them. Useful before getting to KSES stripping.
	 *
	 * @param  string $text Text that may need preserving
	 * @return string Text that was preserved if needed
	 */
	public function single_line_code_preserve( $text ) {
		return preg_replace_callback( "/[`]{1}([^\n`]*?[^\n`])[`]{1}/", array( $this, 'do_single_line_code_preserve' ), $text );
	}

	/**
	 * Regex callback for inline code presevation
	 *
	 * @param  array $matches Regex matches
	 * @return string Codeblock with escaped interior
	 */
	public function do_single_line_code_preserve( $matches ) {

		if ( 'yes' === githuber_get_option( 'support_inline_code_keyboard_style', 'githuber_extensions' ) ) {
			if ( '}' === substr( $matches[1], -1 ) && '{' !== substr( $matches[1], 0, 1 ) ) {
				return '<code class="kb-btn">' . $this->hash_block( esc_html( $matches[1] ) ) . '</code>';
			}
		}
		return '<code>' . $this->hash_block( esc_html( $matches[1] ) ) . '</code>';
	}

	/**
	 * Preserve code block contents by HTML encoding them. Useful before getting to KSES stripping.
	 *
	 * @param  string $text Markdown/HTML content
	 * @return string       Markdown/HTML content with escaped code blocks
	 */
	public function codeblock_preserve( $text ) {
		return preg_replace_callback( "/^(\t*[`~]{3})([^`\n]+)?\n([\s\S]*?)\n(\\1)/m", array( $this, 'do_codeblock_preserve' ), $text );
	}

	/**
	 * Regex callback for code block preservation.
	 *
	 * @param  array $matches Regex matches
	 * @return string Codeblock with escaped interior
	 */
	public function do_codeblock_preserve( $matches ) {
		$block = stripslashes( $matches[3] );

		// Issue #209
		$block = str_replace( '&#', '_!_!_', $block );

		// check `
		$block = str_replace( '`', '&#x60;', $block );
		$block = esc_html( $block );
		$block = str_replace( '\\', '\\\\', $block );
		$open  = $matches[1] . $matches[2] . "\n";
		$end   =  "\n" . $matches[4];

		return $open . $block . $end;
	}

	/**
	 * Restore previously preserved (i.e. escaped) code block contents.
	 *
	 * @param  string $text Markdown/HTML content with escaped code blocks
	 * @return string Markdown/HTML content
	 */
	public function codeblock_restore( $text ) {
		return preg_replace_callback( "/^(\t*[`~]{3})([^`\n]+)?\n([\s\S]*?)\n(\\1)/m", array( $this, 'do_codeblock_restore' ), $text );
	}

	/**
	 * Regex callback for code block restoration (unescaping).
	 *
	 * @param  array $matches Regex matches
	 * @return string Codeblock with unescaped interior
	 */
	public function do_codeblock_restore( $matches ) {
		$block = html_entity_decode( $matches[3], ENT_QUOTES );

		// Issue #209
		$block = str_replace( '_!_!_', '&#', $block );

		$block = str_replace( '&#x60;', '`', $block );
		$open  = $matches[1] . $matches[2] . "\n";
		$end   =  "\n" . $matches[4];

		return $open . $block . $end;
	}

	/**
	 * Restores any text preserved by $this->hash_block()
	 *
	 * @param  string $text Text that may have hashed preservation placeholders
	 * @return string Text with hashed preseravtion placeholders replaced by original text
	 */
	protected function do_restore( $text ) {
		// Reverse hashes to ensure nested blocks are restored.
		$hashes = array_reverse( $this->preserve_text_hash, true );
		foreach ( $hashes as $hash => $value ) {
			$placeholder = $this->hash_maker( $hash );
			$text        = str_replace( $placeholder, $value, $text );
		}
		// reset the hash
		$this->preserve_text_hash = array();

		// Restore "`"
		$text = str_replace( '&#x60;', '`', $text );

		return $text;
	}

	/**
	 * Less glamorous than the Keymaker
	 *
	 * @param  string $hash An md5 hash
	 * @return string A placeholder hash
	 */
	protected function hash_maker( $hash ) {
		return 'MARKDOWN_HASH' . $hash . 'MARKDOWN_HASH';
	}
}
