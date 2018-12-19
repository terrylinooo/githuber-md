<?php
/**
 * Module Name: KaTex
 * Module Description: Use KaTex markup for complex equations and other geekery.
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Githuber
 * @since 1.1.0
 * @version 1.0.0
 */

namespace Githuber\Module;

class KaTeX extends ModuleAbstract {

	/**
	 * The version of KaTeX we are using.
	 *
	 * @var string
	 */
	public $katex_version = '0.10.0';

	/**
	 * The priority order to load CSS file, the value should be higher than theme's.
	 * Overwrite the theme's style it's safe to display the correct syntax highlight.
	 *
	 * @var integer
	 */
	public $css_priority = 1000;

	/**
	 * Constants.
	 */
	const MD_POST_META_KATEX    = '_is_githuber_katex';

	/**
	 * Constructer.
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Initialize.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'wp_enqueue_scripts', array( $this, 'front_enqueue_styles'), $this->css_priority );
		add_action( 'wp_enqueue_scripts', array( $this, 'front_enqueue_scripts' ) );
		add_action( 'wp_print_footer_scripts', array( $this, 'front_print_footer_scripts' ) );
		/**
		 * Just for debug propose, don't use it. 
		 * Because it will excute the regex search in every page views, waste your CPU usage.
		 * Uncomment it if you would like to debug the `katex_markup` method.
		 * 
		 * add_filter( 'the_content', array( $this, 'katex_markup' ), 9 );
		 * add_filter( 'comment_text', array( $this, 'katex_markup' ), 9 );
		 * 
		 * You also need to comment the line in `Controller/Mardown`
		 * $text = Module\KaTeX::katex_markup( $text );
		 */
	}
 
	/**
	 * Register CSS style files for frontend use.
	 * 
	 * @return void
	 */
	public function front_enqueue_styles() {
		if ( $this->is_module_should_be_loaded( self::MD_POST_META_KATEX ) ) {
			
			$option = githuber_get_option( 'katex_src', 'githuber_modules' );

			switch ( $option ) {
				case 'cloudflare':
					$style_url = 'https://cdnjs.cloudflare.com/ajax/libs/KaTeX/' . $this->katex_version . '/katex.min.css';
					break;

				case 'jsdelivr':
					$style_url = 'https://cdn.jsdelivr.net/npm/katex@' . $this->katex_version . '/dist/katex.min.css';
					break;

				default:
					$style_url = $this->githuber_plugin_url . 'assets/vendor/katex/katex.min.css';
					break;
			} 
			wp_enqueue_style( 'katex', $style_url, array(), $this->katex_version, 'all' );
		}
	}

	/**
	 * Register JS files for frontend use.
	 * 
	 * @return void
	 */
	public function front_enqueue_scripts() {
		if ( $this->is_module_should_be_loaded( self::MD_POST_META_KATEX ) ) {

			$option = githuber_get_option( 'katex_src', 'githuber_modules' );

			switch ( $option ) {
				case 'cloudflare':
					$script_url = 'https://cdnjs.cloudflare.com/ajax/libs/KaTeX/' . $this->katex_version . '/katex.min.js';
					break;

				case 'jsdelivr':
					$script_url = 'https://cdn.jsdelivr.net/npm/katex@' . $this->katex_version . '/dist/katex.min.js';
					break;

				default:
					$script_url = $this->githuber_plugin_url . 'assets/vendor/katex/katex.min.js';
					break;
			} 
			wp_enqueue_script( 'katex', $script_url, array(), $this->katex_version, true );
		}
	}

	/**
	 * Katex Markup
	 * 
	 * Ex.
	 * $$ x_{1,2} = {-b\pm\sqrt{b^2 - 4ac} \over 2a}.$$
	 *
	 * @param string  $content HTML or Markdown content.
	 * @param integer $post_id Post Id.
	 * @return void
	 */
	public static function katex_markup( $content ) {

		$regex = '%
			\$\$*
			((?:
				[^$]+ # Not a dollar
			|
				(?<=(?<!\\\\)\\\\)\$ # Dollar preceded by exactly one slash
			)+)
			(?<!\\\\)\$*\$ # Dollar preceded by zero slashes
		%ix';

		$content = preg_replace_callback( $regex, function() {
			$matches = func_get_arg(0);

			if ( ! empty( $matches[1] ) ) {
				$katex = $matches[1];
				$katex = str_replace( array( '&lt;', '&gt;', '&quot;', '&#039;', '&#038;', '&amp;', "\n", "\r" ), array( '<', '>', '"', "'", '&', '&', ' ', ' ' ), $katex );
				return '<div class="katex-container">' . trim( $katex ) . '</div>';
			}
		}, $content );

		$restore_item = "<code>\$\$\n$1\n\$\$</code>";

		// Restore original string in <code> blocks.
		$content = preg_replace( "#<code><div class=\"katex-container\">(.*?)</div></code>#", $restore_item, $content );
		return $content;
	}

	/**
	 * Print Javascript plaintext in page footer.
	 */
	public function front_print_footer_scripts() {
		$script = '
			<script>
				(function($) {
					$(function() {
						if ($(".katex-container").length > 0) {
							$(".katex-container").each(function() {
								var katexText = $(this).text();
								var el = $( this ).get( 0 );
								if ($(this).parent("code").length == 0) {
									try {
										katex.render(katexText, el)
									} catch (err) {
										$(this).html("<span class=\'err\'>" + err)
									}
								}
							});
						}
					});
				})(jQuery);
			</script>
		';
		echo $script;
	}
}
