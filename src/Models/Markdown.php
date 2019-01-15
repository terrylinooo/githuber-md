<?php
/**
 * Class Markdown
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Githuber
 * @since 1.0.0
 * @version 1.4.3
 */

namespace Githuber\Model;

class Markdown extends ModelAbstract {

	/**
	 * Constructer.
	 * 
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Get the latest post revision.
	 *
	 * @param int $post_id The post ID
	 *
	 * @return object Post data
	 */
	function get_lastest_revision( $post_id ) {
		return $this->db->get_row(
			$this->db->prepare(
				"SELECT * FROM {$this->db->posts} WHERE post_type = 'revision' AND post_parent = %d ORDER BY ID DESC", 
				$post_id
			)
		);
	}
}