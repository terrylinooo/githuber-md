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
 * @version 1.9.2
 */

namespace Githuber\Module;

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
		// add_action( 'wp_print_footer_scripts', array( $this, 'front_print_footer_scripts' ) );
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
	 * Print Javascript plaintext in page footer.
	 */
	public function front_print_footer_scripts() {

	}
}
