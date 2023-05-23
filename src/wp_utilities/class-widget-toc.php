<?php
/**
 * Githuber_Widget_Toc
 * Add a Table of Contents for your article. This widget is for single-post pages only.
 *
 * @package   WordPress
 * @author    Terry Lin <terrylinooo>
 * @license   GPLv3 (or later)
 * @link      https://terryl.in
 * @copyright 2018 Terry Lin
 */

/**
 * Githuber_Widget_Toc
 */
class Githuber_Widget_Toc extends WP_Widget {

	/**
	 * Sets up a new Githuber TOC widget instance.
	 */
	public function __construct() {

		$widget_ops = array(
			'classname'                   => 'widget_githuber_toc',
			'description'                 => __( 'Add a Table of Contents for your article. This widget is for single-post pages only.', 'wp-githuber-md' ),
			'customize_selective_refresh' => true,
		);

		parent::__construct( 'githuber-toc', __( 'Githuber MD: TOC', 'wp-githuber-md' ), $widget_ops );
		$this->alt_option_name = 'widget_githuber_toc';
	}

	/**
	 * Initial TOC .
	 */
	public function githuber_toc_inline_js() {

	}

	/**
	 * Outputs the content for the Githuber TOC instance.
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 * @param array $instance The settings for the particular instance of the widget.
	 */
	public function widget( $args, $instance ) {
		$title  = apply_filters( 'widget_title', $instance['title'] );
		$output = $args['before_widget'];

		if ( ! empty( $title ) ) {
			$output .= $args['before_title'];
			$output .= $title;
			$output .= $args['after_title'];
		}

		$output .= '<nav id="md-widget-toc" class="md-widget-toc" role="navigation"></nav>';
		$output .= $args['after_widget'];

		echo $output;
	}

	/**
	 * Outputs the options form on admin.
	 *
	 * @param array $instance The settings for the particular instance of the widget.
	 * @return void
	 */
	public function form( $instance ) {
		if ( isset( $instance['title'] ) ) {
			$title = $instance['title'];
		} else {
			$title = __( 'New title', 'wpb_widget_domain' );
		}
		// Widget admin form
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php
	}

	/**
	 * Flushes the Githuber TOC widget cache.
	 */
	public function flush_widget_cache() {
		_deprecated_function( __METHOD__, '4.4.0' );
	}
}
