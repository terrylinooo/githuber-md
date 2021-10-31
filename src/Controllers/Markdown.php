<?php
/**
 * Class Markdown
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Githuber
 * @since 1.0.0
 * @version 1.11.0
 *
 * A lot of code snippets are from Jetpack Markdown module, we don't reinvent the wheel, however, we modify it for our needs.
 * @link https://github.com/Automattic/jetpack/blob/master/modules/markdown/easy-markdown.php
 */

namespace Githuber\Controller;
use Githuber\Controller as Controller;
use Githuber\Module as Module;
use Githuber\Model as Model;

class Markdown extends ControllerAbstract {

	/**
	 * We use a JavaScript library that is called `EditorMd`, and this is its version number.
	 *
	 * @link https://github.com/pandao/editor.md
	 *
	 * @var string
	 */
	public $editormd_varsion = '1.5.0.14';

	/**
	 * The Post Type support from Markdown controller.
	 *
	 * @var string
	 */
	public $post_type;

	/**
	 * Constants.
	 */
	const MD_POST_TYPE           = 'githuber_markdown';
	const MD_POST_META           = '_is_githuber_markdown';
	const MD_POST_META_ENABLED   = '_is_githuber_markdown_enabled';
	const MD_POST_META_PRISM     = '_githuber_prismjs';
	const MD_POST_META_HIGHLIGHT = '_githuber_highlightjs';
	const MD_POST_META_SEQUENCE  = '_is_githuber_sequence';
	const MD_POST_META_FLOW      = '_is_githuber_flow_chart';
	const MD_POST_META_KATEX     = '_is_githuber_katex';
	const MD_POST_META_MATHJAX   = '_is_githuber_mathjax';
	const MD_POST_META_MERMAID   = '_is_githuber_mermaid';

	const JETPACK_MD_POST_META   = '_wpcom_is_markdown';

	/**
	 * Parser's instance.
	 */
	private static $parser_instance;

	/**
	 * Markdown Model instance.
	 */
	private static $model_instance;

	/**
	 * Flags
	 *
	 * @var array
	 */
	private $monitoring = array( 'post' => array(), 'parent' => array() );

	/**
	 * To ensure that our munged posts over xml-rpc are removed from the cache.
	 *
	 * @var array
	 */
	public $posts_to_uncache = array();

	/**
	 * Module supprt.
	 *
	 * @var boolean
	 */
	public $is_support_prism     = false;
	public $is_support_highlight = false;
	public $is_support_task_list = false;
	public $is_support_katex     = false;
	public $is_support_flowchart = false;
	public $is_support_sequence  = false;
	public $is_support_mermaid   = false;
	public $is_support_toc       = false;
	public $is_support_mathjax   = false;

	public $markdown_this_post = true;

	/**
	 * Constructer.
	 */
	public function __construct() {
		parent::__construct();

		if ( ! self::$model_instance ) {
			self::$model_instance = new Model\Markdown();
		}

		if ( 'yes' === githuber_get_option( 'support_prism', 'githuber_modules' ) ) {
			$this->is_support_prism = true;
		}

		if ( 'yes' === githuber_get_option( 'support_highlight', 'githuber_modules' ) ) {
			$this->is_support_highlight = true;
		}

		if ( 'yes' === githuber_get_option( 'support_task_list', 'githuber_extensions' ) ) {
			$this->is_support_task_list = true;
		}

		if ( 'yes' === githuber_get_option( 'support_katex', 'githuber_modules' ) ) {
			$this->is_support_katex = true;
		}

		if ( 'yes' === githuber_get_option( 'support_flowchart', 'githuber_modules' ) ) {
			$this->is_support_flowchart = true;
		}

		if ( 'yes' === githuber_get_option( 'support_sequence_diagram', 'githuber_modules' ) ) {
			$this->is_support_sequence = true;
		}

		if ( 'yes' === githuber_get_option( 'support_mermaid', 'githuber_modules' ) ) {
			$this->is_support_mermaid = true;
		}

		if ( 'yes' === githuber_get_option( 'support_mathjax', 'githuber_modules' ) ) {
			$this->is_support_mathjax = true;
		}

		// Load TOC widget. //
		if ( 'yes' == githuber_get_option( 'support_toc', 'githuber_modules' ) ) {
			if ( 'yes' == githuber_get_option( 'display_toc_in_post', 'githuber_modules' ) ) {
				$this->is_support_toc = true;
			}
		}
	}

	/**
	 * Initialize.
	 */
	public function init() {

		// Force-disable Jetpack's Markdown module if it is active.
		add_filter( 'option_jetpack_active_modules', array( $this, 'admin_githuber_disable_jetpack_markdown' ) );

		$enabled_post_types = githuber_get_option( 'enable_markdown_for_post_types', 'githuber_markdown' );

		if ( empty( $enabled_post_types ) ) {
			$enabled_post_types = array(
				'post',
				'page',
			);
		}

		foreach( $enabled_post_types as $post_type ) {
			$support_post_types[] = $post_type;
		}

		$support_post_types = apply_filters( 'githuber_md_suppot_post_types', $support_post_types );

		array_push( $support_post_types , 'revision');

		foreach ( $support_post_types as $post_type ) {
			add_post_type_support( $post_type, self::MD_POST_TYPE );

			// Only use it in DEBUG mode.
			githuber_logger( 'add_post_type_support', array( $post_type ) );
		}

		add_action( 'admin_init', array( $this, 'admin_init' ) );

		$post_id = githuber_get_current_post_id();

		$markdown_this_post = get_metadata( 'post', $post_id, self::MD_POST_META_ENABLED, true );

		// Get post type from curren screen.
		$current_post_type = githuber_get_current_post_type();

        $args = array(
            'public'       => true,
            '_builtin'     => false, // for custom post types
            'show_in_rest' => true, // for custom post types with Gutenberg editor enabled
        );

        $custom_post_types = get_post_types( $args );

		// Feature request #98
		if ( 'yes' === githuber_get_option( 'richeditor_by_default', 'githuber_preferences' ) ) {

			if ( empty( $markdown_this_post ) || 'yes' !== $markdown_this_post ) {
				$rich_editing = new RichEditing();
				$rich_editing->enable();

				if ( empty( $current_post_type ) || 'post' === $current_post_type || 'page' === $current_post_type || in_array( $current_post_type, $custom_post_types ) ) {
					$rich_editing->enable_gutenberg();
				}

				$this->markdown_this_post = false;
			}
		}

		if ( ! empty( $current_post_type ) && ! post_type_supports( githuber_get_current_post_type(), self::MD_POST_TYPE ) ) {

			// We enable Rich editor if user not enable Markdown for current post type!
			$rich_editing = new RichEditing();
			$rich_editing->enable();

			// Custom post types are not supporting Gutenberg by default for now, so
            // We only enable Gutenberg for `post`, `page` and custom post types with Gutenberg enabled
			if ( 'post' === $current_post_type || 'page' === $current_post_type || in_array( $current_post_type, $custom_post_types ) ) {
				$rich_editing->enable_gutenberg();
			}
		} else {

			// Markdown-per-post switcher.
			if ( 'no' === $markdown_this_post ) {
				$rich_editing = new RichEditing();
				$rich_editing->enable();

				if ( 'post' === $current_post_type || 'page' === $current_post_type || in_array( $current_post_type, $custom_post_types ) ) {
					$rich_editing->enable_gutenberg();
				}

			} else {

				// Tell YoastSEO, the Markdown is enable.
				if ( 'yes' === githuber_get_option( 'support_wpseo_analysis', 'githuber_preferences' ) ) {
					add_filter( 'wpseo_is_markdown_enabled', '__return_true' );
				}

				// Okay! User enable Markdown for current current post and it's post type.
				$this->jetpack_code_snippets();
				$this->maybe_unload_for_bulk_edit();

				if ( 'yes' === githuber_get_option( 'html_to_markdown', 'githuber_markdown' ) ) {
					$html2markdown = new Controller\HtmlToMarkdown();
					$html2markdown->init();
				}

				if ( 'yes' === githuber_get_option( 'fetch_remote_image', 'githuber_markdown' ) ) {
					$fetchRemoteImage = new Controller\FetchRemoteImage();
					$fetchRemoteImage->init();
				}
			}
		}
	}

	/**
	 * Register CSS style files.
	 */
	public function admin_enqueue_styles( $hook_suffix ) {
		wp_enqueue_style( 'editmd', $this->githuber_plugin_url . '/assets/vendor/editor.md/css/editormd.min.css', array(), $this->editormd_varsion, 'all' );
	}

	/**
	 * Register JS files.
	 */
	public function admin_enqueue_scripts( $hook_suffix ) {

		if ( ! post_type_supports( get_current_screen()->post_type, self::MD_POST_TYPE ) ) {
			return;
		}

		$post_id = githuber_get_current_post_id();
		$markdown_this_post = get_metadata( 'post', $post_id, self::MD_POST_META_ENABLED, true );

		if ( 'no' === $markdown_this_post || ! $this->markdown_this_post ) {

		} else {
			wp_enqueue_script( 'editormd', $this->githuber_plugin_url . 'assets/vendor/editor.md/editormd.min.js', array( 'jquery' ), $this->editormd_varsion, true );
			wp_enqueue_script( 'githuber-md', $this->githuber_plugin_url . 'assets/js/githuber-md.js', array( 'editormd' ), $this->version, true );

			switch ( get_bloginfo( 'language' ) ) {
				case 'zh-TW':
					wp_enqueue_script( 'editor-md-lang', $this->githuber_plugin_url . 'assets/vendor/editor.md/languages/zh-tw.js', array(), $this->editormd_varsion, true );
					break;

				case 'zh-CN':
					wp_enqueue_script( 'editor-md-lang', $this->githuber_plugin_url . 'assets/vendor/editor.md/languages/zh-cn.js', array(), $this->editormd_varsion, true );
					break;

				case 'en-US':
				default:
					wp_enqueue_script( 'editor-md-lang', $this->githuber_plugin_url . 'assets/vendor/editor.md/languages/en.js', array(), $this->editormd_varsion, true );
			}

			$editormd_config_list['markdown'] = array(
				'editor_sync_scrolling',
				'editor_live_preview',
				'editor_image_paste',
				'editor_html_decode',
				'editor_toolbar_theme',
				'editor_editor_theme',
				'editor_line_number',
				'editor_spell_check',
				'editor_spell_check_lang',
				'editor_match_highlighter',
			);

			$editormd_config_list['modules'] = array(
				'support_emojify',
				'support_katex',
				'support_flowchart',
				'support_sequence_diagram',
				'support_mermaid',
				'support_mathjax',
			);

			$editormd_config_list['extensions'] = array(
				'support_task_list',
				'support_inline_code_keyboard_style',
				'support_html_figure',
			);

			$editormd_localize = array();

			foreach ( $editormd_config_list as $key => $value ) {
				foreach ( $value as $setting_name ) {
					$editormd_localize[ $setting_name ] = githuber_get_option( $setting_name, 'githuber_' . $key );
				}
			}

			$editormd_localize['editor_modules_url']   = $this->githuber_plugin_url . 'assets/vendor/editor.md/lib/';
			$editormd_localize['plugin_vendor_url']    = $this->githuber_plugin_url . 'assets/vendor/';
			$editormd_localize['editor_placeholder']   = __( 'Happy Markdowning!', 'wp-githuber-md' );
			$editormd_localize['image_paste_callback'] = admin_url( 'admin-ajax.php?action=githuber_image_paste&post_id=' . $post_id . '&_wpnonce=' . wp_create_nonce( 'image_paste_action_' . $post_id ) );
			$editormd_localize['prism_line_number']    = githuber_get_option( 'prism_line_number', 'githuber_modules' );

			// Register JS variables for the Editormd library uses.
			wp_localize_script( 'githuber-md', 'editormd_config', $editormd_localize );
		}

		/* @version 1.6.0 */
		wp_enqueue_script( 'githuber-md-mpp', $this->githuber_plugin_url . 'assets/js/githuber-md-mpp.js', array( 'jquery' ), $this->version, true );

		$metabox_data['ajax_url'] = admin_url( 'admin-ajax.php' );
		$metabox_data['post_id']  = githuber_get_current_post_id();

		wp_localize_script( 'githuber-md-mpp', 'markdown_this_post_config', $metabox_data );
	}

	/**
	 * Initalize to WP `admin_init` hook.
	 */
	public function admin_init() {

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

		if ( 'no' !== githuber_get_option( 'markdown_editor_switcher', 'githuber_markdown' ) ) {

			/* @version 1.6.0 */
			add_action( 'wp_ajax_githuber_markdown_this_post', array( $this, 'admin_githuber_markdown_this_post' ) );

			// Add the sidebar metabox to posts.
			$current_post_type = githuber_get_current_post_type();

			// Only display metabox if current post-type supports Markdown.
			if ( ! empty( $current_post_type) && post_type_supports( githuber_get_current_post_type(), self::MD_POST_TYPE ) ) {
				add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
			}
		}
	}

	public function admin_init_meta_box() {

	}

	/**
	 * Markdown parser.
	 *
	 * @return object MarkdownParser instance.
	 */
	public static function get_parser()
	{
		if ( ! self::$parser_instance ) {

			$is_markdown_extra = githuber_get_option( 'support_mardown_extra', 'githuber_extensions' );

			if ( 'yes' === $is_markdown_extra ) {
				self::$parser_instance = new Module\MarkdownExtraParser();
			} else {
				self::$parser_instance = new Module\MarkdownParser();
			}
		}
		return self::$parser_instance;
	}

	/**
	 * Is Markdown conversion for posts or comments enabled?
	 *
	 * @param string $post_action_type The type of posting action.
	 * @return bool
	 */
	public function is_md_enabled( $post_action_type ) {
		switch ( $post_action_type ) {
			case 'posting':
				return true;
				break;
			case 'commeting':
				$setting = githuber_get_option( 'enable_markdown_for_comment', 'githuber_markdown' );
				if ( isset( $setting[ $post_action_type ] ) && $setting[ $post_action_type ] === $post_action_type ) {
					return true;
				}
				break;
		}
		return false;
	}

	/**
	 * Detect the language is defined through the way recommended in the HTML5 draft: through a language-xxxx class.
	 * Find out all of them, then put them into the post meta for frontend uses.
	 *
	 * @param int    $post_id       The post ID.
	 * @param string $post_content The post content.
	 * @return void
	 */
	public function detect_code_languages( $post_id, $post_content ) {

		$prism_meta_array     = array();
		$highlight_meta_array = array();

		delete_metadata( 'post', $post_id, self::MD_POST_META_PRISM);
		delete_metadata( 'post', $post_id, self::MD_POST_META_HIGHLIGHT);
		delete_metadata( 'post', $post_id, self::MD_POST_META_SEQUENCE);
		delete_metadata( 'post', $post_id, self::MD_POST_META_FLOW);

		$is_sequence  = false;
		$is_flowchart = false;
		$is_mermaid   = false;
		$is_katex     = false;
		$is_mathjax   = false;

		if ( preg_match_all( '/<code class="language-([a-z\-0-9]+)"/', $post_content, $matches ) > 0 && ! empty( $matches[1] ) ) {

			foreach ( $matches[1] as $match ) {

				if ( ! empty( Module\Prism::$prism_codes[ $match ] ) ) {
					$prism_meta_array[ $match ] = $match;
				}

				// Check if this componets requires the parent components or not.
				if ( ! empty( Module\Prism::$prism_component_parent[ $match ] ) ) {
					foreach ( Module\Prism::$prism_component_parent[ $match ] as $parent ) {

						// If it need a parent componet, add it to the $paris_meta_array.
						if ( empty( $prism_meta_array[ $parent ] ) ) {
							$prism_meta_array[ $parent ] = $parent;
						}
					}
				}

				if ( ! empty( Module\Highlight::$highlight_codes[ $match ] ) ) {
					$highlight_meta_array[ $match ] = $match;
				}

				if ( 'seq' === $match || 'sequence' === $match ) {
					$is_sequence = true;
				}

				if ( 'flow' === $match || 'flowchart' === $match ) {
					$is_flowchart = true;
				}

				if ( 'mermaid' === $match ) {
					$is_mermaid = true;
				}

				if ( 'katex' === $match ) {
					$is_katex = true;
				}

				if ( 'mathjax' === $match ) {
					$is_mathjax = true;
				}
			}
		} 
		
		// If we find inline KaTex syntax.
		if ( strpos( $post_content, '<code class="katex-inline">' ) !== false ) {
			$is_katex = true;
		}

		// If we find inline MathJax syntax.
		if ( strpos( $post_content, '<code class="mathjax-inline language-mathjax">' ) !== false ) {
			$is_mathjax = true;
		}

		// Combine array into a string.
		$prism_meta_string     = implode( ',', $prism_meta_array );
		$highlight_meta_string = implode( ',', $highlight_meta_array );

		// Store the string to post meta, for identifying what the syntax languages are used in current post.
		if ( $this->is_support_prism && ! empty( $prism_meta_array ) ) {
			update_metadata( 'post', $post_id, self::MD_POST_META_PRISM, $prism_meta_string );
		} else {
			update_metadata( 'post', $post_id, self::MD_POST_META_PRISM, '' );
		}

		if ( $this->is_support_highlight && ! empty( $highlight_meta_array ) ) {
			update_metadata( 'post', $post_id, self::MD_POST_META_HIGHLIGHT, $highlight_meta_string );
		} else {
			update_metadata( 'post', $post_id, self::MD_POST_META_HIGHLIGHT, '' );
		}

		if ( $this->is_support_sequence && $is_sequence ) {
			update_metadata( 'post', $post_id, self::MD_POST_META_SEQUENCE, true );
		} else {
			update_metadata( 'post', $post_id, self::MD_POST_META_SEQUENCE, false );
		}

		if ( $this->is_support_flowchart && $is_flowchart ) {
			update_metadata( 'post', $post_id, self::MD_POST_META_FLOW, true );
		} else {
			update_metadata( 'post', $post_id, self::MD_POST_META_FLOW, false );
		}

		if ( $this->is_support_mermaid && $is_mermaid ) {
			update_metadata( 'post', $post_id, self::MD_POST_META_MERMAID, true );
		} else {
			update_metadata( 'post', $post_id, self::MD_POST_META_MERMAID, false );
		}

		if ( $this->is_support_katex && $is_katex ) {
			update_metadata( 'post', $post_id, self::MD_POST_META_KATEX, true );
		} else {
			update_metadata( 'post', $post_id, self::MD_POST_META_KATEX, false );
		}

		if ( $this->is_support_mathjax && $is_mathjax ) {
			update_metadata( 'post', $post_id, self::MD_POST_META_MATHJAX, true );
		} else {
			update_metadata( 'post', $post_id, self::MD_POST_META_MATHJAX, false );
		}
	}

	/**
	 * Register the `HtmlToMarkdown` meta box in the post-editor.
	 */
	public function add_meta_box() {

		if ( ! githuber_current_user_can( 'edit_posts' ) ) {
			return false;
		}

		add_meta_box(
			'markdown_this_post_meta_box',
			__( 'Enable Markdown', 'wp-githuber-md' ) . '<div class="bg-icon-md"></div>',
			array( $this, 'show_meta_box' ),
			null,
			'side',
			'high'
		);
	}

	/**
	 * Show `HtmlToMarkdown` meta box.
	 */
	public function show_meta_box() {

		$post_id               = githuber_get_current_post_id();
		$markdown_this_post    = get_metadata( 'post', $post_id, self::MD_POST_META_ENABLED, true );

		githuber_logger( 'Show meta box.', array(
			'post_id'            => $post_id,
			'markdown_this_post' => $markdown_this_post,
		) );

		$data['markdown_this_post_choice'] = $markdown_this_post;
		$data['is_markdown_this_post']     = $this->markdown_this_post;

		echo githuber_load_view( 'metabox/markdown-per-post', $data );
	}

	/**
	 * Do action hook for per post Markdown control.
	 */
	public function admin_githuber_markdown_this_post() {

		githuber_logger( 'Start an Ajax call.');

		$response = array(
			'success' => false,
			'result'  => '',
		);

		if ( ! empty( $_POST['post_id'] ) && ! empty( $_POST['markdown_this_post'] ) ) {
			$post_id = (int) $_POST['post_id'];
			$choice  = $_POST['markdown_this_post'];

			if ( 'yes' === $choice ) {
				update_metadata( 'post', $post_id, self::MD_POST_META_ENABLED, 'yes' );
			} else {
				update_metadata( 'post', $post_id, self::MD_POST_META_ENABLED, 'no' );
			}

			$response = array(
				'success' => true,
				'result'  => $choice,
				'post_id' => $post_id,
			);

			githuber_logger( 'Post data is gotten.', array(
				'post_id' => $_POST['post_id'],
				'markdown_this_post' => $_POST['markdown_this_post'],
			) );
		}

		header('Content-type: application/json');

		echo json_encode( $response );

		// To avoid wp_ajax return "0" string to break the vaild json string.
		wp_die();
	}

	/**
	 * The below methods are from Jetpack: Markdown modular
	 * And we modified it for our needs.
	 *
	 * @link https://github.com/Automattic/jetpack/blob/master/modules/markdown/easy-markdown.php
	 * @license GPL
	 */
	public function jetpack_code_snippets() {
		$this->maybe_load_actions_and_filters();

		if ( defined( 'REST_API_REQUEST' ) && REST_API_REQUEST ) {
			add_action( 'switch_blog', array( $this, 'maybe_load_actions_and_filters' ), 10, 2 );
		}
	}

	/**
	 * If we're in a bulk edit session, unload so that we don't lose our markdown metadata
	 */
	public function maybe_unload_for_bulk_edit() {
		if ( isset( $_REQUEST['bulk_edit'] ) && $this->is_md_enabled( 'posting' ) ) {
			$this->unload_markdown( 'posting' );
		}
	}

	/**
	 * Called on init and fires on switch_blog to decide if our actions and filters
	 * should be running.
	 * @param int|null $new_blog_id New blog ID
	 * @param int|null $old_blog_id Old blog ID
	 */
	public function maybe_load_actions_and_filters( $new_blog_id = null, $old_blog_id = null ) {

		// If this is a switch_to_blog call, and the blog isn't changing, we'll already be loaded
		if ( $new_blog_id && $new_blog_id === $old_blog_id ) {
			return;
		}
		if ( $this->is_md_enabled( 'posting' ) ) {
			$this->load_markdown( 'posting' );
		} else {
			$this->unload_markdown( 'posting' );
		}
		if ( $this->is_md_enabled( 'commenting' ) ) {
			$this->load_markdown( 'commenting' );
		} else {
			$this->unload_markdown( 'commenting' );
		}
	}

	/**
	 * Set up hooks for enabling Markdown conversion on specfic post action.
	 *
	 * @param $post_action_type posting|commenting
	 * @return void
	 */
	public function load_markdown( $post_action_type ) {
		switch ( $post_action_type ) {
			case 'posting':
				// Set up hooks for enabling Markdown conversion on posts
				add_action( 'wp_insert_post', array( $this, 'wp_insert_post' ) );
				add_filter( 'wp_insert_post_data', array( $this, 'wp_insert_post_data' ), 10, 2 );
				add_filter( 'edit_post_content', array( $this, 'edit_post_content' ), 10, 2 );
				add_filter( 'edit_post_content_filtered', array( $this, 'edit_post_content_filtered' ), 10, 2 );
				add_action( 'wp_restore_post_revision', array( $this, 'wp_restore_post_revision' ), 10, 2 );
				add_filter( '_wp_post_revision_fields', array( $this, '_wp_post_revision_fields' ) );
				add_action( 'xmlrpc_call', array( $this, 'xmlrpc_actions' ) );
				add_filter( 'content_save_pre', array( $this, 'preserve_code_blocks' ), 1 );

				if ( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST ) {
					$this->check_for_early_methods();
				}
				break;
			case 'commenting':
				// Use priority 9 so that Markdown runs before KSES, which can clean up any munged HTML.
				add_filter( 'pre_comment_content', array( $this, 'pre_comment_content' ), 9 );
				break;
			default:
		}
	}

	/**
	 * Removes hooks to disable Markdown conversion on specfic post action.
	 *
	 * @param $post_action_type posting|commenting
	 * @return void
	 */
	public function unload_markdown( $post_action_type ) {
		switch ( $post_action_type ) {
			case 'posting':
				remove_action( 'wp_insert_post', array( $this, 'wp_insert_post' ) );
				remove_filter( 'wp_insert_post_data', array( $this, 'wp_insert_post_data' ), 10, 2 );
				remove_filter( 'edit_post_content', array( $this, 'edit_post_content' ), 10, 2 );
				remove_filter( 'edit_post_content_filtered', array( $this, 'edit_post_content_filtered' ), 10, 2 );
				remove_action( 'wp_restore_post_revision', array( $this, 'wp_restore_post_revision' ), 10, 2 );
				remove_filter( '_wp_post_revision_fields', array( $this, '_wp_post_revision_fields' ) );
				remove_action( 'xmlrpc_call', array( $this, 'xmlrpc_actions' ) );
				remove_filter( 'content_save_pre', array( $this, 'preserve_code_blocks' ), 1 );
				break;
			case 'commenting':
				remove_filter( 'pre_comment_content', array( $this, 'pre_comment_content' ), 9 );
				break;
			default:
		}
	}

	/**
	 * Sanitize setting. Don't really want to store "on" value, so we'll store "1" instead!
	 * @param  string $input Value received by settings API via $_POST
	 * @return bool   Cast to boolean.
	 */
	public function sanitize_setting( $input ) {
		return (bool) $input;
	}

	/**
	 * Check if a $post_id has Markdown enabled
	 * @param  int  $post_id A post ID.
	 * @return bool
	 */
	public function has_markdown( $post_id ) {
		if ( get_metadata( 'post', $post_id, self::MD_POST_META, true ) ) {
			return true;
		}

		// Backward check Jetpack Markdown.
		if ( get_metadata( 'post', $post_id, self::JETPACK_MD_POST_META, true ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Set Markdown as enabled on a post_id. We skip over update_postmeta so we
	 * can sneakily set metadata on post revisions, which we need.
	 * @param int    $post_id A post ID.
	 * @return bool  The metadata was successfully set.
	 */
	protected function set_as_markdown( $post_id ) {
		return update_metadata( 'post', $post_id, self::MD_POST_META, true );
	}

	/**
	 * Swap post_content and post_content_filtered for editing
	 * @param  string $content Post content
	 * @param  int $id         post ID
	 * @return string          Swapped content
	 */
	public function edit_post_content( $content, $id ) {
		if ( $this->has_markdown( $id ) ) {
			$post = get_post( $id );
			if ( $post && ! empty( $post->post_content_filtered ) ) {
				$post = $this->swap_for_editing( $post );
				return $post->post_content;
			}
		}
		return $content;
	}

	/**
	 * Swap post_content_filtered and post_content for editing
	 * @param  string $content Post content_filtered
	 * @param  int    $id      post ID
	 * @return string          Swapped content
	 */
	public function edit_post_content_filtered( $content, $id ) {

		// if markdown was disabled, let's turn this off
		if ( ! $this->is_md_enabled( 'posting' ) && $this->has_markdown( $id ) ) {
			$post = get_post( $id );
			if ( $post && ! empty( $post->post_content_filtered ) ) {
				$content = '';
			}
		}
		return $content;
	}

	/**
	 * Magic happens here. Markdown is converted and stored on post_content. Original Markdown is stored
	 * in post_content_filtered so that we can continue editing as Markdown.
	 *
	 * @param  array $post_data The post data that will be inserted into the DB. Slashed.
	 * @param  array $postarr   All the stuff that was in $_POST.
	 * @return array            $post_data with post_content and post_content_filtered modified
	 */
	public function wp_insert_post_data( $post_data, $postarr ) {

		// $post_data array is slashed!
		$post_id = isset( $postarr['ID'] ) ? $postarr['ID'] : false;

		// bail early if markdown is disabled or this post type is unsupported.
		if ( ! $this->is_md_enabled( 'posting' ) || ! post_type_supports( $post_data['post_type'], self::MD_POST_TYPE ) ) {

			// it's disabled, but maybe this *was* a markdown post before.
			if ( $this->has_markdown( $post_id ) && ! empty( $post_data['post_content_filtered'] ) ) {
				$post_data['post_content_filtered'] = '';
			}

			// we have no context to determine supported post types in the `post_content_pre` hook,
			// which already ran to sanitize code blocks. Undo that.
			$post_data['post_content'] = $this->restore_code_blocks( $post_data['post_content'] );
			return $post_data;
		}

		// rejigger post_content and post_content_filtered
		// revisions are already in the right place, except when we're restoring, but that's taken care of elsewhere
		// also prevent quick edit feature from overriding already-saved markdown (issue https://github.com/Automattic/jetpack/issues/636)
		if ( 'revision' !== $post_data['post_type'] && ! isset( $_POST['_inline_edit'] ) ) {

			$post_data['post_content_filtered'] = $post_data['post_content'];
			$post_data['post_content'] = $this->transform( $post_data['post_content'], array( 'id' => $post_id ) );

			if ( $this->is_convert_remote_image() ) {
				foreach ( FetchRemoteImage::$image_list as $image_info ) {
					$post_data['post_content_filtered'] = str_replace( $image_info['before'], $image_info['after'], $post_data['post_content_filtered'] );
				}
			}

			/** This filter is already documented in core/wp-includes/default-filters.php */
			$post_data['post_content'] = apply_filters( 'content_save_pre', $post_data['post_content'] );

		} elseif ( 0 === strpos( $post_data['post_name'], $post_data['post_parent'] . '-autosave' ) ) {

			// autosaves for previews are weird
			$post_data['post_content_filtered'] = $post_data['post_content'];
			$post_data['post_content'] = $this->transform( $post_data['post_content'], array( 'id' => $post_data['post_parent'] ) );

			if ( $this->is_convert_remote_image() ) {
				foreach ( FetchRemoteImage::$image_list as $image_info ) {
					$post_data['post_content_filtered'] = str_replace( $image_info['before'], $image_info['after'], $post_data['post_content_filtered'] );
				}
			}

			/** This filter is already documented in core/wp-includes/default-filters.php */
			$post_data['post_content'] = apply_filters( 'content_save_pre', $post_data['post_content'] );
		}

		// set as markdown on the wp_insert_post hook later
		if ( $post_id ) {
			$this->monitoring['post'][ $post_id ] = true;
		} else {
			$this->monitoring['content'] = wp_unslash( $post_data['post_content'] );
		}

		if ( 'revision' === $postarr['post_type'] && $this->has_markdown( $postarr['post_parent'] ) ) {
			$this->monitoring['parent'][ $postarr['post_parent'] ] = true;
		}
	
		// Is it support Prism - syntax highlighter.
		$this->detect_code_languages( $post_id, wp_unslash( $post_data['post_content'] ) );

		$post_data['post_content'] = $this->fix_issue_209( $post_data['post_content'] );

		return $post_data;
	}

	/**
	 * Calls on wp_insert_post action, after wp_insert_post_data. This way we can
	 * still set postmeta on our revisions after it's all been deleted.
	 * @param  int $post_id The post ID that has just been added/updated
	 * @return null
	 */
	public function wp_insert_post( $post_id ) {
		$post_parent = get_post_field( 'post_parent', $post_id );
		// this didn't have an ID yet. Compare the content that was just saved.
		if ( isset( $this->monitoring['content'] ) && $this->monitoring['content'] === get_post_field( 'post_content', $post_id ) ) {
			unset( $this->monitoring['content'] );
			$this->set_as_markdown( $post_id );
		}
		if ( isset( $this->monitoring['post'][ $post_id ] ) ) {
			unset( $this->monitoring['post'][ $post_id ] );
			$this->set_as_markdown( $post_id );
		} elseif ( isset( $this->monitoring['parent'][ $post_parent ] ) ) {
			unset( $this->monitoring['parent'][ $post_parent ] );
			$this->set_as_markdown( $post_id );
		}
	}

	/**
	 * Run a comment through Markdown. Easy peasy.
	 *
	 * @param  string $content
	 * @return string
	 */
	public function pre_comment_content( $content ) {
		return $this->transform( $content, array(
			'id' => $this->comment_hash( $content ),
		) );
	}
	protected function comment_hash( $content ) {
		return 'c-' . substr( md5( $content ), 0, 8 );
	}

	/**
	 * Markdown conversion. Some DRYness for repetitive tasks.
	 *
	 * @param string $text Content to be run through Markdown
	 * @param array  $args Arguments, with keys:
	 *                     id: provide a string to prefix footnotes with a unique identifier
	 *                     unslash: when true, expects and returns slashed data
	 *                     decode_code_blocks: when true, assume that text in fenced code blocks is already
	 *                     HTML encoded and should be decoded before being passed to Markdown, which does
	 *                     its own encoding.
	 * @return string Markdown-processed content
	 */
	public function transform( $text, $args = array() ) {

		$is_decode_code_blocks = ( 'yes' === githuber_get_option( 'decode_code_blocks', 'githuber_preferences' ) ) ? true : false;

		$args = wp_parse_args( $args, array(
			'id'                 => false,
			'unslash'            => true,
			'decode_code_blocks' => $is_decode_code_blocks, // Fix: issue #30
			//'decode_code_blocks' => false, // Fix: issue #30
		) );

		// probably need to unslash
		if ( $args['unslash'] ) {
			$text = wp_unslash( $text );
		}

		// ensure our paragraphs are separated
		$text = str_replace( array( '</p><p>', "</p>\n<p>" ), "</p>\n\n<p>", $text );

		// visual editor likes to add <p>s. Buh-bye.
		$text = $this->get_parser()->remove_bare_p_tags( $text );

		// sometimes we get an encoded > at start of line, breaking blockquotes
		$text = preg_replace( '/^&gt;/m', '>', $text );

		// If we're not using the code shortcode, prevent over-encoding.
		if ( $args['decode_code_blocks'] ) {
			$text = $this->restore_code_blocks( $text );
		}

		// Transform it!
		$text = $this->get_parser()->transform( $text );

		// Fetch remote images.
		if ( $this->is_convert_remote_image() ) {
			$text = $this->convert_remote_image( $text );
		}

		// Render Github Flavored Markdown task lists if this module is enabled.
		if ( $this->is_support_task_list ) {
			$text = Module\TaskList::parse_gfm_task_list( $text );
		}

		// Render KaTeX inline markup.
		if ( $this->is_support_katex ) {
			$text = Module\KaTeX::katex_inline_markup( $text );
		}

		// Render MathJax inline markup.
		if ( $this->is_support_mathjax ) {
			$text = Module\MathJax::mathjax_inline_markup( $text );
		}

		// Markdown inserts extra spaces to make itself work. Buh-bye.
		$text = rtrim( $text );

		// probably need to re-slash
		if ( $args['unslash'] ) {
			$text = wp_slash( $text );
		}

		return $text;
	}

	/**
	 * Shows Markdown in the Revisions screen, and ensures that post_content_filtered
	 * is maintained on revisions
	 *
	 * @param  array $fields Post fields pertinent to revisions
	 * @return array         Modified array to include post_content_filtered
	 */
	public function _wp_post_revision_fields( $fields ) {
		$fields['post_content_filtered'] = __( 'Markdown content', 'jetpack' );
		return $fields;
	}

	/**
	 * Do some song and dance to keep all post_content and post_content_filtered content
	 * in the expected place when a post revision is restored.
	 *
	 * @param  int $post_id        The post ID have a restore done to it
	 * @param  int $revision_id    The revision ID being restored
	 */
	public function wp_restore_post_revision( $post_id, $revision_id ) {
		if ( $this->has_markdown( $revision_id ) ) {
			$revision = get_post( $revision_id, ARRAY_A );
			$post = get_post( $post_id, ARRAY_A );

			// Yes, we put it in post_content, because our wp_insert_post_data() expects that
			$post['post_content'] = $revision['post_content_filtered'];

			// set this flag so we can restore the post_content_filtered on the last revision later
			$this->monitoring['restore'] = true;

			// let's not make a revision of our fixing update
			add_filter( 'wp_revisions_to_keep', '__return_false', 99 );
			wp_update_post( $post );
			$this->fix_latest_revision_on_restore( $post_id );
			remove_filter( 'wp_revisions_to_keep', '__return_false', 99 );
		}
	}

	/**
	 * We need to ensure the last revision has Markdown, not HTML in its post_content_filtered
	 * column after a restore.
	 *
	 * @param int $post_id The post ID that was just restored.
	 */
	protected function fix_latest_revision_on_restore( $post_id ) {
		$post = get_post( $post_id );
		$last_revision = self::$model_instance->get_lastest_revision( $post->ID );
		$last_revision->post_content_filtered = $post->post_content_filtered;
		wp_insert_post( (array) $last_revision );
	}

	/**
	 * Kicks off magic for an XML-RPC session. We want to keep editing Markdown
	 * and publishing HTML.
	 *
	 * @param  string $xmlrpc_method The current XML-RPC method
	 * @return void
	 */
	public function xmlrpc_actions( $xmlrpc_method ) {
		switch ( $xmlrpc_method ) {
			case 'metaWeblog.getRecentPosts':
			case 'wp.getPosts':
			case 'wp.getPages':
				add_action( 'parse_query', array( $this, 'make_filterable' ), 10, 1 );
				break;
			case 'wp.getPost':
				$this->prime_post_cache();
				break;
		}
	}

	/**
	 * metaWeblog.getPost and wp.getPage fire xmlrpc_call action *after* get_post() is called.
	 * So, we have to detect those methods and prime the post cache early.
	 */
	protected function check_for_early_methods() {
		$raw_post_data = file_get_contents( "php://input" );
		if ( false === strpos( $raw_post_data, 'metaWeblog.getPost' )
			&& false === strpos( $raw_post_data, 'wp.getPage' ) ) {
			return;
		}
		include_once( ABSPATH . WPINC . '/class-IXR.php' );
		$message = new \IXR_Message( $raw_post_data );
		$message->parse();
		$post_id_position = 'metaWeblog.getPost' === $message->methodName ? 0 : 1;
		$this->prime_post_cache( $message->params[ $post_id_position ] );
	}

	/**
	 * Prime the post cache with swapped post_content. This is a sneaky way of getting around
	 * the fact that there are no good hooks to call on the *.getPost xmlrpc methods.
	 */
	private function prime_post_cache( $post_id = false ) {
		global $wp_xmlrpc_server;
		if ( ! $post_id ) {
			$post_id = $wp_xmlrpc_server->message->params[3];
		}
		// prime the post cache
		if ( $this->has_markdown( $post_id ) ) {
			$post = get_post( $post_id );
			if ( ! empty( $post->post_content_filtered ) ) {
				wp_cache_delete( $post->ID, 'posts' );
				$post = $this->swap_for_editing( $post );
				wp_cache_add( $post->ID, $post, 'posts' );
				$this->posts_to_uncache[] = $post_id;
			}
		}
		// uncache munged posts if using a persistent object cache
		if ( wp_using_ext_object_cache() ) {
			add_action( 'shutdown', array( $this, 'uncache_munged_posts' ) );
		}
	}

	/**
	 * Swaps `post_content_filtered` back to `post_content` for editing purposes.
	 *
	 * @param  object $post WP_Post object
	 * @return object       WP_Post object with swapped `post_content_filtered` and `post_content`
	 */
	protected function swap_for_editing( $post ) {
		$markdown = $post->post_content_filtered;

		// unencode encoded code blocks
		$markdown = $this->restore_code_blocks( $markdown );

		// restore beginning of line blockquotes
		$markdown = preg_replace( '/^&gt; /m', '> ', $markdown );
		$post->post_content_filtered = $post->post_content;
		$post->post_content = $markdown;
		return $post;
	}

	/**
	 * We munge the post cache to serve proper markdown content to XML-RPC clients.
	 * Uncache these after the XML-RPC session ends.
	 */
	public function uncache_munged_posts() {
		// $this context gets lost in testing sometimes. Weird.
		foreach ( $this->posts_to_uncache as $post_id ) {
			wp_cache_delete( $post_id, 'posts' );
		}
	}

	/**
	 * Since *.(get)?[Rr]ecentPosts calls get_posts with suppress filters on, we need to
	 * turn them back on so that we can swap things for editing.
	 *
	 * @param  object $wp_query WP_Query object
	 */
	public function make_filterable( $wp_query ) {
		$wp_query->set( 'suppress_filters', false );
		add_action( 'the_posts', array( $this, 'the_posts' ), 10, 2 );
	}

	/**
	 * Swaps post_content and post_content_filtered for editing.
	 *
	 * @param  array  $posts    Posts returned by the just-completed query
	 * @param  object $wp_query Current WP_Query object
	 * @return array            Modified $posts
	 */
	public function the_posts( $posts, $wp_query ) {
		foreach ( $posts as $key => $post ) {
			if ( $this->has_markdown( $post->ID ) && ! empty( $posts[ $key ]->post_content_filtered ) ) {
				$markdown = $posts[ $key ]->post_content_filtered;
				$posts[ $key ]->post_content_filtered = $posts[ $key ]->post_content;
				$posts[ $key ]->post_content = $markdown;
			}
		}
		return $posts;
	}

	/**
	 * Preserve code blocks from being munged by KSES before they have a chance
	 *
	 * @param  string $text post content
	 * @return string       post content with code blocks escaped
	 */
	public function preserve_code_blocks( $text ) {
		return $this->get_parser()->codeblock_preserve( $text );
	}

	/**
	 * Restore code blocks.
	 *
	 * @param  string $text post content
	 * @return string       post content with code blocks unescaped
	 */
	public function restore_code_blocks( $text ) {
		$text = $this->get_parser()->codeblock_restore( $text );
		return $this->fix_issue_209( $text );
	}

	/**
	 * https://github.com/terrylinooo/githuber-md/issues/209
	 *
	 * @param  string $text post content
	 * @return string       post content with code blocks unescaped
	 */
	public function fix_issue_209( $text ) {
		// Use a unique string `_!_!_` to replace `&#`, then covert it to `&amp;#`
		$text = str_replace( '_!_!_', '&amp;#', $text );
		return $text;
	}

	/**
	 * Force-disable Jetpack's Markdown module if it is active.
	 *
	 * @param array $modules Array of active Jetpack modules.
	 *
	 * @return array $modules Array of active Jetpack modules.
	 */
	public function admin_githuber_disable_jetpack_markdown( $modules ) {
		$found = array_search( 'markdown', $modules, true );
		if ( false !== $found ) {
			unset( $modules[ $found ] );
		}
		return $modules;
	}

	/**
	 * Detect remote images.
	 *
	 * @param string $post_content 
	 * 
	 * @return string
	 */
	public function convert_remote_image( $post_content  ) {

		if ( $this->is_convert_remote_image() ) {
			$post_content  = FetchRemoteImage::covert( $post_content  );
		}
		return $post_content ;
	}

	/**
	 * Is performing covert remote image.
	 *
	 * @return bool
	 */
	public function is_convert_remote_image() {
		if ( 'yes' === githuber_get_option( 'fetch_remote_image', 'githuber_markdown' ) ) {
			if ( isset( $_POST['fetch_remote_image'] ) && 'yes' === $_POST['fetch_remote_image'] ) {
				return true;
			}	
		}
		return false;
	}
}
