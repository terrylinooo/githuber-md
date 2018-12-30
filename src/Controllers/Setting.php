<?php
/**
 * Class Setting
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Githuber
 * @since 1.0.0
 * @version 1.3.0
 */

namespace Githuber\Controller;

class Setting extends ControllerAbstract {

	public static $settings = array();
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
		add_filter( 'plugin_action_links_' . $this->githuber_plugin_name, array( $this, 'plugin_action_links' ), 10, 5 );
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
				'title' => __( 'Markdown', 'wp-githuber-md' ),
			),
			
			array(
				'id'    => 'githuber_modules',
				'title' => __( 'Modules', 'wp-githuber-md' ),
			),

			array(
				'id'    => 'githuber_options',
				'title' => __( 'Theme Options', 'wp-githuber-md' ),
			),

			array(
				'id'    => 'githuber_about',
				'title' => __( 'About', 'wp-githuber-md' ),
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
					'label' => __( 'Writing', 'wp-githuber-md' ),
				),

				array(
					'name'    => 'enable_markdown_for',
					'label'   => __( 'Enable', 'wp-githuber-md' ),
					'desc'    => __( 'Enable Markdown for post, pages or comments.', 'wp-githuber-md' ),
					'type'    => 'multicheck',
					'default' => array(
						'posting' => 'posting',
					),
					'options' => array(
						'posting'    => __( 'Posts and pages', 'wp-githuber-md' ),
						'commenting' => __( 'Comments', 'wp-githuber-md' ),
					)
				),

				array(
					'name'    => 'disable_revision',
					'label'   => __( 'Disable Revision', 'wp-githuber-md' ),
					'desc'    => __( 'If you think the revision function is annoying when you\'re writing, you can to disable it.', 'wp-githuber-md' ),
					'type'    => 'radio',
					'default' => 'no',
					'options' => array(
						'yes' => __( 'Yes', 'wp-githuber-md' ),
						'no'  => __( 'No', 'wp-githuber-md' ),
					)
				),

				array(
					'name'    => 'disable_autosave',
					'class'   => 'disable_autosave',
					'label'   => __( 'Disable Auto-save', 'wp-githuber-md' ),
					'desc'    => __( 'If you think the auto-save function is annoying when you\'re writing, you can to disable it.', 'wp-githuber-md' ),
					'type'    => 'radio',
					'default' => 'yes',
					'options' => array(
						'yes' => __( 'Yes', 'wp-githuber-md' ),
						'no'  => __( 'No', 'wp-githuber-md' ),
					)
				),

				array(
					'name'    => 'html_to_markdown',
					'class'   => 'html_to_markdown',
					'label'   => __( 'HTML-to-Markdown Helper', 'wp-githuber-md' ),
					'desc'    => githuber_load_view( 'setting/html-to-markdown' ),
					'type'    => 'radio',
					'default' => 'yes',
					'options' => array(
						'yes' => __( 'Yes', 'wp-githuber-md' ),
						'no'  => __( 'No', 'wp-githuber-md' ),
					)
				),

				array(
					'name'  => '_TITLE_',
					'label' => __( 'Editor Settings', 'wp-githuber-md' ),
				),

				array(
					'name'    => 'editor_live_preview',
					'label'   => __( 'Live Preview', 'wp-githuber-md' ),
					'desc'    => __( 'Split editor into two panes to display a live preview when editing post.', 'wp-githuber-md' ),
					'type'    => 'radio',
					'default' => 'yes',
					'options' => array(
						'yes' => __( 'Yes', 'wp-githuber-md' ),
						'no'  => __( 'No', 'wp-githuber-md' ),
					)
				),

				array(
					'name'    => 'editor_sync_scrolling',
					'label'   => __( 'Sync Scrolling', 'wp-githuber-md' ),
					'desc'    => __( 'Synchronize scrolling of two editor panes by content.', 'wp-githuber-md' ),
					'type'    => 'radio',
					'default' => 'yes',
					'options' => array(
						'yes' => __( 'Yes', 'wp-githuber-md' ),
						'no'  => __( 'No', 'wp-githuber-md' ),
					)
				),

				array(
					'name'    => 'editor_html_decode',
					'label'   => __( 'HTML Decode', 'wp-githuber-md' ),
					'desc'    => __( 'Allow all HTML tags and attributes in the Markdown Editor.', 'wp-githuber-md' ),
					'type'    => 'radio',
					'default' => 'yes',
					'options' => array(
						'yes' => __( 'Yes', 'wp-githuber-md' ),
						'no'  => __( 'No', 'wp-githuber-md' ),
					)
				),

				array(
					'name'  => '_TITLE_',
					'label' => __( 'Editor Style', 'wp-githuber-md' ),
				),

				array(
					'name'    => 'editor_toolbar_theme',
					'label'   => __( 'Toolbar', 'wp-githuber-md' ),
					'desc'    => __( 'Choose a perferred style for the Editor\'s toolbar.', 'wp-githuber-md' ),
					'type'    => 'select',
					'default' => 'default',
					'options' => array(
						'default' => 'default',
						'dark'    => 'dark',
					),
				),

				array(
					'name'    => 'editor_editor_theme',
					'label'   => __( 'Editing Area', 'wp-githuber-md' ),
					'desc'    => __( 'Choose a perferred style for the Editor\'s editing area.', 'wp-githuber-md' ),
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
					'label' => __( 'Modules', 'wp-githuber-md' ),
				),

				array(
					'name'    => 'support_prism',
					'label'   => __( 'Syntax Highlight', 'wp-githuber-md' ),
					'desc'    => __( 'Highligh the syntax in your code snippets by Prism.js', 'wp-githuber-md' ),
					'type'    => 'radio',
					'default' => 'no',
					'options' => array(
						'yes' => __( 'Yes', 'wp-githuber-md' ),
						'no'  => __( 'No', 'wp-githuber-md' ),
					)
				),

				array(
					'name'    => 'support_katex',
					'label'   => __( 'KaTeX', 'wp-githuber-md' ),
					'desc'    => __( 'Support <a href="https://terryl.in/en/githuber-md-katax/" target="_blank">KaTeX</a> math typesetting.', 'wp-githuber-md' ),
					'type'    => 'radio',
					'default' => 'no',
					'options' => array(
						'yes' => __( 'Yes', 'wp-githuber-md' ),
						'no'  => __( 'No', 'wp-githuber-md' ),
					)
				),

				array(
					'name'    => 'support_flowchart',
					'label'   => __( 'Flow Chart', 'wp-githuber-md' ),
					'desc'    => __( 'Support <a href="https://terryl.in/en/githuber-md-flow-chart/" target="_blank">flowchart.js</a> to draws simple SVG flow chart diagrams.', 'wp-githuber-md' ),
					'type'    => 'radio',
					'default' => 'no',
					'options' => array(
						'yes' => __( 'Yes', 'wp-githuber-md' ),
						'no'  => __( 'No', 'wp-githuber-md' ),
					)
				),

				array(
					'name'    => 'support_sequence_diagram',
					'label'   => __( 'Sequence Diagrams', 'wp-githuber-md' ),
					'desc'    => __( 'Support <a href="https://terryl.in/en/githuber-md-sequence-diagrams/" target="_blank">js-sequence-diagrams</a> to turn text into vector UML sequence diagrams.', 'wp-githuber-md' ),
					'type'    => 'radio',
					'default' => 'no',
					'options' => array(
						'yes' => __( 'Yes', 'wp-githuber-md' ),
						'no'  => __( 'No', 'wp-githuber-md' ),
					)
				),

				array(
					'name'    => 'support_task_list',
					'label'   => __( 'Task List', 'wp-githuber-md' ),
					'desc'    => __( 'Support Github Flavored Markdown task lists.', 'wp-githuber-md' ),
					'type'    => 'radio',
					'default' => 'no',
					'options' => array(
						'yes' => __( 'Yes', 'wp-githuber-md' ),
						'no'  => __( 'No', 'wp-githuber-md' ),
					)
				),

				array(
					'name'    => 'support_mermaid',
					'label'   => __( 'Mermaid', 'wp-githuber-md' ),
					'desc'    => __( 'Support <a href="https://mermaidjs.github.io/" target="_blank">Mermaid.js</a>, more information please visit the link.', 'wp-githuber-md' ),
					'type'    => 'radio',
					'default' => 'no',
					'options' => array(
						'yes' => __( 'Yes', 'wp-githuber-md' ),
						'no'  => __( 'No', 'wp-githuber-md' ),
					)
				),

				/*

				array(
					'name'    => 'support_toc',
					'label'   => __( 'Table of Content', 'wp-githuber-md' ),
					'desc'    => __( 'Display a TOC in the every first section.', 'wp-githuber-md' ),
					'type'    => 'radio',
					'default' => 'no',
					'options' => array(
						'yes' => __( 'Yes', 'wp-githuber-md' ),
						'no'  => __( 'No', 'wp-githuber-md' )
					)
				),

				array(
					'name'    => 'support_emoji',
					'label'   => __( 'Emoji', 'wp-githuber-md' ),
					'desc'    => __( 'Support Emoji in posts.', 'wp-githuber-md' ),
					'type'    => 'radio',
					'default' => 'no',
					'options' => array(
						'yes' => __( 'Yes', 'wp-githuber-md' ),
						'no'  => __( 'No', 'wp-githuber-md' )
					)
				),

				*/

				array(
					'name'    => 'support_image_paste',
					'label'   => __( 'Image Paste', 'wp-githuber-md' ),
					'desc'    => githuber_load_view( 'setting/image-paste' ),
					'type'    => 'radio',
					'default' => 'no',
					'options' => array(
						'yes' => __( 'Yes', 'wp-githuber-md' ),
						'no'  => __( 'No', 'wp-githuber-md' ),
					)
				),
			),

			'githuber_modules' =>  array(

				array(
					'name'  => '_TITLE_',
					'label' => __( 'Syntax Highlight', 'wp-githuber-md' ),
					'desc'  => __( 'prism.js', 'wp-githuber-md' ),
				),

				array(
					'name'    => 'prism_theme',
					'label'   => __( 'Theme', 'wp-githuber-md' ),
					'desc'    => __( 'Choose a perferred theme for the syntax highlighter.', 'wp-githuber-md' ),
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
					'label'   => __( 'Line Number', 'wp-githuber-md' ),
					'desc'    => __( 'Show line number in code area?', 'wp-githuber-md' ),
					'type'    => 'radio',
					'default' => 'no',
					'options' => array(
						'yes' => __( 'Yes', 'wp-githuber-md' ),
						'no'  => __( 'No', 'wp-githuber-md' ),
					)
				),

				array(
					'name'    => 'prism_src',
					'label'   => __( 'File Host', 'wp-githuber-md' ),
					'desc'    => __( 'Use this library with a CDN service or self-hosted (default)?', 'wp-githuber-md' ),
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
					'label' => __( 'KaTex', 'wp-githuber-md' ),
					'desc'  => __( 'KaTex.js', 'wp-githuber-md' ),
				),

				array(
					'name'    => 'katex_src',
					'label'   => __( 'File Host', 'wp-githuber-md' ),
					'desc'    => __( 'Use this library with a CDN service or self-hosted (default)?', 'wp-githuber-md' ),
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
					'label' => __( 'Flow Chart', 'wp-githuber-md' ),
					'desc'  => __( 'flowchart.js', 'wp-githuber-md' ),
				),

				array(
					'name'    => 'flowchart_src',
					'label'   => __( 'File Host', 'wp-githuber-md' ),
					'desc'    => __( 'Use this library with a CDN service or self-hosted (default)?', 'wp-githuber-md' ),
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
					'label' => __( 'Sequence Diagrams', 'wp-githuber-md' ),
					'desc'  => __( 'sequence-diagrams.js', 'wp-githuber-md' ),
				),

				array(
					'name'    => 'sequence_diagram_src',
					'label'   => __( 'File Host', 'wp-githuber-md' ),
					'desc'    => __( 'Use this library with a CDN service or self-hosted (default)?', 'wp-githuber-md' ),
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
					'label' => __( 'Mermaid', 'wp-githuber-md' ),
					'desc'  => __( 'mermaid.js', 'wp-githuber-md' ),
				),

				array(
					'name'    => 'mermaid_src',
					'label'   => __( 'File Host', 'wp-githuber-md' ),
					'desc'    => __( 'Use this library with a CDN service or self-hosted (default)?', 'wp-githuber-md' ),
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
					'label' => __( 'Image Paste', 'wp-githuber-md' ),
				),

				array(
					'name'    => 'image_paste_src',
					'label'   => __( 'Storage Space', 'wp-githuber-md' ),
					'desc'    => __( 'Images are stored in WordPress\'s <strong>uploads</strong> folder by default. However, you can use Imgur instead of the default place.', 'wp-githuber-md' ),
					'type'    => 'radio',
					'default' => 'default',
					'options' => array(
						'default' => __( 'default', 'wp-githuber-md' ),
						'imgur'   => __( 'imgur.com', 'wp-githuber-md' ),
					)
				),

				array(
                    'name'              => 'imgur_client_id',
					'label'             => __( 'Imgur Client ID', 'wp-githuber-md' ),
					'desc'              => githuber_load_view( 'setting/image-paste-imgur' ),
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
					'label' => __( 'Menu', 'wp-githuber-md' ),
					'desc'  => '',
				),

				array(
					'name'    => 'githuber_theme_bootstrap_menu',
					'label'   => __( 'Bootstrap 4 Menu', 'wp-githuber-md' ),
					'desc'    => __( 'Use Bootstrap 4 dropdown menu in header position. (2-layer)', 'wp-githuber-md' ),
					'type'    => 'radio',
					'default' => 'no',
					'options' => array(
						'yes' => __( 'Yes', 'wp-githuber-md' ),
						'no'  => __( 'No', 'wp-githuber-md' ),
					)
				),

				array(
					'name'  => '_TITLE_',
					'label' => __( 'Widget', 'wp-githuber-md' ),
					'desc'  => '',
				),

				array(
					'name'    => 'githuber_theme_bootstrap_toc',
					'label'   => __( 'Bootstrap 4 TOC', 'wp-githuber-md' ),
					'desc'    => __( 'A widget that shows a Bootstrap 4 styled TOC deponds on your post content.', 'wp-githuber-md' ),
					'type'    => 'radio',
					'default' => 'no',
					'options' => array(
						'yes' => __( 'Yes', 'wp-githuber-md' ),
						'no'  => __( 'No', 'wp-githuber-md' ),
					)
				),

				array(
					'name'  => '_TITLE_',
					'label' => __( 'Post Type', 'wp-githuber-md' ),
					'desc'  => '',
				),

				array(
					'name'    => 'githuber_theme_repository',
					'label'   => __( 'GitHub Repository', 'wp-githuber-md' ),
					'desc'    => __( 'Display the stars, forks, issues from your GitHub repository.', 'wp-githuber-md' ),
					'type'    => 'radio',
					'default' => 'no',
					'options' => array(
						'yes' => __( 'Yes', 'wp-githuber-md' ),
						'no'  => __( 'No', 'wp-githuber-md' ),
					)
				),

				array(
					'name'  => '_TITLE_',
					'label' => __( 'Shortcode', 'wp-githuber-md' ),
					'desc'  => '',
				),

				array(
					'name'    => 'githuber_theme_shortcode_social_icons',
					'label'   => __( 'Social Icons', 'wp-githuber-md' ),
					'desc'    => __( 'Use social icons in author section.', 'wp-githuber-md' ),
					'type'    => 'radio',
					'default' => 'no',
					'options' => array(
						'yes' => __( 'Yes', 'wp-githuber-md' ),
						'no'  => __( 'No', 'wp-githuber-md' ),
					)
				),

				array(
					'name'  => '_TITLE_',
					'label' => __( 'Adjustment', 'wp-githuber-md' ),
					'desc'  => '',
				),

				array(
					'name'    => 'githuber_theme_adjustment_head_output',
					'label'   => __( 'Head Output', 'wp-githuber-md' ),
					'desc'    => __( 'Remove information displays in HTML source code.', 'wp-githuber-md' ),
					'type'    => 'radio',
					'default' => 'no',
					'options' => array(
						'yes' => __( 'Yes', 'wp-githuber-md' ),
						'no'  => __( 'No', 'wp-githuber-md' ),
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
					'name' => 'plugin_about_author',
					'label'   => __( 'Author', 'wp-githuber-md' ),
					'desc' => 'Terry L. from Taiwan.',
					'type' => 'html'
				),

				array(
					'name' => 'plugin_about_version',
					'label'   => __( 'Version', 'wp-githuber-md' ),
					'desc' => GITHUBER_PLUGIN_VERSION,
					'type' => 'html'
				),

				array(
					'name' => 'plugin_about_github',
					'label'   => __( 'GitHub Repository', 'wp-githuber-md' ),
					'desc' => githuber_load_view( 'setting/about-github-repo' ),
					'type' => 'html'
				),

				array(
					'name' => 'plugin_about_support',
					'label'   => __( 'Support', 'wp-githuber-md' ),
					'desc' => githuber_load_view( 'setting/about-and-support' ),
					'type' => 'html'
				),

				array(
					'name' => 'plugin_about_changelog',
					'label'   => __( 'Changelog', 'wp-githuber-md' ),
					'desc' => githuber_load_view( 'setting/about-changelog' ),
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
					__( 'WP Githuber MD ', 'wp-githuber-md' ),
					__( 'WP Githuber MD', 'wp-githuber-md' ),
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
		if ( ! current_user_can( 'manage_options' ) ) {
			return $links;
		}

		if ( $file == $this->githuber_plugin_name ) {
			$links[] = '<a href="' . admin_url( "plugins.php?page=" . $this->menu_slug ) . '">' . __( 'Settings', 'wp-githuber-md' ) . '</a>';
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
			$links[] = '<a href="https://github.com/terrylinooo/githuber-md" target="_blank">' . __( 'View GitHub project', 'wp-githuber-md' ) . '</a>';
			$links[] = '<a href="https://github.com/terrylinooo/githuber-md/issues" target="_blank">' . __( 'Report issues', 'wp-githuber-md' ) . '</a>';
		}
		return $links;
	}
}
