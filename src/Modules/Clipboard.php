<?php
/**
 * Module Name: Clipboard
 * Module Description: Copy text into clipboard.
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Githuber
 * @since 1.9.2
 * @version 1.10.1
 */

namespace Githuber\Module;

/**
 * Class Clipboard
 */
class Clipboard extends ModuleAbstract {

	/**
	 * The version of flowchart.js we are using.
	 *
	 * @var string
	 */
	public $clipboard_version = '2.0.4';

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
		add_action( 'wp_enqueue_scripts', array( $this, 'front_enqueue_scripts' ) );
		add_action( 'wp_print_footer_scripts', array( $this, 'front_print_footer_scripts' ) );
	}

	/**
	 * Register JS files for frontend use.
	 *
	 * @return void
	 */
	public function front_enqueue_scripts() {
		$clipboard_src = githuber_get_option( 'clipboard_src', 'githuber_modules' );

		switch ( $clipboard_src ) {
			case 'cloudflare':
				$script_url = 'https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/' . $this->clipboard_version . '/clipboard.min.js';
				break;

			case 'jsdelivr':
				$script_url = 'https://cdn.jsdelivr.net/npm/clipboard@' . $this->clipboard_version . '/dist/clipboard.min.js';
				break;

			default:
				$script_url = $this->githuber_plugin_url . 'assets/vendor/clipboard/clipboard.min.js';
				break;
		}

		wp_enqueue_script( 'clipboard', $script_url, array(), $this->clipboard_version, true );
	}

	/**
	 * Register CSS style files for frontend use.
	 */
	public function front_enqueue_styles() {

	}

	/**
	 * Print Javascript plaintext in page footer.
	 */
	public function front_print_footer_scripts() {
		$script = <<<EOL
			<script id="module-clipboard">

			document.addEventListener('DOMContentLoaded', function() {
					const preElements = document.getElementsByTagName("pre");
					let hasLanguage = false;

					for (let i = 0; i < preElements.length; i++) {
							if (preElements[i].children[0]) {
									const codeClass = preElements[i].children[0].className;
									const isLanguage = codeClass.indexOf("language-");

									const excludedCodeClassNames = [
											"language-katex",
											"language-seq",
											"language-sequence",
											"language-flow",
											"language-flowchart",
											"language-mermaid",
									];

									const isExcluded = excludedCodeClassNames.indexOf(codeClass) !== -1;

									if (!isExcluded && isLanguage !== -1) {
											const currentPre = preElements[i];
											const parent = currentPre.parentNode;
											const div = document.createElement("div");
											div.style.position = 'relative';

											parent.replaceChild(div, currentPre);

											const button = document.createElement("button");
											button.className = "copy-button";
											button.textContent = "Copy";

											div.appendChild(currentPre);
											div.appendChild(button);

											hasLanguage = true;
									}
							}
					}

					if (hasLanguage) {
							const copyCode = new ClipboardJS(".copy-button", {
									target: function(trigger) {
											return trigger.previousElementSibling;
									}
							});

							copyCode.on("success", function(event) {
									event.clearSelection();
									event.trigger.textContent = "Copied";
									window.setTimeout(function() {
											event.trigger.textContent = "Copy";
									}, 2000);
							});
					}
			});


			</script>
		EOL;

		echo preg_replace( '/\s+/', ' ', $script );
	}
}
