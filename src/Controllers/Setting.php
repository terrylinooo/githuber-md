<?php
/**
 * Class Setting
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Githuber
 * @since 1.0.0
 * @version 1.1.0
 */

namespace Githuber\Controller;

class Setting extends ControllerAbstract {

	public static $settings = [];
	public static $setting_api;

	/**
	 * Where the Githuber MD's setting menu displays on.
	 *
	 * @var string
	 */
	public $menu_position = 'plugins';

	/**
	 * Menu slug.
	 *
	 * @var string
	 */
	public $menu_slug = 'githuber-md';

	/**
	 * Constructer.
	 */
	public function __construct() {
		parent::__construct();

		if ( ! self::$setting_api ) {
			self::$setting_api = new \WeDevs_Settings_API();
		}
	}
	
	/**
	 * Initialize.
	 */
	public function init() {
		add_action( 'admin_init', array( $this, 'setting_admin_init' ) );
		add_action( 'admin_menu', array( $this, 'setting_admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_styles' ) );
		add_filter( 'plugin_action_links', array( $this, 'plugin_action_links' ), 10, 5 );
		add_filter( 'plugin_row_meta', array( $this, 'plugin_extend_links' ), 10, 2 );
	}

	/**
	 * Load specfic CSS file for the Githuber setting page.
	 */
	public function admin_enqueue_styles( $hook_suffix ) {

		if ( false === strpos( $hook_suffix, 'githuber-md' ) ) {
			return;
		}
		wp_enqueue_style( 'custom_wp_admin_css', $this->githuber_plugin_url . 'assets/css/admin-style.css' );
	}

	/**
	 * Register JS files.
	 */
	public function admin_enqueue_scripts( $hook_suffix ) {

	}

	/**
	 * The Githuber setting page, sections and fields.
	 */
	public function setting_admin_init() {

		// set sections and fields.
		self::$setting_api->set_sections( $this->get_sections() );
		self::$setting_api->set_fields( $this->get_fields() );
	 
		// initialize them.
		self::$setting_api->admin_init();

		self::$settings = $this->get_fields();
	}

	/**
	 * Setting sections.
	 *
	 * @return array
	 */
	public function get_sections() {
		return array(

			array(
				'id'    => 'githuber_markdown',
				'title' => __( 'Markdown', $this->text_domain ),
			),
			
			array(
				'id'    => 'githuber_modules',
				'title' => __( 'Modules', $this->text_domain ),
			),

			array(
				'id'    => 'githuber_options',
				'title' => __( 'Theme Options', $this->text_domain ),
			),

			array(
				'id'    => 'githuber_about',
				'title' => __( 'About', $this->text_domain ),
			),
		);
	}

	/**
	 * Setting fields.
	 *
	 * @return array
	 */
	public function get_fields() {

		return array(

			'githuber_markdown' => array(

				array(
					'name'  => '_TITLE_',
					'label' => __( 'Writing', $this->text_domain ),
				),

				array(
					'name'    => 'enable_markdown_for',
					'label'   => __( 'Enable', $this->text_domain ),
					'desc'    => __( 'Enable Markdown for post, pages or comments.', $this->text_domain ),
					'type'    => 'multicheck',
					'default' => array(
						'posting' => 'posting',
					),
					'options' => array(
						'posting'    => 'Posts and pages',
						'commenting' => 'Comments',
					)
				),

				array(
					'name'    => 'disable_revision',
					'label'   => __( 'Disable Revision', $this->text_domain ),
					'desc'    => __( 'If you think the revision and auto-save functions are annoying when you\'re writing, you can to disable them.', $this->text_domain ),
					'type'    => 'radio',
					'default' => 'no',
					'options' => array(
						'yes' => __( 'Yes', $this->text_domain ),
						'no'  => __( 'No', $this->text_domain ),
					)
				),

				array(
					'name'  => '_TITLE_',
					'label' => __( 'Editor Settings', $this->text_domain ),
				),

				array(
					'name'    => 'editor_live_preview',
					'label'   => __( 'Live Preview', $this->text_domain ),
					'desc'    => __( 'Split editor into two panes to display a live preview when editing post.', $this->text_domain ),
					'type'    => 'radio',
					'default' => 'no',
					'options' => array(
						'yes' => __( 'Yes', $this->text_domain ),
						'no'  => __( 'No', $this->text_domain ),
					)
				),

				array(
					'name'    => 'editor_sync_scrolling',
					'label'   => __( 'Sync Scrolling', $this->text_domain ),
					'desc'    => __( 'Synchronize scrolling of two editor panes by content.', $this->text_domain ),
					'type'    => 'radio',
					'default' => 'no',
					'options' => array(
						'yes' => __( 'Yes', $this->text_domain ),
						'no'  => __( 'No', $this->text_domain ),
					)
				),

				array(
					'name'    => 'editor_html_decode',
					'label'   => __( 'HTML Decode', $this->text_domain ),
					'desc'    => __( 'Allow all HTML tags and attributes in the Markdown Editor. Default false to increase security.', $this->text_domain ),
					'type'    => 'radio',
					'default' => 'no',
					'options' => array(
						'yes' => __( 'Yes', $this->text_domain ),
						'no'  => __( 'No', $this->text_domain ),
					)
				),

				array(
					'name'  => '_TITLE_',
					'label' => __( 'Editor Style', $this->text_domain ),
				),

				array(
					'name'    => 'editor_toolbar_theme',
					'label'   => __( 'Toolbar', $this->text_domain ),
					'desc'    => __( 'Choose a perferred style for the Editor\'s toolbar.', $this->text_domain ),
					'type'    => 'select',
					'default' => 'default',
					'options' => array(
						'default' => 'default',
						'dark'    => 'dark',
					),
				),

				array(
					'name'    => 'editor_editor_theme',
					'label'   => __( 'Editing Area', $this->text_domain ),
					'desc'    => __( 'Choose a perferred style for the Editor\'s editing area.', $this->text_domain ),
					'type'    => 'select',
					'default' => 'default',
					'options' => array(
						'default'                 => 'default',
						'3024-day'                => '3024-day',
						'3024-night'              => '3024-night',
						'abcdef'                  => 'abcdef',
						'ambiance'                => 'ambiance',
						'ambiance-mobile'         => 'ambiance-mobile',
						'base16-dark'             => 'base16-dark',
						'base16-light'            => 'base16-light',
						'bespin'                  => 'bespin',
						'blackboard'              => 'blackboard',
						'cobalt'                  => 'cobalt',
						'colorforth'              => 'colorforth',
						'dracula'                 => 'dracula',
						'duotone-dark'            => 'duotone-dark',
						'duotone-light'           => 'duotone-light',
						'eclipse'                 => 'eclipse',
						'elegant'                 => 'elegant',
						'erlang-dark'             => 'erlang-dark',
						'gruvbox-dark'            => 'gruvbox-dark',
						'hopscotch'               => 'hopscotch',
						'icecoder'                => 'icecoder',
						'idea'                    => 'idea',
						'isotope'                 => 'isotope',
						'lesser-dark'             => 'lesser-dark',
						'liquibyte'               => 'liquibyte',
						'lucario'                 => 'lucario',
						'material'                => 'material',
						'mbo'                     => 'mbo',
						'mdn-like'                => 'mdn-like',
						'midnight'                => 'midnight',
						'monokai'                 => 'monokai',
						'neat'                    => 'neat',
						'neo'                     => 'neo',
						'night'                   => 'night',
						'oceanic-next'            => 'oceanic-next',
						'panda-syntax'            => 'panda-syntax',
						'paraiso-dark'            => 'paraiso-dark',
						'paraiso-light'           => 'paraiso-light',
						'pastel-on-dark'          => 'pastel-on-dark',
						'railscasts'              => 'railscasts',
						'rubyblue'                => 'rubyblue',
						'seti'                    => 'seti',
						'shadowfox'               => 'shadowfox',
						'solarized'               => 'solarized',
						'ssms'                    => 'ssms',
						'the-matrix'              => 'the-matrix',
						'tomorrow-night-bright'   => 'tomorrow-night-bright',
						'tomorrow-night-eighties' => 'tomorrow-night-eighties',
						'ttcn'                    => 'ttcn',
						'twilight'                => 'twilight',
						'vibrant-ink'             => 'vibrant-ink',
						'xq-dark'                 => 'xq-dark',
						'xq-light'                => 'xq-light',
						'yeti'                    => 'yeti',
						'zenburn'                 => 'zenburn',
					),
				),

				array(
					'name'  => '_TITLE_',
					'label' => __( 'Modules', $this->text_domain ),
				),

				array(
					'name'    => 'support_prism',
					'label'   => __( 'Syntax Highlight', $this->text_domain ),
					'desc'    => __( 'Highligh the syntax in your code snippets by Prism.js', $this->text_domain ),
					'type'    => 'radio',
					'default' => 'no',
					'options' => array(
						'yes' => __( 'Yes', $this->text_domain ),
						'no'  => __( 'No', $this->text_domain ),
					)
				),

				array(
					'name'    => 'support_katex',
					'label'   => __( 'KaTeX', $this->text_domain ),
					'desc'    => __( 'Support <a href="https://katex.org/" target="_blank">KaTeX</a> math typesetting.', $this->text_domain ),
					'type'    => 'radio',
					'default' => 'no',
					'options' => array(
						'yes' => __( 'Yes', $this->text_domain ),
						'no'  => __( 'No', $this->text_domain ),
					)
				),

				array(
					'name'    => 'support_flowchart',
					'label'   => __( 'Flow Chart', $this->text_domain ),
					'desc'    => __( 'Support <a href="http://flowchart.js.org/" target="_blank">flowchart.js</a> to draws simple SVG flow chart diagrams.', $this->text_domain ),
					'type'    => 'radio',
					'default' => 'no',
					'options' => array(
						'yes' => __( 'Yes', $this->text_domain ),
						'no'  => __( 'No', $this->text_domain ),
					)
				),

				array(
					'name'    => 'support_sequence_diagram',
					'label'   => __( 'Sequence Diagrams', $this->text_domain ),
					'desc'    => __( 'Support <a href="https://bramp.github.io/js-sequence-diagrams/" target="_blank">js-sequence-diagrams</a> to turn text into vector UML sequence diagrams.', $this->text_domain ),
					'type'    => 'radio',
					'default' => 'no',
					'options' => array(
						'yes' => __( 'Yes', $this->text_domain ),
						'no'  => __( 'No', $this->text_domain ),
					)
				),

				array(
					'name'    => 'support_task_list',
					'label'   => __( 'Task List', $this->text_domain ),
					'desc'    => __( 'Support Github Flavored Markdown task lists.', $this->text_domain ),
					'type'    => 'radio',
					'default' => 'no',
					'options' => array(
						'yes' => __( 'Yes', $this->text_domain ),
						'no'  => __( 'No', $this->text_domain ),
					)
				),

				/*

				array(
					'name'    => 'support_toc',
					'label'   => __( 'Table of Content', $this->text_domain ),
					'desc'    => __( 'Display a TOC in the every first section.', $this->text_domain ),
					'type'    => 'radio',
					'default' => 'no',
					'options' => array(
						'yes' => __( 'Yes', $this->text_domain ),
						'no'  => __( 'No', $this->text_domain )
					)
				),

				array(
					'name'    => 'support_emoji',
					'label'   => __( 'Emoji', $this->text_domain ),
					'desc'    => __( 'Support Emoji in posts.', $this->text_domain ),
					'type'    => 'radio',
					'default' => 'no',
					'options' => array(
						'yes' => __( 'Yes', $this->text_domain ),
						'no'  => __( 'No', $this->text_domain )
					)
				),

				*/

				array(
					'name'    => 'support_image_paste',
					'label'   => __( 'Image Paste', $this->text_domain ),
					'desc'    => __( 'Easily paste image from clipboard directly into the post content.', $this->text_domain ),
					'type'    => 'radio',
					'default' => 'no',
					'options' => array(
						'yes' => __( 'Yes', $this->text_domain ),
						'no'  => __( 'No', $this->text_domain ),
					)
				),
			),

			'githuber_modules' =>  array(

				array(
					'name'  => '_TITLE_',
					'label' => __( 'Syntax Highlight', $this->text_domain ),
					'desc'  => __( 'prism.js', $this->text_domain ),
				),

				array(
					'name'    => 'prism_theme',
					'label'   => __( 'Theme', $this->text_domain ),
					'desc'    => __( 'Choose a perferred theme for the syntax highlighter.', $this->text_domain ),
					'type'    => 'select',
					'default' => 'default',
					'options' => array(
						'default'        => 'default',
						'dark'           => 'dark',
						'funky'          => 'funky',
						'okaidia'        => 'okaidia',
						'twilight'       => 'twilight',
						'tomorrow'       => 'tomorrow',
						'coy'            => 'coy',
						'solarizedlight' => 'solarizedlight',
					)
				),

				array(
					'name'    => 'prism_line_number',
					'label'   => __( 'Line Number', $this->text_domain ),
					'desc'    => __( 'Show line number in code area?', $this->text_domain ),
					'type'    => 'radio',
					'default' => 'no',
					'options' => array(
						'yes' => __( 'Yes', $this->text_domain ),
						'no'  => __( 'No', $this->text_domain ),
					)
				),

				array(
					'name'    => 'prism_src',
					'label'   => __( 'File Host', $this->text_domain ),
					'desc'    => __( 'Use this library with a CDN service or self-hosted (default)?', $this->text_domain ),
					'type'    => 'radio',
					'default' => 'default',
					'options' => array(
						'default'    => 'default',
						'cloudflare' => 'cdnjs.cloudflare.com',
						'jsdelivr'   => 'cdn.jsdelivr.net',
					)
				),

				array(
					'name'  => '_TITLE_',
					'label' => __( 'KaTex', $this->text_domain ),
					'desc'  => __( 'KaTex.js', $this->text_domain ),
				),

				array(
					'name'    => 'katex_src',
					'label'   => __( 'File Host', $this->text_domain ),
					'desc'    => __( 'Use this library with a CDN service or self-hosted (default)?', $this->text_domain ),
					'type'    => 'radio',
					'default' => 'default',
					'options' => array(
						'default'    => 'default',
						'cloudflare' => 'cdnjs.cloudflare.com',
						'jsdelivr'   => 'cdn.jsdelivr.net',
					)
				),

				array(
					'name'  => '_TITLE_',
					'label' => __( 'Flow Chart', $this->text_domain ),
					'desc'  => __( 'flowchart.js', $this->text_domain ),
				),

				array(
					'name'    => 'flowchart_src',
					'label'   => __( 'File Host', $this->text_domain ),
					'desc'    => __( 'Use this library with a CDN service or self-hosted (default)?', $this->text_domain ),
					'type'    => 'radio',
					'default' => 'default',
					'options' => array(
						'default'    => 'default',
						'cloudflare' => 'cdnjs.cloudflare.com',
						'jsdelivr'   => 'cdn.jsdelivr.net',
					)
				),

				array(
					'name'  => '_TITLE_',
					'label' => __( 'Image Paste', $this->text_domain ),
				),

				array(
					'name'    => 'image_paste_src',
					'label'   => __( 'Storage Space', $this->text_domain ),
					'desc'    => __( 'Images are stored in WordPress\'s <strong>uploads</strong> folder by default. However, you can use Imgur instead of the default place.', $this->text_domain ),
					'type'    => 'radio',
					'default' => 'default',
					'options' => array(
						'default' => __( 'default', $this->text_domain ),
						'imgur'   => __( 'imgur.com', $this->text_domain ),
					)
				),

				array(
                    'name'              => 'imgur_client_id',
					'label'             => __( 'Imgur Client ID', $this->text_domain ),
					'desc'              => __( 'Required while the choosed storage space is <u>imgur.com</u>. If you don\'t have one, <a href="https://api.imgur.com/oauth2/addclient" target="_blank">sign up</a> here.', $this->text_domain ),
                    'placeholder'       => '',
                    'type'              => 'text',
                    'default'           => '',
                    'sanitize_callback' => 'sanitize_text_field',
				),

			),

			'githuber_options' => array(

				array(
                    'name' => 'theme_description',
                    'desc' => githuber_load_view( 'setting/theme-description' ),
                    'type' => 'html'
				),
				
				array(
					'name'  => '_TITLE_',
					'label' => __( 'Menu', $this->text_domain ),
					'desc'  => '',
				),

				array(
					'name'    => 'githuber_theme_bootstrap_menu',
					'label'   => __( 'Bootstrap 4 Menu', $this->text_domain ),
					'desc'    => __( 'Use Bootstrap 4 dropdown menu in header position. (2-layer)', $this->text_domain ),
					'type'    => 'radio',
					'default' => 'no',
					'options' => array(
						'yes' => __( 'Yes', $this->text_domain ),
						'no'  => __( 'No', $this->text_domain ),
					)
				),

				array(
					'name'  => '_TITLE_',
					'label' => __( 'Widget', $this->text_domain ),
					'desc'  => '',
				),

				array(
					'name'    => 'githuber_theme_bootstrap_toc',
					'label'   => __( 'Bootstrap 4 TOC', $this->text_domain ),
					'desc'    => __( 'A widget that shows a Bootstrap 4 styled TOC deponds on your post content.', $this->text_domain ),
					'type'    => 'radio',
					'default' => 'no',
					'options' => array(
						'yes' => __( 'Yes', $this->text_domain ),
						'no'  => __( 'No', $this->text_domain ),
					)
				),

				array(
					'name'  => '_TITLE_',
					'label' => __( 'Post Type', $this->text_domain ),
					'desc'  => '',
				),

				array(
					'name'    => 'githuber_theme_repository',
					'label'   => __( 'GitHub Repository', $this->text_domain ),
					'desc'    => __( 'Display the stars, forks, issues from your GitHub repository.', $this->text_domain ),
					'type'    => 'radio',
					'default' => 'no',
					'options' => array(
						'yes' => __( 'Yes', $this->text_domain ),
						'no'  => __( 'No', $this->text_domain ),
					)
				),

				array(
					'name'  => '_TITLE_',
					'label' => __( 'Shortcode', $this->text_domain ),
					'desc'  => '',
				),

				array(
					'name'    => 'githuber_theme_shortcode_social_icons',
					'label'   => __( 'Social Icons', $this->text_domain ),
					'desc'    => __( '.', $this->text_domain ),
					'type'    => 'radio',
					'default' => 'no',
					'options' => array(
						'yes' => __( 'Yes', $this->text_domain ),
						'no'  => __( 'No', $this->text_domain ),
					)
				),

				array(
					'name'  => '_TITLE_',
					'label' => __( 'Adjustment', $this->text_domain ),
					'desc'  => '',
				),

				array(
					'name'    => 'githuber_theme_adjustment_head_output',
					'label'   => __( 'Head Output', $this->text_domain ),
					'desc'    => __( 'Remove information displays in HTML source code.', $this->text_domain ),
					'type'    => 'radio',
					'default' => 'no',
					'options' => array(
						'yes' => __( 'Yes', $this->text_domain ),
						'no'  => __( 'No', $this->text_domain ),
					)
				),

				array(
                    'name' => 'theme_adjustment_head_output',
                    'desc' => githuber_load_view( 'setting/theme-adjustment' ),
                    'type' => 'html'
				),
			),

			'githuber_about' => array(

				array(
					'name' => 'plugin_about',
					'label'   => __( 'Support', $this->text_domain ),
					'desc' => githuber_load_view( 'setting/about-and-support' ),
					'type' => 'html'
				),
			),
		);
	}

	/**
	 * Register the plugin page.
	 */
	public function setting_admin_menu() {
		switch ( $this->menu_position ) {
			case 'menu':
			case 'plugins':
			case 'options':
			default:
				$menu_function = 'add_' . $this->menu_position . '_page';
				$menu_function(
					__( 'Githuber MD ', $this->text_domain ),
					__( 'Githuber MD', $this->text_domain ),
					'manage_options',
					$this->menu_slug, 
					array( $this, 'setting_plugin_page' ),
					'dashicons-edit'
				);
				break;
		}
	}

	/**
	* Display the plugin settings options page.
	*/
	public function setting_plugin_page() {

		echo '<div class="wrap">';
		settings_errors();
	
		self::$setting_api->show_navigation();
		self::$setting_api->show_forms();
	
		echo '</div>';
	}

	/**
	 * Filters the action links displayed for each plugin in the Network Admin Plugins list table.
	 *
	 * @param  array  $links Original links.
	 * @param  string $file  File position.
	 * @return array Combined links.
	 */
	public function plugin_action_links( $links, $file ) {
		if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
			return $links;
		}

		if ( $file == $this->githuber_plugin_name ) {
			$links[] = '<a href="' . admin_url( "plugins.php?page=" . $this->menu_slug ) . '">' . __( 'Settings', $this->text_domain ) . '</a>';
			return $links;
		}
	}

	/**
	 * Add links to plugin meta information on plugin list page.
	 *
	 * @param  array  $links Original links.
	 * @param  string $file  File position.
	 * @return array Combined links.
	 */
	public function plugin_extend_links( $links, $file ) {
		if ( ! current_user_can( 'install_plugins' ) ) {
			return $links;
		}

		if ( $file == $this->githuber_plugin_name ) {
			$links[] = '<a href="https://github.com/terrylinooo/githuber-md" target="_blank">' . __( 'View GitHub project', $this->text_domain ) . '</a>';
			$links[] = '<a href="https://github.com/terrylinooo/githuber-md/issues" target="_blank">' . __( 'Report issues', $this->text_domain ) . '</a>';
		}
		return $links;
	}
}
