<?php
/**
 * Class HtmlToMarkdown
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Githuber
 * @since 1.3.0
 * @version 1.4.2
 */

namespace Githuber\Controller;
use League\HTMLToMarkdown\HtmlConverter;

class HtmlToMarkdown extends ControllerAbstract {

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

		// For security reasons, only authorized logged-in users can update content.
		if ( array_intersect( $allowed_roles, $user->roles ) || is_super_admin() ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
			add_action( 'wp_ajax_githuber_html2markdown', array( $this, 'admin_githuber_html2markdown' ) );

			// Add the sidebar metabox to posts.
			add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );

			// Remove auto-save function.
			add_action( 'admin_enqueue_scripts', array( $this , 'remove_autosave' ), 100 );
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
		wp_enqueue_script( 'githuber-md-h2m', $this->githuber_plugin_url . 'assets/js/githuber-md-h2m.js', array(), $this->version, true );

		$data['ajax_url'] = admin_url( 'admin-ajax.php' );
		$data['post_id']  = githuber_get_current_post_id();

		wp_localize_script( 'githuber-md-h2m', 'h2m_config', $data );
	}

	/**
	 * Remove auto-save function.
	 */
	function remove_autosave() {
		wp_dequeue_script('autosave');
	}

	/**
	 * Register the `HtmlToMarkdown` meta box in the post-editor.
	 */
	public function add_meta_box() {
		
		if ( ! githuber_current_user_can( 'edit_posts' ) ) {
			return false;
		}

		add_meta_box(
			'html2markdown_meta_box',
			__( 'HTML to Markdown', 'wp-githuber-md' ) . '<div class="bg-icon-md"></div>',
			array( $this, 'show_meta_box' ),
			null,
			'side',
			'high'
		);
	}
	
	/**
	 * Show `HtmlToMarkdown` meta box.
	 */
	public function show_meta_box() {
		echo githuber_load_view( 'metabox/html-to-markdown' );
	}

	/**
	 * Do action hook for image paste.
	 */
	public function admin_githuber_html2markdown() {
		$is_strip_tags = false;
		$is_line_break = false;
		$post_content  = '';

		$response = array(
			'success' => false,
			'result'  => '',
		);

		if ( isset( $_POST['strip_tags'] ) && 'yes' === $_POST['strip_tags'] ) {
			$is_strip_tags = true;
		}

		if ( isset( $_POST['line_break'] ) && 'yes' === $_POST['line_break'] ) {
			$is_line_break = true;
		}

		if ( ! isset( $_POST['post_id'] ) ) {
			//return;
		}

		if ( ! empty( $_POST['post_content'] ) ) {
			$post_content = $_POST['post_content'];
		}

		//$post_id = (int) $_POST['post_id'];
		//$post    = (array) get_post( $post_id );

		$converter = new HtmlConverter();
		$converter->getConfig()->setOption('strip_tags', $is_strip_tags);
		$converter->getConfig()->setOption('hard_break', $is_line_break);
		$converter->getConfig()->setOption('header_style', 'atx');

		$markdown = $converter->convert( $post_content );
		$markdown = $this->filter_wordpress_html( $markdown );

		if ( ! empty( $markdown ) ) {
			$response = array(
				'success' => true,
				'result'  => $markdown,
			);
		}

		header('Content-type: application/json');
		
		echo json_encode( $response );

		// To avoid wp_ajax return "0" string to break the vaild json string.
		wp_die();
	}

	/**
	 * Strip slash and quotes that added by jQuery AJAX.
	 *
	 * @param string HTML string
	 * @return string
	 */
	private function filter_wordpress_html( $content ) {
		$content = str_replace( '\\"', '', $content );
		$content = wp_unslash( $content );
		return $content;
	}
}
