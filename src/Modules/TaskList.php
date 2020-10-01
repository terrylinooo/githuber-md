<?php
/**
 * Module Name: TaskList
 * Module Description: upport Github Flavored Markdown task lists.
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Githuber
 * @since 1.0.0
 * @version 1.0.0
 */

namespace Githuber\Module;

class TaskList {

	/**
	 * Support Github Flavored Markdown task lists.
	 * 
	 * @param string $text HTML content.
	 * @return string filtered HTML content.
	 */
	public static function parse_gfm_task_list( $text ) {
		$checked_item   = '<li class="gfm-task-list"><input type="checkbox">$1$2';
		$unchecked_item = '<li class="gfm-task-list"><input type="checkbox" checked>$1$2';

		// Replace task-list signs to corresponding HTML code.
		$text = preg_replace( "#<li>\[\s\] (.*?)([</li>|<ul>])#", $checked_item, $text );
		$text = preg_replace( "#<li>\[[x]\] (.*?)([</li>|<ul>])#", $unchecked_item, $text );
		return $text;
	}
}
