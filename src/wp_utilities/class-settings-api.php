<?php

/**
 * Githuber MD Settings API wrapper class
 *
 * @author Terry Lin
 * @package Githuber
 * @since 1.0.0
 * @version 1.7.0
 * @license GPLv3
 *
 * Notice:
 * This script is modified a lot by Terry L. for Githuber MD plugin use.
 * If you're looking for original code, go visit https://github.com/tareq1988/wordpress-settings-api-class
 *
 * @version 1.3 (27-Sep-2016)
 * @author Tareq Hasan <tareq@weDevs.com>
 * @link https://tareq.co Tareq Hasan
 * @license MIT
 */

class Githuber_Settings_API {

	/**
	 * settings sections array
	 *
	 * @var array
	 */
	protected $settings_sections = array();

	/**
	 * Settings fields array
	 *
	 * @var array
	 */
	protected $settings_fields = array();

	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}

	/**
	 * Enqueue scripts and styles
	 */
	function admin_enqueue_scripts() {
		wp_enqueue_style( 'wp-color-picker' );

		wp_enqueue_media();
		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_script( 'jquery' );

		wp_enqueue_script( 'prettify-print', GITHUBER_PLUGIN_URL . 'assets/vendor/editor.md/lib/prettify.min.js', array( 'jquery' ), '1.0', true );
		wp_enqueue_script( 'setting-api', GITHUBER_PLUGIN_URL . 'assets/js/githuber-md-setting-api.js', array( 'jquery' ), GITHUBER_PLUGIN_VERSION, true );
	}

	/**
	 * Set settings sections
	 *
	 * @param array   $sections setting sections array
	 */
	function set_sections( $sections ) {
		$this->settings_sections = $sections;

		return $this;
	}

	/**
	 * Add a single section
	 *
	 * @param array   $section
	 */
	function add_section( $section ) {
		$this->settings_sections[] = $section;

		return $this;
	}

	/**
	 * Set settings fields
	 *
	 * @param array   $fields settings fields array
	 */
	function set_fields( $fields ) {
		$this->settings_fields = $fields;

		return $this;
	}

	function add_field( $section, $field ) {
		$defaults = array(
			'name'  => '',
			'label' => '',
			'desc'  => '',
			'type'  => 'text'
		);

		$arg = wp_parse_args( $field, $defaults );
		$this->settings_fields[$section][] = $arg;

		return $this;
	}

	/**
	 * Initialize and registers the settings sections and fileds to WordPress
	 *
	 * Usually this should be called at `admin_init` hook.
	 *
	 * This function gets the initiated settings sections and fields. Then
	 * registers them to WordPress and ready for use.
	 */
	function admin_init() {
		//register settings sections
		foreach ( $this->settings_sections as $section ) {
			if ( false == get_option( $section['id'] ) ) {
				add_option( $section['id'] );
			}

			if ( isset($section['desc']) && !empty($section['desc']) ) {
				$section['desc'] = '<div class="inside">' . $section['desc'] . '</div>';
				$callback = function() use ( $section ) {
					echo str_replace( '"', '\"', $section['desc'] );
				};
			} else if ( isset( $section['callback'] ) ) {
				$callback = $section['callback'];
			} else {
				$callback = null;
			}

			$page_title = '<span class="g-tab-title">' . $section['title'] . '</span>';

			add_settings_section( $section['id'] . '_0', $page_title, $callback, $section['id'] );
		}

		//register settings fields
		foreach ( $this->settings_fields as $section => $field ) {
			$i = 0;
			$next_section_group = $section . '_' . $i;

			foreach ( $field as $option ) {
				$i++;
				$name = isset( $option['name'] ) ? $option['name'] : 'No name';
				$type = isset( $option['type'] ) ? $option['type'] : 'text';
				$label = isset( $option['label'] ) ? $option['label'] : '';
				$callback = isset( $option['callback'] ) ? $option['callback'] : array( $this, 'callback_' . $type );
				
				if ( isset( $option['section_title'] ) && true === $option['section_title'] ) {
					// Create a section in same page if $name is empty.
					$next_section_group = $section . '_' . $i;
					$location_id        = isset( $option['location_id'] ) ? $option['location_id'] : '';
					$section_title      = '<span class="g-section-title" id="' . $location_id . '">' . $option['label'] . '</span>';

					if ( ! empty( $option['desc'] )) {
						$section_title = '<span class="g-section-title" id="' . $location_id . '">' . $option['label'] . '<span class="g-section-title-desc">' . $option['desc'] . '</span></span>';
					}
					add_settings_section( $next_section_group, $section_title, '', $section);
					
				} else {
					$args = array(
						'id'                => $name,
						'class'             => isset( $option['class'] ) ? $option['class'] : $name,
						'label_for'         => "{$section}[{$name}]",
						'desc'              => isset( $option['desc'] ) ? $option['desc'] : '',
						'name'              => $label,
						'section'           => $section,
						'size'              => isset( $option['size'] ) ? $option['size'] : null,
						'options'           => isset( $option['options'] ) ? $option['options'] : '',
						'std'               => isset( $option['default'] ) ? $option['default'] : '',
						'sanitize_callback' => isset( $option['sanitize_callback'] ) ? $option['sanitize_callback'] : '',
						'type'              => $type,
						'placeholder'       => isset( $option['placeholder'] ) ? $option['placeholder'] : '',
						'min'               => isset( $option['min'] ) ? $option['min'] : '',
						'max'               => isset( $option['max'] ) ? $option['max'] : '',
						'step'              => isset( $option['step'] ) ? $option['step'] : '',
						'location_id'       => isset( $option['location_id'] ) ? $option['location_id'] : '',
						'parent'            => isset( $option['parent'] ) ? $option['parent'] : '',
						'has_child'         => isset( $option['has_child'] ) ? $option['has_child'] : '',
					);
	
					add_settings_field( "{$section}[{$name}]", $label, $callback, $section, $next_section_group, $args );
				}
			}
		}

		// creates our settings in the options table
		foreach ( $this->settings_sections as $section ) {
			register_setting( $section['id'], $section['id'], array( $this, 'sanitize_options' ) );
		}
	}

	/**
	 * Get field description for display
	 *
	 * @param array   $args settings field args
	 */
	public function get_field_description( $args ) {
		if ( ! empty( $args['desc'] ) ) {
			$desc = sprintf( '<p class="description">%s</p>', $args['desc'] );
		} else {
			$desc = '';
		}

		return $desc;
	}

	/**
	 * Displays a text field for a settings field
	 *
	 * @param array   $args settings field args
	 */
	function callback_text( $args ) {

		$value       = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
		$size        = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular';
		$type        = isset( $args['type'] ) ? $args['type'] : 'text';
		$placeholder = empty( $args['placeholder'] ) ? '' : ' placeholder="' . $args['placeholder'] . '"';

		$html        = sprintf( '<input type="%1$s" class="%2$s-text" id="%3$s[%4$s]" name="%3$s[%4$s]" value="%5$s"%6$s/>', $type, $size, $args['section'], $args['id'], $value, $placeholder );
		$html       .= $this->get_field_description( $args );

		if ( ! empty( $args['parent'] ) ) {
			$html = '<div class="setting-has-parent" data-parent="' . $args['parent'] . '">' . $html . '</div>';
		}

		echo $html;
	}

	/**
	 * Displays a url field for a settings field
	 *
	 * @param array   $args settings field args
	 */
	function callback_url( $args ) {
		$this->callback_text( $args );
	}

	/**
	 * Displays a number field for a settings field
	 *
	 * @param array   $args settings field args
	 */
	function callback_number( $args ) {
		$value       = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
		$size        = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular';
		$type        = isset( $args['type'] ) ? $args['type'] : 'number';
		$placeholder = empty( $args['placeholder'] ) ? '' : ' placeholder="' . $args['placeholder'] . '"';
		$min         = ( $args['min'] == '' ) ? '' : ' min="' . $args['min'] . '"';
		$max         = ( $args['max'] == '' ) ? '' : ' max="' . $args['max'] . '"';
		$step        = ( $args['step'] == '' ) ? '' : ' step="' . $args['step'] . '"';

		$html        = sprintf( '<input type="%1$s" class="%2$s-number" id="%3$s[%4$s]" name="%3$s[%4$s]" value="%5$s"%6$s%7$s%8$s%9$s/>', $type, $size, $args['section'], $args['id'], $value, $placeholder, $min, $max, $step );
		$html       .= $this->get_field_description( $args );

		if ( ! empty( $args['parent'] ) ) {
			$html = '<div class="setting-has-parent" data-parent="' . $args['parent'] . '">' . $html . '</div>';
		}

		echo $html;
	}

	/**
	 * Displays a checkbox for a settings field
	 *
	 * @param array   $args settings field args
	 */
	function callback_checkbox( $args ) {

		$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
		$html  = '<fieldset>';
		$html  .= sprintf( '<label for="wpuf-%1$s-%2$s">', $args['section'], $args['id'] );
		$html  .= sprintf( '<input type="hidden" name="%1$s[%2$s]" value="off" />', $args['section'], $args['id'] );
		$html  .= sprintf( '<input type="checkbox" class="checkbox" id="wpuf-%1$s-%2$s" name="%1$s[%2$s]" value="on" %3$s />', $args['section'], $args['id'], checked( $value, 'on', false ) );
		$html  .= sprintf( '%1$s</label>', $args['desc'] );
		$html  .= '</fieldset>';

		if ( ! empty( $args['parent'] ) ) {
			$html = '<div class="setting-has-parent" data-parent="' . $args['parent'] . '">' . $html . '</div>';
		}

		echo $html;
	}

	/**
	 * Displays a toggle for a settings field
	 *
	 * @param array   $args settings field args
	 */
	function callback_toggle( $args ) {

		$value     = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
		$location  = isset( $args['location_id'] ) ? $args['location_id'] : '';
		$has_child = isset( $args['has_child'] ) ? 'has-child' : '';
		$size      = isset( $args['size'] ) ? $args['size'] : '';

		$html = '';
		$html .= sprintf( '<div class="wpmd setting-toggle %4$s %5$s" data-location="%1$s" data-target="%2$s[%3$s]" data-setting="%3$s">', $location, $args['section'], $args['id'], $has_child, $size );
		$html .= sprintf( '<input type="hidden" name="%1$s[%2$s]" value="no" />', $args['section'], $args['id'] );
		$html .= sprintf( '<input type="checkbox" class="checkbox" id="wpuf-%1$s-%2$s" name="%1$s[%2$s]" value="yes" %3$s />', $args['section'], $args['id'], checked( $value, 'yes', false ) );
		$html .= sprintf( '<label for="wpuf-%1$s-%2$s">', $args['section'], $args['id'] );
		$html .= sprintf( '%1$s</label>', 'toggle' );
		$html .= '</div>';
		$html .= $this->get_field_description( $args );
		$html .= '</fieldset>';

		if ( ! empty( $args['parent'] ) ) {
			$html = '<div class="setting-has-parent" data-parent="' . $args['parent'] . '">' . $html . '</div>';
		}

		echo $html;
	}

	/**
	 * Displays a multicheckbox for a settings field
	 *
	 * @param array   $args settings field args
	 */
	function callback_multicheck( $args ) {

		$value = $this->get_option( $args['id'], $args['section'], $args['std'] );
		$html  = '<fieldset>';
		$html .= sprintf( '<input type="hidden" name="%1$s[%2$s]" value="" />', $args['section'], $args['id'] );

		$option_count = count( $args['options'] );

		foreach ( $args['options'] as $key => $label ) {
			$checked = isset( $value[$key] ) ? $value[$key] : '0';

			if ( $option_count < 5 ) {
				$html .= '<div>';
			} else {
				$html .= '<div style="display: inline-block; margin-right: 15px;">';
			}
			$html    .= sprintf( '<label for="wpuf-%1$s-%2$s-%3$s">', $args['section'], $args['id'], $key );
			$html    .= sprintf( '<input type="checkbox" class="checkbox" id="wpuf-%1$s-%2$s-%3$s" name="%1$s[%2$s][%3$s]" value="%3$s" %4$s />', $args['section'], $args['id'], $key, checked( $checked, $key, false ) );
			$html    .= sprintf( '%1$s</label>',  $label );
			$html .= '</div>';
		}

		$html .= $this->get_field_description( $args );
		$html .= '</fieldset>';

		if ( ! empty( $args['parent'] ) ) {
			$html = '<div class="setting-has-parent" data-parent="' . $args['parent'] . '">' . $html . '</div>';
		}

		echo $html;
	}

	/**
	 * Displays a radio button for a settings field
	 *
	 * @param array   $args settings field args
	 */
	function callback_radio( $args ) {

		$value = $this->get_option( $args['id'], $args['section'], $args['std'] );
		$html  = '<fieldset>';

		foreach ( $args['options'] as $key => $label ) {
			$html .= sprintf( '<label for="wpuf-%1$s-%2$s-%3$s">',  $args['section'], $args['id'], $key );
			$html .= sprintf( '<input type="radio" class="radio" id="wpuf-%1$s-%2$s-%3$s" name="%1$s[%2$s]" value="%3$s" %4$s />', $args['section'], $args['id'], $key, checked( $value, $key, false ) );
			$html .= sprintf( '%1$s</label><br>', $label );
		}

		$html .= $this->get_field_description( $args );
		$html .= '</fieldset>';

		if ( ! empty( $args['parent'] ) ) {
			$html = '<div class="setting-has-parent" data-parent="' . $args['parent'] . '">' . $html . '</div>';
		}

		echo $html;
	}

	/**
	 * Displays a selectbox for a settings field
	 *
	 * @param array   $args settings field args
	 */
	function callback_select( $args ) {

		$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
		$size  = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular';
		$html  = sprintf( '<select class="%1$s" name="%2$s[%3$s]" id="%2$s[%3$s]">', $size, $args['section'], $args['id'] );

		foreach ( $args['options'] as $key => $label ) {
			$html .= sprintf( '<option value="%s"%s>%s</option>', $key, selected( $value, $key, false ), $label );
		}

		$html .= sprintf( '</select>' );
		$html .= $this->get_field_description( $args );

		if ( ! empty( $args['parent'] ) ) {
			$html = '<div class="setting-has-parent" data-parent="' . $args['parent'] . '">' . $html . '</div>';
		}

		echo $html;
	}

	/**
	 * Displays a textarea for a settings field
	 *
	 * @param array   $args settings field args
	 */
	function callback_textarea( $args ) {

		$value       = esc_textarea( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
		$size        = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular';
		$placeholder = empty( $args['placeholder'] ) ? '' : ' placeholder="'.$args['placeholder'].'"';

		$html        = sprintf( '<textarea rows="5" cols="55" class="%1$s-text" id="%2$s[%3$s]" name="%2$s[%3$s]"%4$s>%5$s</textarea>', $size, $args['section'], $args['id'], $placeholder, $value );
		$html        .= $this->get_field_description( $args );

		if ( ! empty( $args['parent'] ) ) {
			$html = '<div class="setting-has-parent" data-parent="' . $args['parent'] . '">' . $html . '</div>';
		}

		echo $html;
	}

	/**
	 * Displays the html for a settings field
	 *
	 * @param array   $args settings field args
	 * @return string
	 */
	function callback_html( $args ) {

		$html = $args['desc'];

		if ( ! empty( $args['parent'] ) ) {
			$html = '<div class="setting-has-parent" data-parent="' . $args['parent'] . '">' . $html . '</div>';
		}

		echo $html;
	}

	/**
	 * Displays a rich text textarea for a settings field
	 *
	 * @param array   $args settings field args
	 */
	function callback_wysiwyg( $args ) {

		$value = $this->get_option( $args['id'], $args['section'], $args['std'] );
		$size  = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : '500px';

		if ( ! empty( $args['parent'] ) ) {
			echo '<div class="setting-has-parent" data-parent="' . $args['parent'] . '">';
		}

		echo '<div style="max-width: ' . $size . ';">';

		$editor_settings = array(
			'teeny'         => true,
			'textarea_name' => $args['section'] . '[' . $args['id'] . ']',
			'textarea_rows' => 10
		);

		if ( isset( $args['options'] ) && is_array( $args['options'] ) ) {
			$editor_settings = array_merge( $editor_settings, $args['options'] );
		}

		wp_editor( $value, $args['section'] . '-' . $args['id'], $editor_settings );

		echo '</div>';

		echo $this->get_field_description( $args );

		if ( ! empty( $args['parent'] ) ) {
			echo '</div>';
		}
	}

	/**
	 * Displays a file upload field for a settings field
	 *
	 * @param array   $args settings field args
	 */
	function callback_file( $args ) {

		$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
		$size  = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular';
		$id    = $args['section']  . '[' . $args['id'] . ']';
		$label = isset( $args['options']['button_label'] ) ? $args['options']['button_label'] : __( 'Choose File' );

		$html  = sprintf( '<input type="text" class="%1$s-text wpsa-url" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s"/>', $size, $args['section'], $args['id'], $value );
		$html  .= '<input type="button" class="button wpsa-browse" value="' . $label . '" />';
		$html  .= $this->get_field_description( $args );

		if ( ! empty( $args['parent'] ) ) {
			$html = '<div class="setting-has-parent" data-parent="' . $args['parent'] . '">' . $html . '</div>';
		}

		echo $html;
	}

	/**
	 * Displays a password field for a settings field
	 *
	 * @param array   $args settings field args
	 */
	function callback_password( $args ) {

		$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
		$size  = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular';

		$html  = sprintf( '<input type="password" class="%1$s-text" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s"/>', $size, $args['section'], $args['id'], $value );
		$html  .= $this->get_field_description( $args );

		if ( ! empty( $args['parent'] ) ) {
			$html = '<div class="setting-has-parent" data-parent="' . $args['parent'] . '">' . $html . '</div>';
		}

		echo $html;
	}

	/**
	 * Displays a color picker field for a settings field
	 *
	 * @param array   $args settings field args
	 */
	function callback_color( $args ) {

		$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
		$size  = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular';

		$html  = sprintf( '<input type="text" class="%1$s-text wp-color-picker-field" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s" data-default-color="%5$s" />', $size, $args['section'], $args['id'], $value, $args['std'] );
		$html  .= $this->get_field_description( $args );

		if ( ! empty( $args['parent'] ) ) {
			$html = '<div class="setting-has-parent" data-parent="' . $args['parent'] . '">' . $html . '</div>';
		}

		echo $html;
	}


	/**
	 * Displays a select box for creating the pages select box
	 *
	 * @param array   $args settings field args
	 */
	function callback_pages( $args ) {

		$dropdown_args = array(
			'selected' => esc_attr($this->get_option($args['id'], $args['section'], $args['std'] ) ),
			'name'     => $args['section'] . '[' . $args['id'] . ']',
			'id'       => $args['section'] . '[' . $args['id'] . ']',
			'echo'     => 0
		);
		$html = wp_dropdown_pages( $dropdown_args );

		if ( ! empty( $args['parent'] ) ) {
			$html = '<div class="setting-has-parent" data-parent="' . $args['parent'] . '">' . $html . '</div>';
		}

		echo $html;
	}

	/**
	 * Sanitize callback for Settings API
	 *
	 * @return mixed
	 */
	function sanitize_options( $options ) {

		if ( !$options ) {
			return $options;
		}

		foreach( $options as $option_slug => $option_value ) {
			$sanitize_callback = $this->get_sanitize_callback( $option_slug );

			// If callback is set, call it
			if ( $sanitize_callback ) {
				$options[ $option_slug ] = call_user_func( $sanitize_callback, $option_value );
				continue;
			}
		}

		return $options;
	}

	/**
	 * Get sanitization callback for given option slug
	 *
	 * @param string $slug option slug
	 *
	 * @return mixed string or bool false
	 */
	function get_sanitize_callback( $slug = '' ) {
		if ( empty( $slug ) ) {
			return false;
		}

		// Iterate over registered fields and see if we can find proper callback
		foreach( $this->settings_fields as $section => $options ) {
			foreach ( $options as $option ) {
				if ( ! isset( $option['name'] ) || $option['name'] != $slug ) {
					continue;
				}

				// Return the callback name
				return isset( $option['sanitize_callback'] ) && is_callable( $option['sanitize_callback'] ) ? $option['sanitize_callback'] : false;
			}
		}

		return false;
	}

	/**
	 * Get the value of a settings field
	 *
	 * @param string  $option  settings field name
	 * @param string  $section the section name this field belongs to
	 * @param string  $default default text if it's not found
	 * @return string
	 */
	function get_option( $option, $section, $default = '' ) {

		$options = get_option( $section );

		if ( isset( $options[$option] ) ) {
			return $options[$option];
		}

		return $default;
	}

	/**
	 * Show navigations as tab
	 *
	 * Shows all the settings section labels as tab
	 */
	function show_navigation() {
		$html = '<h2 class="nav-tab-wrapper">';

		$count = count( $this->settings_sections );

		// don't show the navigation if only one section exists
		if ( $count === 1 ) {
			return;
		}

		foreach ( $this->settings_sections as $tab ) {
			$html .= sprintf( '<a href="#%1$s" class="nav-tab" id="%1$s-tab">%2$s</a>', $tab['id'], $tab['title'] );
		}

		$html .= '</h2>';

		echo $html;
	}

	/**
	 * Show the section settings forms
	 *
	 * This function displays every sections in a different form
	 */
	function show_forms() {
		?>
		<div class="metabox-holder">
			<?php foreach ( $this->settings_sections as $form ) { ?>
				<div id="<?php echo $form['id']; ?>" class="group" style="display: none;">
					<form method="post" action="options.php">
						<?php
						do_action( 'wsa_form_top_' . $form['id'], $form );
						settings_fields( $form['id'] );
						do_settings_sections( $form['id'] );
						do_action( 'wsa_form_bottom_' . $form['id'], $form );
						if ( isset( $this->settings_fields[ $form['id'] ] ) ):
						?>
						<div style="border-top: 1px #cccccc solid; margin-top: 20px;">
							<?php submit_button(); ?>
						</div>
						<?php endif; ?>
					</form>
				</div>
			<?php } ?>
		</div>
		<?php
		$this->script();
	}

	/**
	 * Tabbable JavaScript codes & Initiate Color Picker
	 *
	 * This code uses localstorage for displaying active tabs
	 */
	function script() {
		?>
		<script>
			jQuery(document).ready(function($) {
				//Initiate Color Picker
				$('.wp-color-picker-field').wpColorPicker();

				// Switches option sections
				$('.group').hide();
				var activetab = '';
				if (typeof(localStorage) != 'undefined' ) {
					activetab = localStorage.getItem("activetab");
				}

				//if url has section id as hash then set it as active or override the current local storage value
				if(window.location.hash){
					activetab = window.location.hash;
					if (typeof(localStorage) != 'undefined' ) {
						localStorage.setItem("activetab", activetab);
					}
				}

				if (activetab != '' && $(activetab).length ) {
					$(activetab).fadeIn();
				} else {
					$('.group:first').fadeIn();
				}
				$('.group .collapsed').each(function(){
					$(this).find('input:checked').parent().parent().parent().nextAll().each(
					function(){
						if ($(this).hasClass('last')) {
							$(this).removeClass('hidden');
							return false;
						}
						$(this).filter('.hidden').removeClass('hidden');
					});
				});

				if (activetab != '' && $(activetab + '-tab').length ) {
					$(activetab + '-tab').addClass('nav-tab-active');
				}
				else {
					$('.nav-tab-wrapper a:first').addClass('nav-tab-active');
				}
				$('.nav-tab-wrapper a').click(function(evt) {
					$('.nav-tab-wrapper a').removeClass('nav-tab-active');
					$(this).addClass('nav-tab-active').blur();
					var clicked_group = $(this).attr('href');
					if (typeof(localStorage) != 'undefined' ) {
						localStorage.setItem("activetab", $(this).attr('href'));
					}
					$('.group').hide();
					$(clicked_group).fadeIn();
					evt.preventDefault();
				});

				$('.wpsa-browse').on('click', function (event) {
					event.preventDefault();

					var self = $(this);

					// Create the media frame.
					var file_frame = wp.media.frames.file_frame = wp.media({
						title: self.data('uploader_title'),
						button: {
							text: self.data('uploader_button_text'),
						},
						multiple: false
					});

					file_frame.on('select', function () {
						attachment = file_frame.state().get('selection').first().toJSON();
						self.prev('.wpsa-url').val(attachment.url).change();
					});

					// Finally, open the modal
					file_frame.open();
				});
		});
		</script>
		<?php
		$this->_style_fix();
	}

	function _style_fix() {
		global $wp_version;

		if ( version_compare($wp_version, '3.8', '<=') ):
		?>
		<style type="text/css">
			/** WordPress 3.8 Fix **/
			.form-table th { padding: 20px 10px; }
			#wpbody-content .metabox-holder { padding-top: 5px; }
		</style>
		<?php
		endif;
	}
}

