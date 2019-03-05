<?php
/**
 * Class Custom Media Livrary
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Githuber
 * @since 1.6.2
 * @version 1.6.2
 */

namespace Githuber\Controller;

class CustomMediaLibrary extends ControllerAbstract {

	/**
	 * Constructer.
	 */
	public function __construct() {
        parent::__construct();
	}

	/**
	 * Initialize.
	 */
	public function init() {
		add_action( 'admin_init', array( $this, 'admin_init' ) );
    }

	/**
	 * Initalize to WP `admin_init` hook.
	 */
	public function admin_init() {
        add_filter( 'attachment_fields_to_edit', array( $this, 'attachment_fields_to_edit' ) , 10, 2 );
    }

	/**
	 * Register CSS style files.
	 */
	public function admin_enqueue_styles( $hook_suffix ) {

	}

	/**
	 * Register JS files.
	 */
	public function admin_enqueue_scripts( $hook_suffix ) {

	}

    /**
     * Show custom media field.
     *
     * @param array $form_fields
     * @param object $post
     * @return void
     */
    function attachment_fields_to_edit( $form_fields, $post = null ) {
 
        $form_fields['githuber_image_insert'] = array(
            'value' => 'markdown',
            'label' => __( 'Code type', 'wp-githuber-md' ),
            'input' => 'html',
            'html'  => githuber_load_view( 'metabox/custom-media-library' ),
        );

        return $form_fields;
    }
}
