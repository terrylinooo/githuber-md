<?php
/**
 * Githuber_Walker
 * Custom nav menu by using Bootscrap 4.
 * Bootscrap 4 CSS file is required.
 * https://getbootstrap.com/docs/4.1/getting-started/download/
 *
 * This is a stable version and will not be modified anymore.
 *
 * @package   WordPress
 * @author    Terry Lin <terrylinooo>
 * @license   GPLv3 (or later)
 * @link      https://terryl.in
 * @copyright 2018 Terry Lin
 */

/**
 * Githuber_Walker
 */
class Githuber_Walker extends Walker_Nav_Menu {
	/**
	 * Constructer.
	 */
	public function __construct() {
		add_filter( 'nav_menu_css_class', function( $classes, $item, $args, $depth ) {
			unset( $classes );
			return array();
		}, 10, 4 );
	}

	/**
	 * Starts the list before the elements are added.
	 *
	 * @param string  $output Used to append additional content (passed by reference).
	 * @param integer $depth  Depth of menu item. Used for padding.
	 * @param array   $args   An object of wp_nav_menu() arguments.
	 * @return void
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		$output .= '<div class="dropdown-menu">';
	}

	/**
	 * Ends the list of after the elements are added.
	 *
	 * @param string  $output Used to append additional content (passed by reference).
	 * @param integer $depth  Depth of menu item. Used for padding.
	 * @param array   $args   An object of wp_nav_menu() arguments.
	 * @return void
	 */
	public function end_lvl( &$output, $depth = 0, $args = array() ) {
		$output .= '</div>';
	}

	/**
	 * Starts the element output.
	 *
	 * @param string  $output Used to append additional content (passed by reference).
	 * @param object  $item   Menu item data object.
	 * @param integer $depth  Depth of menu item. Used for padding.
	 * @param array   $args   An object of wp_nav_menu() arguments.
	 * @param integer $id     Current item ID.
	 * @return void
	 */
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$classes      = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[]    = 'menu-item-' . $item->ID;
		$class_names  = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names .= ' nav-item';

		if ( in_array( 'menu-item-has-children', $classes, true ) ) {
			$class_names .= ' dropdown';
		}

		if ( in_array( 'current-menu-item', $classes, true ) ) {
			$class_names .= ' active';
		}

		$class_names = $class_names ? ' class="' . esc_attr( trim( $class_names ) ) . '"' : '';
		$class_names = trim( $class_names );
		$id          = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
		$id          = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		if ( 0 === $depth ) {
			$output .= '<li ' . $id . $class_names . '>';
		}

		$atts = array();

		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target ) ? $item->target : '';
		$atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
		$atts['href']   = ! empty( $item->url ) ? $item->url : '';

		if ( 0 === $depth ) {
			$atts['class'] = 'nav-link';
		}

		if ( 0 === $depth && in_array( 'menu-item-has-children', $classes, true ) ) {
			$atts['class']      .= ' dropdown-toggle';
			$atts['data-toggle'] = 'dropdown';
		}

		if ( $depth > 0 ) {
			$manual_class  = array_values( $classes )[0] . ' dropdown-item';
			$atts['class'] = trim( $manual_class );
		}

		if ( in_array( 'current-menu-item', $item->classes, true ) ) {
			$atts['class'] .= ' active';
		}

		$atts       = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );
		$attributes = '';

		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		$item_output  = $args->before;
		$item_output .= '<a' . $attributes . '>';
		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;
		$output      .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	/**
	 * Ends the element output, if needed.
	 *
	 * @param string  $output Used to append additional content (passed by reference).
	 * @param object  $item   Page data object. Not used.
	 * @param integer $depth  Depth of page. Not Used.
	 * @param array   $args   An object of wp_nav_menu() arguments.
	 * @return void
	 */
	public function end_el( &$output, $item, $depth = 0, $args = array() ) {
		if ( 0 === $depth ) {
			$output .= '</li>';
		}
	}
}
