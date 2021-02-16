<?php
/**
 * Class Setting
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Githuber
 * @since 1.0.0
 * @version 1.7.0
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
	public $menu_position = 'options';

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
			self::$setting_api = new \Githuber_Settings_API();
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
		//wp_enqueue_style( 'custom_wp_admin_css', $this->githuber_plugin_url . 'assets/css/admin-style.css', array(), $this->version, 'all' );
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

		$settings = $this->get_fields();

		if ( $GLOBALS['wp_version'] < '4.5' ) {
			// sequence_diagram uses underscore.js, and it has some conflict issues with WordPress's plupload uploader in older vision.
			// So, we hide this option in older version.
			foreach ( $settings['githuber_markdown'] as $k => $v ) {
				if ( 'support_sequence_diagram' === $v['name'] ) {
					unset( $settings['githuber_markdown'][ $k ] );
				}
			}
			foreach ( $settings['githuber_modules'] as $k => $v ) {
				if ( 'sequence_diagram_src' === $v['name'] ) {
					unset( $settings['githuber_modules'][ $k-1 ] );
					unset( $settings['githuber_modules'][ $k ] );
				}
			}
		}

		self::$setting_api->set_fields( $settings );

		// initialize them.
		self::$setting_api->admin_init();

		self::$settings = $settings;
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
				'id'    => 'githuber_extensions',
				'title' => __( 'Extensions', 'wp-githuber-md' ),
			),

			array(
				'id'    => 'githuber_preferences',
				'title' => __( 'Preferences', 'wp-githuber-md' ),
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

		$support_post_types = get_post_types( array( 'public' => true ), 'objects' );

		$post_type_options = array();

		foreach($support_post_types as $post_type) {
			if( 'attachment' !== $post_type->name ) {
				$post_type_options[ $post_type->name ] = $post_type->label;
			}
		}

		$system_lang             = get_locale();
		$default_spellcheck_lang = 'en_GB';
		$spellcheck_warning      = '';

		$spellcheck_lang_list = array(
			'af_ZA' => 'af_ZA',
			'bg_BG' => 'bg_BG',
			'ca_ES' => 'ca_ES',
			'cs_CZ' => 'cs_CZ',
			'cy_GB' => 'cy_GB',
			'da_DK' => 'da_DK',
			'de_DE' => 'de_DE',
			'el_GR' => 'el_GR',
			'en_AU' => 'en_AU',
			'en_CA' => 'en_CA',
			'en_GB' => 'en_GB',
			'en_US' => 'en_US',
			'es_ES' => 'es_ES',
			'et_EE' => 'et_EE',
			'fa_IR' => 'fa_IR',
			'fr_FR' => 'fr_FR',
			'he_IL' => 'he_IL',
			'hi_IN' => 'hi_IN',
			'hr_HR' => 'hr_HR',
			'hu_HU' => 'hu_HU',
			'hy'    => 'hy',
			'id_ID' => 'id_ID',
			'it_IT' => 'it_IT',
			'ko'    => 'ko',
			'lt_LT' => 'lt_LT',
			'lv_LV' => 'lv_LV',
			'nb_NO' => 'nb_NO',
			'nl_NL' => 'nl_NL',
			'pl_PL' => 'pl_PL',
			'pt_BR' => 'pt_BR',
			'pt_PT' => 'pt_PT',
			'ro_RO' => 'ro_RO',
			'ru_RU' => 'ru_RU',
			'sh'    => 'sh',
			'sk_SK' => 'sk_SK',
			'sl_SL' => 'sl_SL',
			'sq'    => 'sq',
			'sr'    => 'sr',
			'sv_SE' => 'sv_SE',
			'ta_IN' => 'ta_IN',
			'tg_TG' => 'tg_TG',
			'tr'    => 'tr',
			'uk_UA' => 'uk_UA',
			'vi_VI' => 'vi_VI',
			'vi_VN' => 'vi_VN',
		);

		if ( array_key_exists( $system_lang, $spellcheck_lang_list ) ) {
			$default_spellcheck_lang = $system_lang;
			$spellcheck_warning      = '<br /><span style="color: #0081ab">' . __( 'Your system langauge is supported.', 'wp-githuber-md' ) . ' (' . $system_lang . ')</span>';
		} else {
			$spellcheck_warning = '<br /><span style="color: #b00000">' . __( 'Your system langauge is not supported.', 'wp-githuber-md' ) . ' (' . $system_lang . ')</span>';
		}

		return array(

			'githuber_markdown' => array(

				array(
					'section_title' => true,
					'label' => __( 'Writing', 'wp-githuber-md' ),
				),

				array(
					'name'    => 'enable_markdown_for_post_types',
					'label'   => __( 'Enable', 'wp-githuber-md' ),
					'desc'    => __( 'Which post types you would like to enable Markdown editor for.', 'wp-githuber-md' ),
					'type'    => 'multicheck',
					'options' => $post_type_options,
					'default' => array(
						'post' => 'post',
						'page' => 'page',
					)
				),

				/*

				array(
					'name'    => 'enable_markdown_for_comment',
					'label'   => '',
					'desc'    => __( 'Enable Markdown for comments.', 'wp-githuber-md' ),
					'type'    => 'multicheck',
					'options' => array(
						'commenting' => __( 'Comments', 'wp-githuber-md' )
					)
				),

				*/

				array(
					'name'    => 'disable_revision',
					'label'   => __( 'Disable Revision', 'wp-githuber-md' ),
					'desc'    => __( 'If you think the revision function is annoying when you\'re writing, you can to disable it.', 'wp-githuber-md' ),
					'type'    => 'toggle',
					'size'    => 'sm',
					'default' => 'no',
				),

				array(
					'name'    => 'disable_autosave',
					'class'   => 'disable_autosave',
					'label'   => __( 'Disable Auto-save', 'wp-githuber-md' ),
					'desc'    => __( 'If you think the auto-save function is annoying when you\'re writing, you can to disable it.', 'wp-githuber-md' ),
					'type'    => 'toggle',
					'size'    => 'sm',
					'default' => 'yes',
				),

				array(
					'name'    => 'editor_spell_check',
					'label'   => __( 'Spell Check', 'wp-githuber-md' ),
					'desc'    => __( 'Enable spell check on the input. (This feature does not apply to code blocks.)', 'wp-githuber-md' ),
					'type'    => 'toggle',
					'size'    => 'sm',
					'default' => 'no',
				),

				array(
					'name'    => 'editor_spell_check_lang',
					'label'   => __( 'Language', 'wp-githuber-md' ),
					'desc'    => __( 'Please specify your language for spell check in the setting above.', 'wp-githuber-md' ) . $spellcheck_warning,
					'type'    => 'select',
					'default' => $default_spellcheck_lang,
					'options' => $spellcheck_lang_list,
				),

				array(
					'name'    => 'editor_match_highlighter',
					'label'   => __( 'Match Highlighter', 'wp-githuber-md' ),
					'desc'    => __( 'Everywhere else in your text where <strong>current word</strong> appears will automatically illuminate.', 'wp-githuber-md' ),
					'type'    => 'toggle',
					'default' => 'no',
				),

				array(
					'section_title' => true,
					'label' => __( 'Meta Boxes', 'wp-githuber-md' ),
				),

				array(
					'name'    => 'html_to_markdown',
					'class'   => 'html_to_markdown',
					'label'   => __( 'HTML-to-Markdown', 'wp-githuber-md' ),
					'desc'    => githuber_load_view( 'setting/html-to-markdown' ),
					'type'    => 'toggle',
					'size'    => 'sm',
					'default' => 'yes',
				),

				array(
					'name'    => 'markdown_editor_switcher',
					'class'   => 'markdown_editor_switcher',
					'label'   => __( 'Markdown Editor Switcher', 'wp-githuber-md' ),
					'desc'    => githuber_load_view( 'setting/markdown-editor-switcher' ),
					'type'    => 'toggle',
					'size'    => 'sm',
					'default' => 'yes',
				),

				array(
					'name'    => 'fetch_remote_image',
					'class'   => 'fetch_remote_image',
					'label'   => __( 'Fetch Remote Image', 'wp-githuber-md' ),
					'desc'    => __( 'A remote image means that it is not a URL from your site. This option allows you to fetch remote images and save them into local folder.', 'wp-githuber-md' ),
					'type'    => 'toggle',
					'size'    => 'sm',
					'default' => 'no',
				),

				array(
					'name'    => 'keyword_suggestion_tool',
					'class'   => 'keyword_suggestion_tool',
					'label'   => __( 'Keyword Suggestion Tool', 'wp-githuber-md' ),
					'desc'    => __( 'This keyword suggestion tool can give you a list of long-tail terms based on the keyword you enter. If you are good in On-page SEO skills, it will help you a lot in writing. Data source is from Google Suggestions.', 'wp-githuber-md' ),
					'type'    => 'toggle',
					'size'    => 'sm',
					'default' => 'no',
				),

				array(
					'section_title' => true,
					'label' => __( 'Markdown Editor', 'wp-githuber-md' ),
				),

				array(
					'name'    => 'editor_live_preview',
					'label'   => __( 'Live Preview', 'wp-githuber-md' ),
					'desc'    => __( 'Split editor into two panes to display a live preview when editing post.', 'wp-githuber-md' ),
					'type'    => 'toggle',
					'size'    => 'sm',
					'default' => 'yes',
				),

				array(
					'name'    => 'editor_sync_scrolling',
					'label'   => __( 'Sync Scrolling', 'wp-githuber-md' ),
					'desc'    => __( 'Synchronize scrolling of two editor panes by content.', 'wp-githuber-md' ),
					'type'    => 'toggle',
					'size'    => 'sm',
					'default' => 'yes',
				),

				array(
					'name'    => 'editor_html_decode',
					'label'   => __( 'HTML Decode', 'wp-githuber-md' ),
					'desc'    => __( 'Allow all HTML tags and attributes in the Markdown Editor.', 'wp-githuber-md' ),
					'type'    => 'toggle',
					'size'    => 'sm',
					'default' => 'yes',
				),

				array(
					'name'    => 'editor_line_number',
					'label'   => __( 'Line Number', 'wp-githuber-md' ),
					'desc'    => __( 'Display line number in the Markdown Editor.', 'wp-githuber-md' ),
					'type'    => 'toggle',
					'size'    => 'sm',
					'default' => 'yes',
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


			),

			'githuber_modules' =>  array(

				array(
					'label'         => __( 'Syntax Highlight', 'wp-githuber-md' ),
					'section_title' => true,
					'location_id'   => 'syntax-highlight',
					'desc'          => __( 'prism.js', 'wp-githuber-md' ),
				),

				array(
					'name'        => 'support_prism',
					//'label'     => __( 'Syntax Highlight', 'wp-githuber-md' ),
					'desc'        => __( 'Highligh the syntax in your code snippets by Prism.js', 'wp-githuber-md' ) . '<br />' . __( 'This option is not available if you choose another highlighter modules.', 'wp-githuber-md' ),
					'type'        => 'toggle',
					'has_child'   => true,
					'location_id' => 'syntax-highlight',
					'default'     => 'no',
				),

				array(
					'name'    => 'prism_theme',
					'label'   => __( 'Theme', 'wp-githuber-md' ),
					'desc'    => __( 'Choose a perferred theme for the syntax highlighter.', 'wp-githuber-md' ),
					'type'    => 'select',
					'default' => 'default',
					'parent'  => 'support_prism',
					'options' => array(
						'default'        => 'default',
						'dark'           => 'dark',
						'funky'          => 'funky',
						'okaidia'        => 'okaidia',
						'twilight'       => 'twilight',
						'tomorrow'       => 'tomorrow',
						'coy'            => 'coy',
						'solarizedlight' => 'solarizedlight',
					),
				),

				array(
					'name'    => 'prism_line_number',
					'label'   => __( 'Line Number', 'wp-githuber-md' ),
					'desc'    => __( 'Show line number in code area?', 'wp-githuber-md' ),
					'type'    => 'toggle',
					'size'    => 'sm',
					'default' => 'no',
					'parent'  => 'support_prism',
				),

				array(
					'name'    => 'prism_src',
					'label'   => __( 'File Host', 'wp-githuber-md' ),
					'desc'    => __( 'Use this library with a CDN service or self-hosted (default)?', 'wp-githuber-md' ),
					'type'    => 'radio',
					'default' => 'default',
					'parent'  => 'support_prism',
					'options' => array(
						'default'    => __( 'default', 'wp-githuber-md' ),
						'cloudflare' => 'cdnjs.cloudflare.com',
						'jsdelivr'   => 'cdn.jsdelivr.net',
					)
				),

				array(
					'label'   => __( 'Example', 'wp-githuber-md' ),
					'desc'    => githuber_load_view( 'example/prism' ),
					'type'    => 'html',
					'parent'  => 'support_prism',
				),

				// -------------------------------------------------------------------//

				array(
					'label'         => __( 'Syntax Highlight', 'wp-githuber-md' ),
					'section_title' => true,
					'location_id'   => 'syntax-highlight-js',
					'desc'          => __( 'highlight.js', 'wp-githuber-md' ),
				),

				array(
					'name'        => 'support_highlight',
					//'label'     => __( 'Syntax Highlight', 'wp-githuber-md' ),
					'desc'        => __( 'Highligh the syntax in your code snippets by Highlight.js', 'wp-githuber-md' ) . '<br />' . __( 'This option is not available if you choose another highlighter modules.', 'wp-githuber-md' ),
					'type'        => 'toggle',
					'has_child'   => true,
					'location_id' => 'syntax-highlight-js',
					'default'     => 'no',
				),

				array(
					'name'    => 'highlight_theme',
					'label'   => __( 'Theme', 'wp-githuber-md' ),
					'desc'    => __( 'Choose a perferred theme for the syntax highlighter.', 'wp-githuber-md' ) . ' [<a href="https://highlightjs.org/static/demo/" target="_blank">' . __( 'Demo') . '</a>]',
					'type'    => 'select',
					'default' => 'default',
					'parent'  => 'support_highlight',
					'options' => array(
						'default'                   => 'Default',
						'a11y-dark'                 => 'A 11 Y Dark',
						'a11y-light'                => 'A 11 Y Light',
						'agate'                     => 'Agate',
						'an-old-hope'               => 'An Old Hope',
						'androidstudio'             => 'Android Studio',
						'arduino-light'             => 'Arduino Light',
						'arta'                      => 'Arta',
						'ascetic'                   => 'Ascetic',
						'atelier-cave-dark'         => 'Atelier Cave Dark',
						'atelier-cave-light'        => 'Atelier Cave Light',
						'atelier-dune-dark'         => 'Atelier Dune Dark',
						'atelier-dune-light'        => 'Atelier Dune Light',
						'atelier-estuary-dark'      => 'Atelier Estuary Dark',
						'atelier-estuary-light'     => 'Atelier Estuary Light',
						'atelier-forest-dark'       => 'Atelier Forest Dark',
						'atelier-forest-light'      => 'Atelier Forest Light',
						'atelier-heath-dark'        => 'Atelier Heath Dark',
						'atelier-heath-light'       => 'Atelier Heath Light',
						'atelier-lakeside-dark'     => 'Atelier Lakeside Dark',
						'atelier-lakeside-light'    => 'Atelier Lakeside Light',
						'atelier-plateau-dark'      => 'Atelier Plateau Dark',
						'atelier-plateau-light'     => 'Atelier Plateau Light',
						'atelier-savanna-dark'      => 'Atelier Savanna Dark',
						'atelier-savanna-light'     => 'Atelier Savanna Light',
						'atelier-seaside-dark'      => 'Atelier Seaside Dark',
						'atelier-seaside-light'     => 'Atelier Seaside Light',
						'atelier-sulphurpool-dark'  => 'Atelier Sulphurpool Dark',
						'atelier-sulphurpool-light' => 'Atelier Sulphurpool Light',
						'atom-one-dark-reasonable'  => 'Atom One Dark Reasonable',
						'atom-one-dark'             => 'Atom One Dark',
						'atom-one-light'            => 'Atom One Light',
						'brown-paper'               => 'Brown Paper',
						'codepen-embed'             => 'Codepen Embed',
						'color-brewer'              => 'Color Brewer',
						'darcula'                   => 'Darcula',
						'dark'                      => 'Dark',
						'darkula'                   => 'Darkula',
						'docco'                     => 'Docco',
						'dracula'                   => 'Dracula',
						'far'                       => 'Far',
						'foundation'                => 'Foundation',
						'github-gist'               => 'Github Gist',
						'github'                    => 'Github',
						'gml'                       => 'Gml',
						'googlecode'                => 'Google Code',
						'grayscale'                 => 'Grayscale',
						'gruvbox-dark'              => 'Gruvbox Dark',
						'gruvbox-light'             => 'Gruvbox Light',
						'hopscotch'                 => 'Hopscotch',
						'hybrid'                    => 'Hybrid',
						'idea'                      => 'Idea',
						'ir-black'                  => 'Ir Black',
						'isbl-editor-dark'          => 'Isbl Editor Dark',
						'isbl-editor-light'         => 'Isbl Editor Light',
						'kimbie.dark'               => 'Kimbie Dark',
						'kimbie.light'              => 'Kimbie Light',
						'lightfair'                 => 'Lightfair',
						'magula'                    => 'Magula',
						'mono-blue'                 => 'Mono Blue',
						'monokai-sublime'           => 'Monokai Sublime',
						'monokai'                   => 'Monokai',
						'nord'                      => 'Nord',
						'obsidian'                  => 'Obsidian',
						'ocean'                     => 'Ocean',
						'paraiso-dark'              => 'Paraiso Dark',
						'paraiso-light'             => 'Paraiso Light',
						'pojoaque'                  => 'Pojoaque',
						'purebasic'                 => 'Purebasic',
						'qtcreator_dark'            => 'Qtcreator Dark',
						'qtcreator_light'           => 'Qtcreator Light',
						'railscasts'                => 'Railscasts',
						'rainbow'                   => 'Rainbow',
						'routeros'                  => 'Routeros',
						'school-book'               => 'School Book',
						'shades-of-purple'          => 'Shades Of Purple',
						'solarized-dark'            => 'Solarized Dark',
						'solarized-light'           => 'Solarized Light',
						'sunburst'                  => 'Sunburst',
						'tomorrow-night-blue'       => 'Tomorrow Night Blue',
						'tomorrow-night-bright'     => 'Tomorrow Night Bright',
						'tomorrow-night-eighties'   => 'Tomorrow Night Eighties',
						'tomorrow-night'            => 'Tomorrow Night',
						'tomorrow'                  => 'Tomorrow',
						'vs'                        => 'VS',
						'vs2015'                    => 'VS 2015',
						'xcode'                     => 'Xcode',
						'xt256'                     => 'Xt 256',
						'zenburn'                   => 'Zenburn',
					),
				),

				array(
					'name'    => 'highlight_src',
					'label'   => __( 'File Host', 'wp-githuber-md' ),
					'desc'    => __( 'Use this library with a CDN service or self-hosted (default)?', 'wp-githuber-md' ),
					'type'    => 'radio',
					'default' => 'default',
					'parent'  => 'support_highlight',
					'options' => array(
						'default'    => __( 'default', 'wp-githuber-md' ),
						'cloudflare' => 'cdnjs.cloudflare.com',
					)
				),

				array(
					'label'   => __( 'Example', 'wp-githuber-md' ),
					'desc'    => githuber_load_view( 'example/highlight-js' ),
					'type'    => 'html',
					'parent'  => 'support_highlight',
				),

				// -------------------------------------------------------------------//

				array(
					'section_title' => true,
					'location_id'   => 'clipboard',
					'label'         => __( 'Copy to Clipboard', 'wp-githuber-md' ),
					'desc'          => __( 'clipboard.js', 'wp-githuber-md' ),
				),

				array(
					'name'    => 'support_clipboard',
					'desc'    => __( 'Display a `Copy` button on the highlighting code block. Copy the text into clipboard by clicking the button.', 'wp-githuber-md' ),
					'type'        => 'toggle',
					'has_child'   => true,
					'default'     => 'no',
					'location_id' => 'clipboard',
				),

				array(
					'name'    => 'clipboard_src',
					'label'   => __( 'File Host', 'wp-githuber-md' ),
					'desc'    => __( 'Use this library with a CDN service or self-hosted (default)?', 'wp-githuber-md' ),
					'type'    => 'radio',
					'default' => 'default',
					'parent'  => 'support_clipboard',
					'options' => array(
						'default'    => __( 'default', 'wp-githuber-md' ),
						'cloudflare' => 'cdnjs.cloudflare.com',
						'jsdelivr'   => 'cdn.jsdelivr.net',
					)
				),

				array(
					'section_title' => true,
					'location_id'   => 'image-paste',
					'label'         => __( 'Image Paste', 'wp-githuber-md' ),
				),

				array(
					'name'        => 'support_image_paste',
					//'label'     => __( 'Image Paste', 'wp-githuber-md' ),
					'desc'        => githuber_load_view( 'setting/image-paste' ),
					'type'        => 'toggle',
					'has_child'   => true,
					'location_id' => 'image-paste',
					'default'     => 'no'
				),

				array(
					'name'    => 'image_paste_src',
					'label'   => __( 'Storage Space', 'wp-githuber-md' ),
					'desc'    => __( 'Images are stored in WordPress\'s <strong>uploads</strong> folder by default. However, you can use Imgur instead of the default place.', 'wp-githuber-md' ),
					'type'    => 'radio',
					'default' => 'default',
					'parent'  => 'support_image_paste',
					'options' => array(
						'default' => __( 'default', 'wp-githuber-md' ),
						'imgur'   => __( 'imgur.com', 'wp-githuber-md' ),
						'smms'    => __( 'sm.ms', 'wp-githuber-md' ),
					)
				),

				array(
                    'name'              => 'imgur_client_id',
					'label'             => __( 'Imgur Client ID', 'wp-githuber-md' ),
					'desc'              => githuber_load_view( 'setting/image-paste-imgur' ),
                    'placeholder'       => '',
                    'type'              => 'text',
					'default'           => '',
					'parent'            => 'support_image_paste',
                    'sanitize_callback' => 'sanitize_text_field',
				),

				array(
                    'name'              => 'smms_api_key',
					'label'             => __( 'sm.ms API Key', 'wp-githuber-md' ),
					'desc'              => githuber_load_view( 'setting/image-paste-smms' ),
                    'placeholder'       => '',
                    'type'              => 'text',
					'default'           => '',
					'parent'            => 'support_image_paste',
                    'sanitize_callback' => 'sanitize_text_field',
				),

				array(
                    'name'    => 'is_image_paste_media_library',
					'label'   => __( 'Upload to Media Library?', 'wp-githuber-md' ),
					'desc'    => githuber_load_view( 'setting/image-paste-media-library' ),
					'type'    => 'toggle',
					'size'    => 'sm',
					'default' => 'yes',
					'parent'  => 'support_image_paste',
				),

				array(
					'section_title' => true,
					'location_id'   => 'table-of-content',
					'label'         => __( 'Table of Content', 'wp-githuber-md' ),
				),

				array(
					'name'        => 'support_toc',
					//'label'     => __( 'Image Paste', 'wp-githuber-md' ),
					'desc'        => __( 'Support Table of Content.', 'wp-githuber-md' ),
					'type'        => 'toggle',
					'has_child'   => true,
					'location_id' => 'table-of-content',
					'default'     => 'no'
				),

				array(
                    'name'    => 'is_toc_widget',
					'label'   => __( 'Widget', 'wp-githuber-md' ),
					'desc'    => __( 'Display a TOC in the widget area for single post.', 'wp-githuber-md' ),
					'type'    => 'toggle',
					'size'    => 'sm',
					'default' => 'yes',
					'parent'  => 'support_toc',
				),

				array(
                    'name'    => 'display_toc_in_post',
					'label'   => __( 'Inside a Post', 'wp-githuber-md' ),
					'desc'    => __( 'Insert a TOC inside a post header location.', 'wp-githuber-md' ),
					'type'    => 'toggle',
					'size'    => 'sm',
					'default' => 'yes',
					'parent'  => 'support_toc',
				),

				array(
					'name'    => 'post_toc_float',
					'label'   => __( 'Float', 'wp-githuber-md' ),
					'desc'    => __( 'Would you like to float the TOC in the post to left or right?', 'wp-githuber-md' ),
					'type'    => 'radio',
					'default' => 'default',
					'parent'  => 'support_toc',
					'options' => array(
						'default' => __( 'default', 'wp-githuber-md' ),
						'right'   => __( 'right', 'wp-githuber-md' ),
						'left'    => __( 'left', 'wp-githuber-md' ),
					)
				),

				array(
                    'name'    => 'post_toc_border',
					'label'   => __( 'Border', 'wp-githuber-md' ),
					'desc'    => __( 'Would you like to show the border of the TOC in the post?', 'wp-githuber-md' ),
					'type'    => 'toggle',
					'size'    => 'sm',
					'default' => 'yes',
					'parent'  => 'support_toc',
				),

				array(
					'section_title' => true,
					'location_id'   => 'katex',
					'label'         => __( 'KaTex', 'wp-githuber-md' ),
					'desc'          => __( 'KaTex.js', 'wp-githuber-md' ),
				),

				array(
					'name'        => 'support_katex',
					//'label'     => __( 'KaTeX', 'wp-githuber-md' ),
					'desc'        => __( 'Support <a href="https://terryl.in/en/githuber-md-katax/" target="_blank">KaTeX</a> math typesetting.', 'wp-githuber-md' ),
					'type'        => 'toggle',
					'has_child'   => true,
					'default'     => 'no',
					'location_id' => 'katex',
				),

				array(
					'name'    => 'katex_src',
					'label'   => __( 'File Host', 'wp-githuber-md' ),
					'desc'    => __( 'Use this library with a CDN service or self-hosted (default)?', 'wp-githuber-md' ),
					'type'    => 'radio',
					'default' => 'default',
					'parent'  => 'support_katex',
					'options' => array(
						'default'    => __( 'default', 'wp-githuber-md' ),
						'cloudflare' => 'cdnjs.cloudflare.com',
						'jsdelivr'   => 'cdn.jsdelivr.net',
						'custom'     => __( 'custom', 'wp-githuber-md' )
					)
				),

				array(
					'label'   => __( 'Example', 'wp-githuber-md' ),
					'desc'    => githuber_load_view( 'example/katex' ),
					'type'    => 'html',
					'parent'  => 'support_katex',
				),

				array(
					'section_title' => true,
					'location_id'   => 'mermaid',
					'label'         => __( 'Mermaid', 'wp-githuber-md' ),
					'desc'          => __( 'mermaid.js', 'wp-githuber-md' ),
				),

				array(
					'name'        => 'support_mermaid',
					//'label'     => __( 'Mermaid', 'wp-githuber-md' ),
					'desc'        => __( 'Support <a href="https://terryl.in/en/githuber-md-mermaid/" target="_blank">Mermaid.js</a>, a Markdownish Syntax for Generating Charts.', 'wp-githuber-md' ),
					'type'        => 'toggle',
					'location_id' => 'mermaid',
					'has_child'   => true,
					'default'     => 'no'
				),

				array(
					'name'    => 'mermaid_src',
					'label'   => __( 'File Host', 'wp-githuber-md' ),
					'desc'    => __( 'Use this library with a CDN service or self-hosted (default)?', 'wp-githuber-md' ),
					'type'    => 'radio',
					'default' => 'default',
					'parent'  => 'support_mermaid',
					'options' => array(
						'default'    => __( 'default', 'wp-githuber-md' ),
						'cloudflare' => 'cdnjs.cloudflare.com',
						'jsdelivr'   => 'cdn.jsdelivr.net',
					)
				),

				array(
					'label'   => __( 'Example', 'wp-githuber-md' ),
					'desc'    => githuber_load_view( 'example/mermaid' ),
					'type'    => 'html',
					'parent'  => 'support_mermaid',
				),

				array(
					'section_title' => true,
					'location_id'   => 'flowchart',
					'label'         => __( 'Flow Chart', 'wp-githuber-md' ),
					'desc'          => __( 'flowchart.js', 'wp-githuber-md' ),
				),

				array(
					'name'        => 'support_flowchart',
					//'label'     => __( 'Flow Chart', 'wp-githuber-md' ),
					'desc'        => __( 'Support <a href="https://terryl.in/en/githuber-md-flow-chart/" target="_blank">flowchart.js</a> to draws simple SVG flow chart diagrams.', 'wp-githuber-md' ),
					'type'        => 'toggle',
					'has_child'   => true,
					'location_id' => 'flowchart',
					'default'     => 'no',
				),

				array(
					'name'    => 'flowchart_src',
					'label'   => __( 'File Host', 'wp-githuber-md' ),
					'desc'    => __( 'Use this library with a CDN service or self-hosted (default)?', 'wp-githuber-md' ),
					'type'    => 'radio',
					'default' => 'default',
					'parent'  => 'support_flowchart',
					'options' => array(
						'default'    => __( 'default', 'wp-githuber-md' ),
						'cloudflare' => 'cdnjs.cloudflare.com',
						'jsdelivr'   => 'cdn.jsdelivr.net',
					)
				),

				array(
					'label'   => __( 'Example', 'wp-githuber-md' ),
					'desc'    => githuber_load_view( 'example/flowchart' ),
					'type'    => 'html',
					'parent'  => 'support_flowchart',
				),

				array(
					'section_title' => true,
					'location_id'   => 'sequence-diagram',
					'label'         => __( 'Sequence Diagrams', 'wp-githuber-md' ),
					'desc'          => __( 'sequence-diagrams.js', 'wp-githuber-md' ),
				),

				array(
					'name'        => 'support_sequence_diagram',
					//'label'     => __( 'Sequence Diagrams', 'wp-githuber-md' ),
					'desc'        => __( 'Support <a href="https://terryl.in/en/githuber-md-sequence-diagrams/" target="_blank">js-sequence-diagrams</a> to turn text into vector UML sequence diagrams.', 'wp-githuber-md' ),
					'type'        => 'toggle',
					'has_child'   => true,
					'location_id' => 'sequence-diagram',
					'default'     => 'no',
				),

				array(
					'name'    => 'sequence_diagram_src',
					'label'   => __( 'File Host', 'wp-githuber-md' ),
					'desc'    => __( 'Use this library with a CDN service or self-hosted (default)?', 'wp-githuber-md' ),
					'type'    => 'radio',
					'default' => 'default',
					'parent'  => 'support_sequence_diagram',
					'options' => array(
						'default'    => __( 'default', 'wp-githuber-md' ),
						'cloudflare' => 'cdnjs.cloudflare.com',
						'jsdelivr'   => 'cdn.jsdelivr.net',
					)
				),

				array(
					'label'   => __( 'Example', 'wp-githuber-md' ),
					'desc'    => githuber_load_view( 'example/sequence' ),
					'type'    => 'html',
					'parent'  => 'support_sequence_diagram',
				),

				array(
					'section_title' => true,
					'location_id'   => 'mathjax',
					'label'         => __( 'MathJax', 'wp-githuber-md' ),
					'desc'          => __( 'MathJax.js', 'wp-githuber-md' ),
				),
			
				array(
					'name'        => 'support_mathjax',
					'desc'        => __( 'MathJax displays mathematical notation in web browsers, using LaTeX markup. ', 'wp-githuber-md' ),
					'type'        => 'toggle',
					'has_child'   => true,
					'location_id' => 'mathjax',
					'default'     => 'no',
				),
	
				array(
					'name'    => 'mathjax_src',
					'label'   => __( 'File Host', 'wp-githuber-md' ),
					'desc'    => __( 'Use this library with a CDN service or self-hosted (default)?', 'wp-githuber-md' ),
					'type'    => 'radio',
					'default' => 'cloudflare',
					'parent'  => 'support_mathjax',
					'options' => array(
						'default'    => __( 'default', 'wp-githuber-md' ),
						'cloudflare' => 'cdnjs.cloudflare.com',
						'jsdelivr'   => 'cdn.jsdelivr.net',
					)
				),
	
				array(
					'label'   => __( 'Example', 'wp-githuber-md' ),
					'desc'    => githuber_load_view( 'example/mathjax' ),
					'type'    => 'html',
					'parent'  => 'support_mathjax',
				),

				array(
					'section_title' => true,
					'location_id'   => 'support-emojify',
					'label'         => __( 'Emojify', 'wp-githuber-md' ),
					'desc'          => __( 'emojify.js', 'wp-githuber-md' ),
				),

				array(
					'name'        => 'support_emojify',
					'desc'        => __( 'Display emojis on Markdown editor preview pane and frontend posts.', 'wp-githuber-md' ),
					'type'        => 'toggle',
					'has_child'   => true,
					'location_id' => 'support-emoji',
					'default'     => 'no',
				),

				array(
					'name'    => 'emojify_src',
					'label'   => __( 'File Host', 'wp-githuber-md' ),
					'desc'    => __( 'Use this library with a CDN service or self-hosted (default)?', 'wp-githuber-md' ),
					'type'    => 'radio',
					'default' => 'default',
					'parent'  => 'support_emojify',
					'options' => array(
						'default'    => __( 'default', 'wp-githuber-md' ),
						'cloudflare' => 'cdnjs.cloudflare.com',
						'jsdelivr'   => 'cdn.jsdelivr.net',
					)
				),

				array(
					'name'    => 'emojify_emoji_size',
					'label'   => __( 'Image Size', 'wp-githuber-md' ),
					'desc'    => __( 'What size would you want the emojis to be in your posts.', 'wp-githuber-md' ),
					'type'    => 'radio',
					'default' => '1.5em',
					'parent'  => 'support_emojify',
					'options' => array(
						'1em' => '1.00 (em)',
						'1.125em' => '1.125 (em)',
						'1.25em' => '1.250 (em)',
						'1.375em' => '1.375 (em)',
						'1.5em' => '1.500 (em)' . ' - ' . __( 'default', 'wp-githuber-md' ),
						'1.625em' => '1.625 (em)',
						'1.75em' => '1.750 (em)',
					)
				),
			),

			'githuber_extensions' => array(

				array(
					'name'    => 'support_mardown_extra',
					'label'   => __( 'Markdown Extra', 'wp-githuber-md' ),
					'desc'    => githuber_load_view( 'setting/markdown-extra' ),
					'type'    => 'toggle',
					'size'    => 'sm',
					'default' => 'no'
				),
				array(
					'name'    => 'support_task_list',
					'label'   => __( 'GFM Task List', 'wp-githuber-md' ),
					'desc'    => __( 'Support Github Flavored Markdown task lists.', 'wp-githuber-md' ),
					'type'    => 'toggle',
					'size'    => 'sm',
					'default' => 'no'
				),

				array(
					'desc' => githuber_load_view( 'example/gfm-task-list' ),
					'type' => 'html',
				),

				array(
					'label'         => __( 'Githuber MD Extensions', 'wp-githuber-md' ),
					'section_title' => true,
				),

				array(
					'name'    => 'support_html_figure',
					'label'   => __( 'HTML5 Figure', 'wp-githuber-md' ),
					'desc'    => githuber_load_view( 'example/html5-figure' ),
					'type'    => 'toggle',
					'size'    => 'sm',
					'default' => 'no'
				),

				array(
					'name'    => 'support_inline_code_keyboard_style',
					'label'   => __( 'Inline Code Block with Keyboard Style', 'wp-githuber-md' ),
					'desc'    => githuber_load_view( 'example/inline-code-keyboard-style' ),
					'type'    => 'toggle',
					'size'    => 'sm',
					'default' => 'no'
				),
			),

			'githuber_preferences' => array(
				array(
					'name'    => 'post_link_target_attribute',
					'label'   => __( 'Link Opening Method', 'wp-githuber-md' ),
					'desc'    => __( 'For links in posts, please specify where to open the linked document.', 'wp-githuber-md' ),
					'type'    => 'radio',
					'default' => '_self',
					'options' => array(
						'_self'  => __( 'Same window. (default)', 'wp-githuber-md' ),
						'_blank' => __( 'New window.', 'wp-githuber-md' ),
					)
				),

				array(
					'name'    => 'allow_shortcode',
					'label'   => __( 'Shortcode', 'wp-githuber-md' ),
					'desc'    => __( 'Allow using shortcode in Markdown text.', 'wp-githuber-md' ) . '<br />' . __( 'Please understand that shortcode is processed to HTML by PHP, not JavaScript, therefore live-preview panel does not support it.', 'wp-githuber-md' ),
					'type'    => 'toggle',
					'default' => 'yes',
				),

				array(
					'name'    => 'smart_quotes',
					'label'   => __( 'Smart Quotes', 'wp-githuber-md' ),
					'desc'    => githuber_load_view( 'setting/smart-quotes' ),
					'type'    => 'toggle',
					'default' => 'yes',
				),

				array(
					'name'    => 'restore_ampersands',
					'label'   => __( 'Ampersands in URL', 'wp-githuber-md' ),
					'desc'    => __( 'Replace `&amp;amp;` to `&` in URLs.', 'wp-githuber-md' ),
					'type'    => 'toggle',
					'default' => 'no',
				),

				array(
					'name'    => 'support_wpseo_analysis',
					'label'   => __( 'Yoast SEO Analysis', 'wp-githuber-md' ),
					'desc'    => __( "Support Yoast SEO readability analysis.", 'wp-githuber-md' ),
					'type'    => 'toggle',
					'default' => 'no',
				),

				array(
					'name'    => 'clear_all_settings',
					'label'   => __( 'Clear all Settings', 'wp-githuber-md' ),
					'desc'    => __( 'Clear all settings when uninstalling WP GitHuber MD.', 'wp-githuber-md' ),
					'type'    => 'toggle',
					'default' => 'no',
				),

				array(
					'name'    => 'decode_code_blocks',
					'label'   => __( 'Decode Code Blocks', 'wp-githuber-md' ),
					'desc'    => sprintf( __( 'If you have met issues similar to issue #30, <a href="%s" target="_blank">#89</a>, try enabling this.', 'wp-githuber-md' ), 'https://github.com/terrylinooo/githuber-md/issues/89' ),
					'type'    => 'toggle',
					'default' => 'no',
				),

				array(
					'name'    => 'richeditor_by_default',
					'label'   => __( 'Default: Rich Editor', 'wp-githuber-md' ),
					'desc'    => __( 'Notice: Your users might be confused because that if they swith to Markdown editor and then switch back to Rich editor or Gutenberg, the Markdown text of that single post will be lost. That is because that Rich editor and Markdown editor use differnt fields to store data.', 'wp-githuber-md' ),
					'type'    => 'toggle',
					'default' => 'no',
				),
			),

			'githuber_about' => array(

				array(
					'name'  => 'plugin_about_author',
					'label' => __( 'Author', 'wp-githuber-md' ),
					'desc'  => 'Terry L. @ Taiwan.',
					'type'  => 'html'
				),

				array(
					'name'  => 'plugin_about_version',
					'label' => __( 'Version', 'wp-githuber-md' ),
					'desc'  => GITHUBER_PLUGIN_VERSION,
					'type'  => 'html'
				),

				array(
					'name'  => 'plugin_about_github',
					'label' => __( 'GitHub Repository', 'wp-githuber-md' ),
					'desc'  => githuber_load_view( 'setting/about-github-repo' ),
					'type'  => 'html'
				),

				array(
					'name'  => 'plugin_theme',
					'label' => __( 'Theme', 'wp-githuber-md' ),
					'desc'  => githuber_load_view( 'setting/theme-description' ),
					'type'  => 'html'
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
					array( $this, 'setting_plugin_page' )
				);
				break;
		}
	}

	/**
	* Display the plugin settings options page.
	*/
	public function setting_plugin_page() {

		$git_url_plugin = 'https://github.com/terrylinooo/githuber-md';

		echo '<div class="githuber-md-info-bar">';
		echo '	<div class="logo-info"><img src="' . GITHUBER_PLUGIN_URL . '/assets/images/logo.png" class="githuber-md-logo"></div>';
		echo '	<div class="version-info">';
		echo '    <a href="' . $git_url_plugin . '/issues" target="_blank">' . __( 'Report an issue', 'wp-githuber-md' ) . '</a>  ';
		echo '    ' . __( 'Version', 'wp-githuber-md' ) . ': <a href="' . $git_url_plugin . '" target="_blank">' . GITHUBER_PLUGIN_VERSION . '</a>  ';
		echo '  </div>';
		echo '</div>';
		echo '<div class="wrap">';

		settings_errors();

		self::$setting_api->show_navigation();
		self::$setting_api->show_forms();

		echo '<div>' . __( 'Maintain social distancing. Wash your hands frequently.', 'wp-githuber-md' ) . '</div>';
		echo '<div>' . __( 'Stay at home. Write your articles with Githuber MD.', 'wp-githuber-md' ) . '</div>';

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
			$links[] = '<a href="' . admin_url( "options-general.php?page=" . $this->menu_slug ) . '">' . __( 'Settings', 'wp-githuber-md' ) . '</a>';
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
