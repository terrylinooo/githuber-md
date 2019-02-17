<?php
/**
 * Class RichEditing
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Githuber
 * @since 1.6.0
 * @version 1.6.0
 */

namespace Githuber\Controller;

class PostType {

    const MD_POST_TYPE = 'githuber_markdown';

    public $is_editor = array();

	/**
	 * Constructer.
	 */
	public function __construct() {

	}

	/**
	 * Initialize.
	 */
	function init() {

		$support_post_types = get_post_types( array( 'public' => true ) );
		$enabled_post_types = githuber_get_option( 'enable_markdown_for_post_types', 'githuber_markdown' );

		$support_post_types[] = 'revision';

		$post_types = array();

		foreach($support_post_types as $post_type) {
			if( 'attachment' !== $post_type ) {
				$support_post_types[] = $post_type;
			}
		}

		$support_post_types = apply_filters( 'githuber_md_suppot_post_types', $support_post_types );

		foreach ( $support_post_types as $post_type ) {

			if ( isset( $enabled_post_types[ $post_type ] ) || 'revision' === $post_type ) {
				add_post_type_support( $post_type, self::MD_POST_TYPE );
			}
		}
    }
    
	/**
	 * Is editor enabled?
	 *
	 * @return boolean
	 */
	public function is_editor_enabled() {

		if ( empty( $_REQUEST['post_type'] ) ) {
			return true;
		} else {
			$post_type = $_REQUEST['post_type'];

			if ( ! empty( $this->is_editor[ $post_type ] ) || empty( $post_type ) )  {
				return true;
			}
		}

		return false;
	}
}
