<?php

/**
 * Class ModelAbstract
 * 
 * Models are specifically used for dealing with the data exchange between controller and database.
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Githuber
 * @since 1.0.0
 * @version 1.0.0
 */

namespace Githuber\Model;

abstract class ModelAbstract {

	/**
	 * WP DB instance.
	 *
	 * @var object
	 */
	public $db;

	/**
	 * Constructer.
	 * 
	 * @return void
	 */
	public function __construct() {

		// Get WP DB object.
		global $wpdb;

		$this->db = &$wpdb;
	}
}
