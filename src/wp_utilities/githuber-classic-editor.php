<?php
/**
 * Classic Editor
 *
 * Plugin Name: Classic Editor
 * Plugin URI:  https://wordpress.org/plugins/classic-editor/
 * Description: Enables the WordPress classic editor and the old-style Edit Post screen with TinyMCE, Meta Boxes, etc. Supports the older plugins that extend this screen.
 * Version:     1.3
 * Author:      WordPress Contributors
 * Author URI:  https://github.com/WordPress/classic-editor/
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Text Domain: classic-editor
 * Domain Path: /languages
 * Network:     true
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 2, as published by the Free Software Foundation. You may NOT assume
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Invalid request.' );
}

if ( ! class_exists( 'Classic_Editor' ) ) :
class Classic_Editor {
	const plugin_version = 1.2;
	private static $settings;
	private static $supported_post_types = array();

	private function __construct() {}

	public static function init_actions() {
		$block_editor = has_action( 'enqueue_block_assets' );
		$gutenberg = function_exists( 'gutenberg_can_edit_post_type' );

		register_activation_hook( __FILE__, array( __CLASS__, 'activate' ) );
		register_uninstall_hook( __FILE__, array( __CLASS__, 'uninstall' ) );

		$settings = self::get_settings();

		if ( is_multisite() ) {
			add_action( 'wpmu_options', array( __CLASS__, 'network_settings' ) );
			add_action( 'update_wpmu_options', array( __CLASS__, 'save_network_settings' ) );
		}

		if ( ! $settings['hide-settings-ui'] ) {
			// Show the plugin's admin settings, and a link to them in the plugins list table.
			add_filter( 'plugin_action_links', array( __CLASS__, 'add_settings_link' ), 10, 2 );
			add_action( 'admin_init', array( __CLASS__, 'register_settings' ) );

			if ( $settings['allow-users'] ) {
				// User settings.
				add_action( 'personal_options_update', array( __CLASS__, 'save_user_settings' ) );
				add_action( 'profile_personal_options', array( __CLASS__, 'user_settings' ) );
			}
		}

		// Always remove the "Try Gutenberg" dashboard widget. See https://core.trac.wordpress.org/ticket/44635.
		remove_action( 'try_gutenberg_panel', 'wp_try_gutenberg_panel' );

		if ( ! $block_editor && ! $gutenberg  ) {
			return;
		}

		if ( $settings['allow-users'] ) {
			if ( $block_editor ) {
				add_filter( 'use_block_editor_for_post', array( __CLASS__, 'choose_editor' ), 100, 2 );
			}
			if ( $gutenberg ) {
				add_filter( 'gutenberg_can_edit_post', array( __CLASS__, 'choose_editor' ), 100, 2 );

				if ( $settings['editor'] === 'classic' ) {
					self::remove_gutenberg_hooks( 'some' );
				}
			}

			add_filter( 'get_edit_post_link', array( __CLASS__, 'get_edit_post_link' ) );
			add_filter( 'redirect_post_location', array( __CLASS__, 'redirect_location' ) );
			add_action( 'edit_form_top', array( __CLASS__, 'add_redirect_helper' ) );
			add_action( 'admin_head-edit.php', array( __CLASS__, 'add_edit_php_inline_style' ) );

			add_action( 'edit_form_top', array( __CLASS__, 'remember_classic_editor' ) );
			add_filter( 'block_editor_settings', array( __CLASS__, 'remember_block_editor' ), 10, 2 );

			// Post state (edit.php)
			add_filter( 'display_post_states', array( __CLASS__, 'add_post_state' ), 10, 2 );
			// Row actions (edit.php)
			add_filter( 'page_row_actions', array( __CLASS__, 'add_edit_links' ), 15, 2 );
			add_filter( 'post_row_actions', array( __CLASS__, 'add_edit_links' ), 15, 2 );

			// Switch editors while editing a post
			add_action( 'add_meta_boxes', array( __CLASS__, 'add_meta_box' ), 10, 2 );
			// TODO: needs https://github.com/WordPress/gutenberg/pull/12309
			// add_action( 'enqueue_block_editor_assets', array( __CLASS__, 'enqueue_block_editor_scripts' ) );
		} else {
			if ( $settings['editor'] === 'classic' ) {
				if ( $block_editor ) {
					// Consider disabling other Block Editor functionality.
					add_filter( 'use_block_editor_for_post_type', '__return_false', 100 );
				}
				if ( $gutenberg ) {
					add_filter( 'gutenberg_can_edit_post_type', '__return_false', 100 );
					self::remove_gutenberg_hooks();
				}
			} else {
				// `$settings['editor'] === 'block'`, nothing to do :)
				return;
			}
		}

		if ( $block_editor ) {
			// Show warning on the "What's New" screen (about.php).
			add_action( 'all_admin_notices', array( __CLASS__, 'notice_after_upgrade' ) );
			// Move the Privacy Page notice back under the title.
			add_action( 'admin_init', array( __CLASS__, 'on_admin_init' ) );
		}
		if ( $gutenberg ) {
			// These are handled by this plugin.
			remove_action( 'admin_init', 'gutenberg_add_edit_link_filters' );
			remove_action( 'admin_print_scripts-edit.php', 'gutenberg_replace_default_add_new_button' );
			remove_filter( 'redirect_post_location', 'gutenberg_redirect_to_classic_editor_when_saving_posts' );
			remove_filter( 'display_post_states', 'gutenberg_add_gutenberg_post_state' );
			remove_action( 'edit_form_top', 'gutenberg_remember_classic_editor_when_saving_posts' );
		}
	}

	public static function remove_gutenberg_hooks( $remove = 'all' ) {
		remove_action( 'admin_menu', 'gutenberg_menu' );
		remove_action( 'admin_init', 'gutenberg_redirect_demo' );

		if ( $remove !== 'all' ) {
			return;
		}

		remove_filter( 'wp_refresh_nonces', 'gutenberg_add_rest_nonce_to_heartbeat_response_headers' );
		remove_filter( 'get_edit_post_link', 'gutenberg_revisions_link_to_editor' );
		remove_filter( 'wp_prepare_revision_for_js', 'gutenberg_revisions_restore' );

		remove_action( 'rest_api_init', 'gutenberg_register_rest_routes' );
		remove_action( 'rest_api_init', 'gutenberg_add_taxonomy_visibility_field' );
		remove_filter( 'rest_request_after_callbacks', 'gutenberg_filter_oembed_result' );
		remove_filter( 'registered_post_type', 'gutenberg_register_post_prepare_functions' );

		remove_action( 'do_meta_boxes', 'gutenberg_meta_box_save', 1000 );
		remove_action( 'submitpost_box', 'gutenberg_intercept_meta_box_render' );
		remove_action( 'submitpage_box', 'gutenberg_intercept_meta_box_render' );
		remove_action( 'edit_page_form', 'gutenberg_intercept_meta_box_render' );
		remove_action( 'edit_form_advanced', 'gutenberg_intercept_meta_box_render' );
		remove_filter( 'redirect_post_location', 'gutenberg_meta_box_save_redirect' );
		remove_filter( 'filter_gutenberg_meta_boxes', 'gutenberg_filter_meta_boxes' );

		remove_action( 'admin_notices', 'gutenberg_build_files_notice' );
		remove_filter( 'body_class', 'gutenberg_add_responsive_body_class' );
		remove_filter( 'admin_url', 'gutenberg_modify_add_new_button_url' ); // old
		remove_action( 'admin_enqueue_scripts', 'gutenberg_check_if_classic_needs_warning_about_blocks' );
		remove_filter( 'register_post_type_args', 'gutenberg_filter_post_type_labels' );

		// Keep
		// remove_filter( 'wp_kses_allowed_html', 'gutenberg_kses_allowedtags', 10, 2 ); // not needed in 5.0
		// remove_filter( 'bulk_actions-edit-wp_block', 'gutenberg_block_bulk_actions' );
		// remove_filter( 'wp_insert_post_data', 'gutenberg_remove_wpcom_markdown_support' );
		// remove_filter( 'the_content', 'do_blocks', 9 );
		// remove_action( 'init', 'gutenberg_register_post_types' );

		// Continue to manage wpautop for posts that were edited in Gutenberg.
		// remove_filter( 'wp_editor_settings', 'gutenberg_disable_editor_settings_wpautop' );
		// remove_filter( 'the_content', 'gutenberg_wpautop', 8 );

	}

	private static function get_settings( $refresh = 'no' ) {
		/**
		 * Can be used to override the plugin's settings. Always hides the settings UI when used (as users cannot change the settings).
		 *
		 * Has to return an associative array with two keys.
		 * The defaults are:
		 *   'editor' => 'classic', // Accepted values: 'classic', 'block'.
		 *   'allow-users' => false,
		 *
		 * @param boolean To override the settings return an array with the above keys.
		 */
		$settings = apply_filters( 'classic_editor_plugin_settings', false );

		if ( is_array( $settings ) ) {
			return array(
				'editor' => ( isset( $settings['editor'] ) && $settings['editor'] === 'block' ) ? 'block' : 'classic',
				'allow-users' => ! empty( $settings['allow-users'] ),
				'hide-settings-ui' => true,
			);
		}

		if ( ! empty( self::$settings ) && $refresh === 'no' ) {
			return self::$settings;
		}

		if ( is_multisite() ) {
			$defaults = array(
				'editor' => 'classic',
				'allow-users' => false,
			);

			/**
			 * Filters the default network options.
			 *
			 * @param array $defaults The default options array. See `classic_editor_plugin_settings` for supported keys and values.
			 */
			$defaults = apply_filters( 'classic_editor_network_default_settings', $defaults );

			if ( get_network_option( null, 'classic-editor-allow-sites' ) !== 'allow' ) {
				// Per-site settings are disabled. Return default network options nad hide the settings UI.
				$defaults['hide-settings-ui'] = true;
				return $defaults;
			}

			// Override with the site options.
			$editor_option = get_option( 'classic-editor-replace' );
			$allow_users_option = get_option( 'classic-editor-allow-users' );

			if ( $editor_option ) {
				$defaults['editor'] = $editor_option;
			}
			if ( $allow_users_option ) {
				$defaults['allow-users'] = ( $allow_users_option === 'allow' );
			}

			$editor = ( isset( $defaults['editor'] ) && $defaults['editor'] === 'block' ) ? 'block' : 'classic';
			$allow_users = ! empty( $defaults['allow-users'] );
		} else {
			$allow_users = ( get_option( 'classic-editor-allow-users' ) === 'allow' );
			$option = get_option( 'classic-editor-replace' );

			// Normalize old options.
			if ( $option === 'block' || $option === 'no-replace' ) {
				$editor = 'block';
			} else {
				// empty( $option ) || $option === 'classic' || $option === 'replace'.
				$editor = 'classic';
			}
		}

		// Override the defaults with the user options.
		if ( ( ! isset( $GLOBALS['pagenow'] ) || $GLOBALS['pagenow'] !== 'options-writing.php' ) && $allow_users ) {
			$user_options = get_user_option( 'classic-editor-settings' );

			if ( $user_options === 'block' || $user_options === 'classic' ) {
				$editor = $user_options;
			}
		}

		self::$settings = array(
			'editor' => $editor,
			'hide-settings-ui' => false,
			'allow-users' => $allow_users,
		);

		return self::$settings;
	}

	private static function is_classic( $post_id = 0 ) {
		if ( ! $post_id ) {
			$post_id = self::get_edited_post_id();
		}

		if ( $post_id ) {
			$settings = self::get_settings();

			if ( $settings['allow-users'] && ! isset( $_GET['classic-editor__forget'] ) ) {
				$which = get_post_meta( $post_id, 'classic-editor-remember', true );

				// The editor choice will be "remembered" when the post is opened in either Classic or Block editor.
				if ( 'classic-editor' === $which ) {
					return true;
				} elseif ( 'block-editor' === $which ) {
					return false;
				}
			}
		}

		if ( isset( $_GET['classic-editor'] ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Get the edited post ID (early) when loading the Edit Post screen.
	 */
	private static function get_edited_post_id() {
		if (
			! empty( $_GET['post'] ) &&
			! empty( $_GET['action'] ) &&
			$_GET['action'] === 'edit' &&
			! empty( $GLOBALS['pagenow'] ) &&
			$GLOBALS['pagenow'] === 'post.php'
		) {
			return (int) $_GET['post']; // post_ID
		}

		return 0;
	}

	public static function register_settings() {
		// Add an option to Settings -> Writing
		register_setting( 'writing', 'classic-editor-replace', array(
			'sanitize_callback' => array( __CLASS__, 'validate_option_editor' ),
		) );

		register_setting( 'writing', 'classic-editor-allow-users', array(
			'sanitize_callback' => array( __CLASS__, 'validate_option_allow_users' ),
		) );

		add_option_whitelist( array(
			'writing' => array( 'classic-editor-replace', 'classic-editor-allow-users' ),
		) );

		$heading_1 = __( 'Default editor for all users', 'classic-editor' );
		$heading_2 = __( 'Allow users to switch editors', 'classic-editor' );

		add_settings_field( 'classic-editor-1', $heading_1, array( __CLASS__, 'settings_1' ), 'writing' );
		add_settings_field( 'classic-editor-2', $heading_2, array( __CLASS__, 'settings_2' ), 'writing' );
	}

	public static function save_user_settings( $user_id ) {
		if (
			isset( $_POST['classic-editor-user-settings'] ) &&
			isset( $_POST['classic-editor-replace'] ) &&
			wp_verify_nonce( $_POST['classic-editor-user-settings'], 'allow-user-settings' )
		) {
			$user_id = (int) $user_id;

			if ( $user_id !== get_current_user_id() && ! current_user_can( 'edit_user', $user_id ) ) {
				return;
			}

			$editor = self::validate_option_editor( $_POST['classic-editor-replace'] );
			update_user_option( $user_id, 'classic-editor-settings', $editor );
		}
	}

	/**
	 * Validate
	 */
	public static function validate_option_editor( $value ) {
		if ( $value === 'block' ) {
			return 'block';
		}

		return 'classic';
	}

	public static function validate_option_allow_users( $value ) {
		if ( $value === 'allow' ) {
			return 'allow';
		}

		return 'disallow';
	}

	public static function settings_1() {
		$settings = self::get_settings( 'refresh' );

		?>
		<div class="classic-editor-options">
			<p>
				<input type="radio" name="classic-editor-replace" id="classic-editor-classic" value="classic"<?php if ( $settings['editor'] === 'classic' ) echo ' checked'; ?> />
				<label for="classic-editor-classic"><?php _ex( 'Classic Editor', 'Editor Name', 'classic-editor' ); ?></label>
			</p>
			<p>
				<input type="radio" name="classic-editor-replace" id="classic-editor-block" value="block"<?php if ( $settings['editor'] !== 'classic' ) echo ' checked'; ?> />
				<label for="classic-editor-block"><?php _ex( 'Block Editor', 'Editor Name', 'classic-editor' ); ?></label>
			</p>
		</div>
		<script>
		jQuery( 'document' ).ready( function( $ ) {
			if ( window.location.hash === '#classic-editor-options' ) {
				$( '.classic-editor-options' ).closest( 'td' ).addClass( 'highlight' );
			}
		} );
		</script>
		<?php
	}

	public static function settings_2() {
		$settings = self::get_settings( 'refresh' );

		?>
		<div class="classic-editor-options">
			<p>
				<input type="radio" name="classic-editor-allow-users" id="classic-editor-allow" value="allow"<?php if ( $settings['allow-users'] ) echo ' checked'; ?> />
				<label for="classic-editor-allow"><?php _e( 'Yes', 'classic-editor' ); ?></label>
			</p>
			<p>
				<input type="radio" name="classic-editor-allow-users" id="classic-editor-disallow" value="disallow"<?php if ( ! $settings['allow-users'] ) echo ' checked'; ?> />
				<label for="classic-editor-disallow"><?php _e( 'No', 'classic-editor' ); ?></label>
			</p>
		</div>
		<?php
	}

	/**
	 * Shown on the Profile page when allowed by admin.
	 */
	public static function user_settings() {
		global $user_can_edit;
		$settings = self::get_settings( 'update' );

		if (
			! defined( 'IS_PROFILE_PAGE' ) ||
			! IS_PROFILE_PAGE ||
			! $user_can_edit ||
			! $settings['allow-users']
		) {
			return;
		}

		?>
		<table class="form-table">
			<tr class="classic-editor-user-options">
				<th scope="row"><?php _e( 'Editor', 'classic-editor' ); ?></th>
				<td>
				<?php wp_nonce_field( 'allow-user-settings', 'classic-editor-user-settings' ); ?>
				<?php self::settings_1(); ?>
				</td>
			</tr>
		</table>
		<script>jQuery( 'tr.user-rich-editing-wrap' ).before( jQuery( 'tr.classic-editor-user-options' ) );</script>
		<?php
	}

	public static function network_settings() {
		$is_checked =  ( get_network_option( null, 'classic-editor-allow-sites' ) === 'allow' );

		?>
		<table class="form-table">
			<tr>
				<th scope="row"><?php _ex( 'Classic Editor', 'Editor Name', 'classic-editor' ); ?></th>
				<td>
				<?php wp_nonce_field( 'allow-site-admin-settings', 'classic-editor-network-settings' ); ?>
				<input type="checkbox" name="classic-editor-allow-sites" id="classic-editor-allow-sites" value="allow"<?php if ( $is_checked ) echo ' checked'; ?>>
				<label for="classic-editor-allow-sites"><?php _e( 'Allow site admins to change settings', 'classic-editor' ); ?></label>
				<p class="description"><?php _e( 'By default the Block Editor is replaced with the Classic Editor and users cannot switch editors.', 'classic-editor' ); ?></p>
				</td>
			</tr>
		</table>
		<?php
	}

	public static function save_network_settings() {
		if (
			isset( $_POST['classic-editor-network-settings'] ) &&
			current_user_can( 'manage_network_options' ) &&
			wp_verify_nonce( $_POST['classic-editor-network-settings'], 'allow-site-admin-settings' )
		) {
			if ( isset( $_POST['classic-editor-allow-sites'] ) && $_POST['classic-editor-allow-sites'] === 'allow' ) {
				update_network_option( null, 'classic-editor-allow-sites', 'allow' );
			} else {
				update_network_option( null, 'classic-editor-allow-sites', 'disallow' );
			}
		}
	}

	public static function notice_after_upgrade() {
		global $pagenow;
		$settings = self::get_settings();

		if (
			$pagenow !== 'about.php' ||
			$settings['hide-settings-ui'] ||
			$settings['editor'] === 'block' || 
			$settings['allow-users'] ||
			! current_user_can( 'edit_posts' )
		) {
			// No need to show when the user cannot edit posts,
			// the settings are preset from another plugin,
			// or when not replacing the Block Editor.
			return;
		}

		$message = __( 'The Classic Editor plugin prevents use of the new Block Editor.', 'classic-editor' );

		if ( current_user_can( 'manage_options' ) ) {
			$message .= ' ' . sprintf( __( 'Change the %1$sClassic Editor settings%2$s.', 'classic-editor' ), '<a href="options-writing.php#classic-editor-options">', '</a>' );
		}

		$margin = is_rtl() ? 'margin: 1em 0 0 160px;' : 'margin: 1em 160px 0 0;';

		?>
		<div id="message" class="notice-warning notice" style="display: inline-block !important; <?php echo $margin; ?>">
			<p><?php echo $message; ?></p>
		</div>
		<?php
	}

	/**
	 * Add a hidden field in edit-form-advanced.php
	 * to help redirect back to the Classic Editor on saving.
	 */
	public static function add_redirect_helper() {
		?>
		<input type="hidden" name="classic-editor" value="" />
		<?php
	}

	/**
	 * Remember when the Classic Editor was used to edit a post.
	 */
	public static function remember_classic_editor( $post ) {
		$post_type = get_post_type( $post );

		if ( $post_type && post_type_supports( $post_type, 'editor' ) ) {
			self::remember( $post->ID, 'classic-editor' );
		}
	}

	/**
	 * Remember when the Block Editor was used to edit a post.
	 */
	public static function remember_block_editor( $editor_settings, $post ) {
		$post_type = get_post_type( $post );

		if ( $post_type && self::can_edit_post_type( $post_type ) ) {
			self::remember( $post->ID, 'block-editor' );
		}

		return $editor_settings;
	}

	private static function remember( $post_id, $editor ) {
		if ( get_post_meta( $post_id, 'classic-editor-remember', true ) !== $editor ) {
			update_post_meta( $post_id, 'classic-editor-remember', $editor );
		}
	}

	/**
	 * Choose which editor to use for a post.
	 *
	 * Passes through `$which_editor` for Block Editor (it's sets to `true` but may be changed by another plugin).
	 *
	 * @uses `use_block_editor_for_post` filter.
	 *
	 * @param boolean $use_block_editor True for Block Editor, false for Classic Editor.
	 * @param WP_Post $post             The post being edited.
	 * @return boolean True for Block Editor, false for Classic Editor.
	 */
	public static function choose_editor( $use_block_editor, $post ) {
		$settings = self::get_settings();
		$editors = self::get_enabled_editors_for_post( $post );

		// If no editor is supported, pass through `$use_block_editor`.
		if ( ! $editors['block_editor'] && ! $editors['classic_editor'] ) {
			return $use_block_editor;
		}

		// Open the default editor when no $post and for "Add New" links,
		// or the alternate editor when the user is switching editors.
		if ( empty( $post->ID ) || $post->post_status === 'auto-draft' ) {
			if (
				( $settings['editor'] === 'classic' && ! isset( $_GET['classic-editor__forget'] ) ) ||  // Add New
				( isset( $_GET['classic-editor'] ) && isset( $_GET['classic-editor__forget'] ) ) // Switch to Classic Editor when no draft post.
			) {
				$use_block_editor = false;
			}
		} elseif ( self::is_classic( $post->ID ) ) {
			$use_block_editor = false;
		}

		// Enforce the editor if set by plugins.
		if ( $use_block_editor && ! $editors['block_editor'] ) {
			$use_block_editor = false;
		} elseif ( ! $use_block_editor && ! $editors['classic_editor'] && $editors['block_editor'] ) {
			$use_block_editor = true;
		}

		return $use_block_editor;
	}

	/**
	 * Keep the `classic-editor` query arg through redirects when saving posts.
	 */
	public static function redirect_location( $location ) {
		if (
			isset( $_REQUEST['classic-editor'] ) ||
			( isset( $_POST['_wp_http_referer'] ) && strpos( $_POST['_wp_http_referer'], '&classic-editor' ) !== false )
		) {
			$location = add_query_arg( 'classic-editor', '', $location );
		}

		return $location;
	}

	/**
	 * Keep the `classic-editor` query arg when looking at revisions.
	 */
	public static function get_edit_post_link( $url ) {
		$settings = self::get_settings();

		if ( isset( $_REQUEST['classic-editor'] ) || $settings['editor'] === 'classic' ) {
			$url = add_query_arg( 'classic-editor', '', $url );
		}

		return $url;
	}

	public static function add_meta_box( $post_type, $post ) {
		$editors = self::get_enabled_editors_for_post( $post );

		if ( ! $editors['block_editor'] || ! $editors['classic_editor'] ) {
			// Editors cannot be switched.
			return;
		}

		$id = 'classic-editor-switch-editor';
		$title = __( 'Editor', 'classic-editor' );
		$callback = array( __CLASS__, 'do_meta_box' );
		/* Add when the Block Editor plugin is enabled.
		$args = array(
			'__back_compat_meta_box' => true,
	    );
	    */

		add_meta_box( $id, $title, $callback, null, 'side', 'default' );
	}

	public static function do_meta_box( $post ) {
		$edit_url = get_edit_post_link( $post->ID, 'raw' );

		if ( did_action( 'enqueue_block_editor_assets' ) ) {
			// Block Editor is loading, switch to Classic Editor.
			$edit_url = add_query_arg( 'classic-editor', '', $edit_url );
			$link_text = __( 'Switch to Classic Editor', 'classic-editor' );
		} else {
			// Switch to Block Editor.
			$edit_url = remove_query_arg( 'classic-editor', $edit_url );
			$link_text = __( 'Switch to Block Editor', 'classic-editor' );
		}

		// Forget the previous value when going to a specific editor.
		$edit_url = add_query_arg( 'classic-editor__forget', '', $edit_url );

		?>
		<p style="margin: 1em 0;"><a href="<?php echo esc_url( $edit_url ); ?>"><?php echo $link_text; ?></a></p>
		<?php
	}

	public static function enqueue_block_editor_scripts() {
		$editors = self::get_enabled_editors_for_post( $GLOBALS['post'] );

		if ( ! $editors['classic_editor'] ) {
			// Editor cannot be switched.
			return;
		}

		wp_enqueue_script(
			'classic-editor-add-submenu',
			plugins_url( 'js/block-editor-plugin.js', __FILE__ ),
			array( 'wp-element', 'wp-components', 'lodash' ),
			self::plugin_version,
			true
		);

		wp_localize_script(
			'classic-editor-add-submenu',
			'classicEditorPluginL10n',
			array( 'linkText' => __( 'Switch to Classic Editor', 'classic-editor' ) )
		);
	}

	/**
	 * Add a link to the settings on the Plugins screen.
	 */
	public static function add_settings_link( $links, $file ) {
		$settings = self::get_settings();

		if ( $file === 'classic-editor/classic-editor.php' && ! $settings['hide-settings-ui'] && current_user_can( 'manage_options' ) ) {
			(array) $links[] = sprintf( '<a href="%s">%s</a>', admin_url( 'options-writing.php#classic-editor-options' ), __( 'Settings', 'classic-editor' ) );
		}

		return $links;
	}

	private static function can_edit_post_type( $post_type ) {
		$can_edit = false;

		if ( function_exists( 'gutenberg_can_edit_post_type' ) ) {
			$can_edit = gutenberg_can_edit_post_type( $post_type );
		} elseif ( function_exists( 'use_block_editor_for_post_type' ) ) {
			$can_edit = use_block_editor_for_post_type( $post_type );
		}

		return $can_edit;
	}

	/**
	 * Checks which editors are enabled for the post type.
	 *
	 * @param string $post_type The post type.
	 * @return array Associative array of the editors and whether they are enabled for the post type.
	 */
	private static function get_enabled_editors_for_post_type( $post_type ) {
		if ( isset( self::$supported_post_types[ $post_type ] ) ) {
			return self::$supported_post_types[ $post_type ];
		}

		$classic_editor = post_type_supports( $post_type, 'editor' );
		$block_editor = self::can_edit_post_type( $post_type );

		$editors = array(
			'classic_editor' => $classic_editor,
			'block_editor'   => $block_editor,
		);

		/**
		 * Filters the editors that are enabled for the post type.
		 *
		 * @param array $editors    Associative array of the editors and whether they are enabled for the post type.
		 * @param string $post_type The post type.
		 */
		$editors = apply_filters( 'classic_editor_enabled_editors_for_post_type', $editors, $post_type );
		self::$supported_post_types[ $post_type ] = $editors;

		return $editors;
	}

	/**
	 * Checks which editors are enabled for the post.
	 *
	 * @param WP_Post $post  The post object.
	 * @return array Associative array of the editors and whether they are enabled for the post.
	 */
	private static function get_enabled_editors_for_post( $post ) {
		$post_type = get_post_type( $post );

		if ( ! $post_type ) {
			return array(
				'classic_editor' => false,
				'block_editor'   => false,
			);
		}

		$editors = self::get_enabled_editors_for_post_type( $post_type );

		/**
		 * Filters the editors that are enabled for the post.
		 *
		 * @param array $editors Associative array of the editors and whether they are enabled for the post.
		 * @param WP_Post $post  The post object.
		 */
		return apply_filters( 'classic_editor_enabled_editors_for_post', $editors, $post );
	}

	/**
	 * Adds links to the post/page screens to edit any post or page in
	 * the Classic Editor or Block Editor.
	 *
	 * @param  array   $actions Post actions.
	 * @param  WP_Post $post    Edited post.
	 * @return array Updated post actions.
	 */
	public static function add_edit_links( $actions, $post ) {
		// This is in Gutenberg, don't duplicate it.
		if ( array_key_exists( 'classic', $actions ) ) {
			unset( $actions['classic'] );
		}

		if ( ! array_key_exists( 'edit', $actions ) ) {
			return $actions;
		}

		$edit_url = get_edit_post_link( $post->ID, 'raw' );

		if ( ! $edit_url ) {
			return $actions;
		}

		$editors = self::get_enabled_editors_for_post( $post );

		// Do not show the links if only one editor is available.
		if ( ! $editors['classic_editor'] || ! $editors['block_editor'] ) {
			return $actions;
		}

		// Forget the previous value when going to a specific editor.
		$edit_url = add_query_arg( 'classic-editor__forget', '', $edit_url );

		// Build the edit actions. See also: WP_Posts_List_Table::handle_row_actions().
		$title = _draft_or_post_title( $post->ID );

		// Link to the Block Editor.
		$url = remove_query_arg( 'classic-editor', $edit_url );
		$text = _x( 'Block Editor', 'Editor Name', 'classic-editor' );
		/* translators: %s: post title */
		$label = sprintf( __( 'Edit &#8220;%s&#8221; in the Block Editor', 'classic-editor' ), $title );
		$edit_block = sprintf( '<a href="%s" aria-label="%s">%s</a>', esc_url( $url ), esc_attr( $label ), $text );

		// Link to the Classic Editor.
		$url = add_query_arg( 'classic-editor', '', $edit_url );
		$text = _x( 'Classic Editor', 'Editor Name', 'classic-editor' );
		/* translators: %s: post title */
		$label = sprintf( __( 'Edit &#8220;%s&#8221; in the Classic Editor', 'classic-editor' ), $title );
		$edit_classic = sprintf( '<a href="%s" aria-label="%s">%s</a>', esc_url( $url ), esc_attr( $label ), $text );

		$edit_actions = array(
			'classic-editor-block' => $edit_block,
			'classic-editor-classic' => $edit_classic,
		);

		// Insert the new Edit actions instead of the Edit action.
		$edit_offset = array_search( 'edit', array_keys( $actions ), true );
		array_splice( $actions, $edit_offset, 1, $edit_actions );

		return $actions;
	}

	/**
	 * Show the editor that will be used in a "post state" in the Posts list table.
	 */
	public static function add_post_state( $post_states, $post ) {
		$editors = self::get_enabled_editors_for_post( $post );

		if ( ! $editors['classic_editor'] && ! $editors['block_editor'] ) {
			return $post_states;
		} elseif ( $editors['classic_editor'] && ! $editors['block_editor'] ) {
			// Forced to Classic Editor.
			$state = '<span class="classic-editor-forced-state">' . _x( 'Classic Editor', 'Editor Name', 'classic-editor' ) . '</span>';
		} elseif ( ! $editors['classic_editor'] && $editors['block_editor'] ) {
			// Forced to Block Editor.
			$state = '<span class="classic-editor-forced-state">' . _x( 'Block Editor', 'Editor Name', 'classic-editor' ) . '</span>';
		} else {
			$last_editor = get_post_meta( $post->ID, 'classic-editor-remember', true );

			if ( $last_editor ) {
				$is_classic = ( $last_editor === 'classic-editor' );
			} else {
				$settings = self::get_settings();
				$is_classic = ( $settings['editor'] === 'classic' );
			}

			$state = $is_classic ? _x( 'Classic Editor', 'Editor Name', 'classic-editor' ) : _x( 'Block Editor', 'Editor Name', 'classic-editor' );
		}

		(array) $post_states[] = $state;

		return $post_states;
	}

	public static function add_edit_php_inline_style() {
		?>
		<style>
		.classic-editor-forced-state {
			font-style: italic;
			font-weight: 400;
			color: #72777c;
			font-size: small;
		}
		</style>
		<?php
	}

	public static function on_admin_init() {
		global $pagenow;

		if ( $pagenow !== 'post.php' ) {
			return;
		}

		$settings = self::get_settings();
		$post_id = self::get_edited_post_id();

		if ( $post_id && ( $settings['editor'] === 'classic' || self::is_classic( $post_id ) ) ) {
			// Move the Privacy Policy help notice back under the title field.
			remove_action( 'admin_notices', array( 'WP_Privacy_Policy_Content', 'notice' ) );
			add_action( 'edit_form_after_title', array( 'WP_Privacy_Policy_Content', 'notice' ) );
		}
	}

	/**
	 * Set defaults on activation.
	 */
	public static function activate() {
		if ( is_multisite() ) {
			add_network_option( null, 'classic-editor-allow-sites', 'disallow' );
		}

		add_option( 'classic-editor-replace', 'classic' );
		add_option( 'classic-editor-allow-users', 'disallow' );
	}

	/**
	 * Delete the options on uninstall.
	 */
	public static function uninstall() {
		if ( is_multisite() ) {
			delete_network_option( null, 'classic-editor-allow-sites' );
		}

		delete_option( 'classic-editor-replace' );
		delete_option( 'classic-editor-allow-users' );
	}
}

add_action( 'plugins_loaded', array( 'Classic_Editor', 'init_actions' ) );

endif;
