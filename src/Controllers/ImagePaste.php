<?php
/**
 * Class ImagePaste
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Githuber
 * @since 1.0.1
 * @version 1.1.0
 */

namespace Githuber\Controller;

class ImagePaste extends ControllerAbstract {

	/**
	 * The version of inline-attachment.js we are using.
	 *
	 * @var string
	 */
	public $imagepaste_version = '2.0.3';

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
		$user          = wp_get_current_user();
		$allowed_roles = array( 'editor', 'administrator', 'author' );

		// For security reasons, only authorized logged-in users can upload images.
		if ( array_intersect( $allowed_roles, $user->roles ) ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
			add_action( 'wp_ajax_githuber_image_paste', array( $this, 'admin_githuber_image_paste' ) );
		}
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
		wp_enqueue_script( 'image-paste', $this->githuber_plugin_url . 'assets/vendor/inline-attachment/inline-attachment.min.js', array(), $this->imagepaste_version, true );
		wp_enqueue_script( 'image-paste-codemirror', $this->githuber_plugin_url . 'assets/vendor/inline-attachment/codemirror-4.inline-attachment.min.js', array(), $this->imagepaste_version, true );
	}

	/**
	 * Do action hook for image paste.
	 */
	public function admin_githuber_image_paste() {
		$upload_dir  = wp_upload_dir();
		$upload_path = $upload_dir['path'];
		$online_path = $upload_dir['url'];
		$response    = array();
		
		if ( isset($_FILES['file']) ) {
			$file = $_FILES['file'];
			$filename = uniqid() . '.' . ( pathinfo( $file['name'], PATHINFO_EXTENSION ) ? : 'png' );

			move_uploaded_file( $file['tmp_name'], $upload_path . '/' . $filename );
		
			$response['filename'] = $online_path . '/' . $filename;
		} else {
			$response['error'] = __( 'Error while uploading file.', $this->text_domain );
		}
		echo json_encode($response);

		// Avoid wp_ajax return "0" string to break the vaild json string.
		wp_die();
	}
}