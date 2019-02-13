<?php
/**
 * Class Markdown
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Githuber
 * @since 1.0.0
 * @version 1.5.2
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
	public $editormd_varsion = '1.5.0.5';

	/**
	 * The Post Type support from Markdown controller.
	 *
	 * @var string
	 */
	public $post_type;

	/**
	 * Constants.
	 */
	const MD_POST_TYPE          = 'githuber_markdown';
	const MD_POST_META          = '_is_githuber_markdown';
	const MD_POST_META_PRISM    = '_githuber_prismjs';
	const MD_POST_META_SEQUENCE = '_is_githuber_sequence';
	const MD_POST_META_FLOW     = '_is_githuber_flow_chart';
	const MD_POST_META_KATEX    = '_is_githuber_katex';
	const MD_POST_META_MERMAID  = '_is_githuber_mermaid';

	const JETPACK_MD_POST_TYPE  = 'wpcom-markdown';
	const JETPACK_MD_POST_META  = '_wpcom_is_markdown';

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
	public $is_support_task_list = false;
	public $is_support_katex     = false;
	public $is_support_flowchart = false;
	public $is_support_sequence  = false;
	public $is_support_mermaid   = false;

	/**
	 * Constructer.
	 */
	public function __construct() {
		parent::__construct();

		if ( ! self::$model_instance ) {
			self::$model_instance = new Model\Markdown();
		}

		if ( 'yes' === githuber_get_option( 'support_prism', 'githuber_markdown' ) ) {
			$this->is_support_prism = true;
		}

		if ( 'yes' === githuber_get_option( 'support_task_list', 'githuber_markdown' ) ) {
			$this->is_support_task_list = true;
		}

		if ( 'yes' === githuber_get_option( 'support_katex', 'githuber_markdown' ) ) {
			$this->is_support_katex = true;
		}

		if ( 'yes' === githuber_get_option( 'support_flowchart', 'githuber_markdown' ) ) {
			$this->is_support_flowchart = true;
		}

		if ( 'yes' === githuber_get_option( 'support_sequence_diagram', 'githuber_markdown' ) ) {
			$this->is_support_sequence = true;
		}

		if ( 'yes' === githuber_get_option( 'support_mermaid', 'githuber_markdown' ) ) {
			$this->is_support_mermaid = true;
		}
	}

	/**
	 * Initialize.
	 */
	public function init() {

		$support_post_types = array(
			'post',
			'page',
			'revision',
			'repository'
		);
		
		$support_post_types = apply_filters( 'githuber_md_suppot_post_types', $support_post_types );

		foreach ( $support_post_types as $post_type ) {
			add_post_type_support( $post_type, self::MD_POST_TYPE );
		}

		add_action( 'admin_init', array( $this, 'admin_init' ) );

		$this->jetpack_code_snippets();
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

		// Jetpack Markdown should not be turned on with Githuber MD at the same time.
		// We should notice users to turn it off.
		if ( post_type_supports( get_current_screen()->post_type, self::JETPACK_MD_POST_TYPE ) ) {
			add_action( 'admin_notices', array( $this, 'jetpack_warning' ) );
		}

		wp_enqueue_script( 'editormd', $this->githuber_plugin_url . 'assets/vendor/editor.md/editormd.min.js', array( 'jquery' ), $this->editormd_varsion, true );
		wp_enqueue_script( 'githuber-md', $this->githuber_plugin_url . 'assets/js/githuber-md.js', array( 'editormd' ), $this->version, true );

		switch ( get_bloginfo( 'language' ) ) {
			case 'zh-TW':
			case 'zh-CN':
				wp_enqueue_script( 'editor-md-lang', $this->githuber_plugin_url . 'assets/vendor/editor.md/languages/zh-tw.js', array(), $this->editormd_varsion, true );
				break;

			case 'en-US':
			default:
				wp_enqueue_script( 'editor-md-lang', $this->githuber_plugin_url . 'assets/vendor/editor.md/languages/en.js', array(), $this->editormd_varsion, true );
		}

		$editormd_config_list = array(
			'editor_sync_scrolling',
			'editor_live_preview',
			'editor_image_paste',
			'editor_html_decode',
			'editor_toolbar_theme',
			'editor_editor_theme',
			'editor_line_number',
			'support_toc',
			'support_emoji',
			'support_katex',
			'support_flowchart',
			'support_sequence_diagram',
			'support_task_list',
			'support_mermaid',
		);

		$editormd_localize = array();

		foreach ($editormd_config_list as $setting_name) {
			$editormd_localize[ $setting_name ] = githuber_get_option( $setting_name, 'githuber_markdown' );
		}

		$editormd_localize['editor_modules_url']   = $this->githuber_plugin_url . 'assets/vendor/editor.md/lib/';
		$editormd_localize['editor_placeholder']   = __( 'Happy Markdowning!', 'wp-githuber-md' );
		$editormd_localize['image_paste_callback'] = admin_url( 'admin-ajax.php?action=githuber_image_paste');
		$editormd_localize['prism_line_number']    = githuber_get_option( 'prism_line_number', 'githuber_modules' );
		

		// Register JS variables for the Editormd library uses.
		wp_localize_script( 'githuber-md', 'editormd_config', $editormd_localize );
	}

	/**
	 * Initalize to WP `admin_init` hook.
	 */
	public function admin_init() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}

	/**
	 * Display a warning, when Jetpack Markdown is on.
	 */
	public function jetpack_warning() {
		echo githuber_load_view( 'message/jetpack-warning' );
	}
	
	/**
	 * Markdown parser.
	 *
	 * @return object MarkdownParser instance.
	 */
	public static function get_parser()
	{
		if ( ! self::$parser_instance ) {
			self::$parser_instance = new Module\MarkdownParser();
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
			case 'commeting':
				$setting = githuber_get_option( 'enable_markdown_for', 'githuber_markdown' );
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
	 * @param int   $post_id       The post ID.
	 * @param string $post_content The post content.
	 * @return void
	 */
	public function detect_code_languages( $post_id, $post_content ) {

		// This is what Prism.js uses.
		$prism_codes = array(
			'html'              => 'HTML',
			'xml'               => 'XML',
			'svg'               => 'SVG',
			'mathml'            => 'MathML',
			'css'               => 'CSS',
			'clike'             => 'C-like',
			'javascript'        => 'JavaScript',
			'abap'              => 'ABAP',
			'actionscript'      => 'ActionScript',
			'ada'               => 'Ada',
			'apacheconf'        => 'Apache Configuration',
			'apl'               => 'APL',
			'applescript'       => 'AppleScript',
			'arduino'           => 'Arduino',
			'arff'              => 'ARFF',
			'asciidoc'          => 'AsciiDoc',
			'asm6502'           => '6502 Assembly',
			'aspnet'            => 'ASP.NET (C#)',
			'autohotkey'        => 'AutoHotkey',
			'autoit'            => 'AutoIt',
			'bash'              => 'Bash',
			'basic'             => 'BASIC',
			'batch'             => 'Batch',
			'bison'             => 'Bison',
			'brainfuck'         => 'Brainfuck',
			'bro'               => 'Bro',
			'c'                 => 'C',
			'csharp'            => 'C#',
			'cpp'               => 'C++',
			'coffeescript'      => 'CoffeeScript',
			'clojure'           => 'Clojure',
			'crystal'           => 'Crystal',
			'csp'               => 'Content-Security-Policy',
			'css-extras'        => 'CSS Extras',
			'd'                 => 'D',
			'dart'              => 'Dart',
			'diff'              => 'Diff',
			'django'            => 'Django/Jinja2',
			'docker'            => 'Docker',
			'eiffel'            => 'Eiffel',
			'elixir'            => 'Elixir',
			'elm'               => 'Elm',
			'erb'               => 'ERB',
			'erlang'            => 'Erlang',
			'fsharp'            => 'F#',
			'flow'              => 'Flow',
			'fortran'           => 'Fortran',
			'gedcom'            => 'GEDCOM',
			'gherkin'           => 'Gherkin',
			'git'               => 'Git',
			'glsl'              => 'GLSL',
			'go'                => 'Go',
			'graphql'           => 'GraphQL',
			'groovy'            => 'Groovy',
			'haml'              => 'Haml',
			'handlebars'        => 'Handlebars',
			'haskell'           => 'Haskell',
			'haxe'              => 'Haxe',
			'http'              => 'HTTP',
			'hpkp'              => 'HTTP Public-Key-Pins',
			'hsts'              => 'HTTP Strict-Transport-Security',
			'ichigojam'         => 'IchigoJam',
			'icon'              => 'Icon',
			'inform7'           => 'Inform 7',
			'ini'               => 'Ini',
			'io'                => 'Io',
			'j'                 => 'J',
			'java'              => 'Java',
			'jolie'             => 'Jolie',
			'json'              => 'JSON',
			'julia'             => 'Julia',
			'keyman'            => 'Keyman',
			'kotlin'            => 'Kotlin',
			'latex'             => 'LaTeX',
			'less'              => 'Less',
			'liquid'            => 'Liquid',
			'lisp'              => 'Lisp',
			'livescript'        => 'LiveScript',
			'lolcode'           => 'LOLCODE',
			'lua'               => 'Lua',
			'makefile'          => 'Makefile',
			'markdown'          => 'Markdown',
			'markup-templating' => 'Markup templating',
			'matlab'            => 'MATLAB',
			'mel'               => 'MEL',
			'mizar'             => 'Mizar',
			'monkey'            => 'Monkey',
			'n4js'              => 'N4JS',
			'nasm'              => 'NASM',
			'nginx'             => 'nginx',
			'nim'               => 'Nim',
			'nix'               => 'Nix',
			'nsis'              => 'NSIS',
			'objectivec'        => 'Objective-C',
			'ocaml'             => 'OCaml',
			'opencl'            => 'OpenCL',
			'oz'                => 'Oz',
			'parigp'            => 'PARI/GP',
			'parser'            => 'Parser',
			'pascal'            => 'Pascal',
			'perl'              => 'Perl',
			'php'               => 'PHP',
			'php-extras'        => 'PHP Extras',
			'plsql'             => 'PL/SQL',
			'powershell'        => 'PowerShell',
			'processing'        => 'Processing',
			'prolog'            => 'Prolog',
			'properties'        => '.properties',
			'protobuf'          => 'Protocol Buffers',
			'pug'               => 'Pug',
			'puppet'            => 'Puppet',
			'pure'              => 'Pure',
			'python'            => 'Python',
			'q'                 => 'Q (kdb+ database)',
			'qore'              => 'Qore',
			'r'                 => 'R',
			'jsx'               => 'React JSX',
			'tsx'               => 'React TSX',
			'renpy'             => 'Ren\'py',
			'reason'            => 'Reason',
			'rest'              => 'reST (reStructuredText)',
			'rip'               => 'Rip',
			'roboconf'          => 'Roboconf',
			'ruby'              => 'Ruby',
			'rust'              => 'Rust',
			'sas'               => 'SAS',
			'sass'              => 'Sass (Sass)',
			'scss'              => 'Sass (Scss)',
			'scala'             => 'Scala',
			'scheme'            => 'Scheme',
			'smalltalk'         => 'Smalltalk',
			'smarty'            => 'Smarty',
			'sql'               => 'SQL',
			'soy'               => 'Soy (Closure Template)',
			'stylus'            => 'Stylus',
			'swift'             => 'Swift',
			'tcl'               => 'Tcl',
			'textile'           => 'Textile',
			'twig'              => 'Twig',
			'typescript'        => 'TypeScript',
			'vbnet'             => 'VB.Net',
			'velocity'          => 'Velocity',
			'verilog'           => 'Verilog',
			'vhdl'              => 'VHDL',
			'vim'               => 'vim',
			'visual-basic'      => 'Visual Basic',
			'wasm'              => 'WebAssembly',
			'wiki'              => 'Wiki markup',
			'xeora'             => 'Xeora',
			'xojo'              => 'Xojo (REALbasic)',
			'yaml'              => 'YAML',
		);

		// The below codes need a parent componet being loaded before.
		$prism_component_parent = array(
			'javascript'        => array( 'clike' ),
			'actionscript'      => array( 'javascript' ),
			'arduino'           => array( 'cpp' ),
			'aspnet'            => array( 'markup' ),
			'bison'             => array( 'c' ),
			'c'                 => array( 'clike' ),
			'csharp'            => array( 'clike' ),
			'cpp'               => array( 'c' ),
			'coffeescript'      => array( 'javascript' ),
			'crystal'           => array( 'ruby' ),
			'css-extras'        => array( 'css' ),
			'd'                 => array( 'clike' ),
			'dart'              => array( 'clike' ),
			'django'            => array( 'markup' ),
			'erb'               => array( 'ruby', 'markup-templating' ),
			'fsharp'            => array( 'clike' ),
			'flow'              => array( 'javascript' ),
			'glsl'              => array( 'clike' ),
			'go'                => array( 'clike' ),
			'groovy'            => array( 'clike' ),
			'haml'              => array( 'ruby' ),
			'handlebars'        => array( 'markup-templating' ),
			'haxe'              => array( 'clike' ),
			'java'              => array( 'clike' ),
			'jolie'             => array( 'clike' ),
			'kotlin'            => array( 'clike' ),
			'less'              => array( 'css' ),
			'markdown'          => array( 'markup' ),
			'markup-templating' => array( 'markup' ),
			'n4js'              => array( 'javascript' ),
			'nginx'             => array( 'clike' ),
			'objectivec'        => array( 'c' ),
			'opencl'            => array( 'cpp' ),
			'parser'            => array( 'markup' ),
			'php'               => array( 'clike', 'markup-templating' ),
			'php-extras'        => array( 'php' ),
			'plsql'             => array( 'sql' ),
			'processing'        => array( 'clike' ),
			'protobuf'          => array( 'clike' ),
			'pug'               => array( 'javascript' ),
			'qore'              => array( 'clike' ),
			'jsx'               => array( 'markup', 'javascript' ),
			'tsx'               => array( 'jsx', 'typescript'),
			'reason'            => array( 'clike' ),
			'ruby'              => array( 'clike' ),
			'sass'              => array( 'css' ),
			'scss'              => array( 'css' ),
			'scala'             => array( 'java' ),
			'smarty'            => array( 'markup-templating' ),
			'soy'               => array( 'markup-templating' ),
			'swift'             => array( 'clike' ),
			'textile'           => array( 'markup' ),
			'twig'              => array( 'markup' ),
			'typescript'        => array( 'javascript' ),
			'vbnet'             => array( 'basic' ),
			'velocity'          => array( 'markup' ),
			'wiki'              => array( 'markup' ),
			'xeora'             => array( 'markup' )
		);

		$prism_meta_array = array();

		delete_metadata( 'post', $post_id, self::MD_POST_META_PRISM);
		delete_metadata( 'post', $post_id, self::MD_POST_META_SEQUENCE);
		delete_metadata( 'post', $post_id, self::MD_POST_META_FLOW);

		$is_sequence  = false;
		$is_flowchart = false;
		$is_mermaid   = false;
		$is_katex     = false;

		if ( preg_match_all( '/<code class="language-([a-z\-0-9]+)"/', $post_content, $matches ) > 0 && ! empty( $matches[1] ) ) {
			
			foreach ( $matches[1] as $match ) {
				if ( ! empty( $prism_codes[ $match ] ) ) {
					$prism_meta_array[ $match ] = $match;
				}

				// Check if this componets requires the parent components or not.
				if ( ! empty( $prism_component_parent[ $match ] ) ) {
					foreach ( $prism_component_parent[ $match ] as $parent ) {
						
						// If it need a parent componet, add it to the $paris_meta_array.
						if ( empty( $prism_meta_array[ $parent ] ) ) {
							$prism_meta_array[ $parent ] = $parent;
						}
					}
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
			}
		}

		// Combine array into a string.
		$prism_meta_string = implode( ',', $prism_meta_array );

		// Store the string to post meta, for identifying what the syntax languages are used in current post.
		if ( $this->is_support_prism && ! empty( $prism_meta_array ) ) {
			update_metadata( 'post', $post_id, self::MD_POST_META_PRISM, $prism_meta_string );
		}

		if ( $this->is_support_sequence && $is_sequence ) {
			update_metadata( 'post', $post_id, self::MD_POST_META_SEQUENCE, true );
		}

		if ( $this->is_support_flowchart && $is_flowchart ) {
			update_metadata( 'post', $post_id, self::MD_POST_META_FLOW, true );
		}

		if ( $this->is_support_mermaid && $is_mermaid ) {
			update_metadata( 'post', $post_id, self::MD_POST_META_MERMAID, true );
		}

		if ( $this->is_support_katex && $is_katex ) {
			update_metadata( 'post', $post_id, self::MD_POST_META_KATEX, true );
		}
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
			$this->unload_markdown_for_posts();
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

			return $post_data;
		}

		// rejigger post_content and post_content_filtered
		// revisions are already in the right place, except when we're restoring, but that's taken care of elsewhere
		// also prevent quick edit feature from overriding already-saved markdown (issue https://github.com/Automattic/jetpack/issues/636)
		if ( 'revision' !== $post_data['post_type'] && ! isset( $_POST['_inline_edit'] ) ) {

			$post_data['post_content_filtered'] = $post_data['post_content'];
			$post_data['post_content'] = $this->transform( $post_data['post_content'], array( 'id' => $post_id ) );

			/** This filter is already documented in core/wp-includes/default-filters.php */
			$post_data['post_content'] = apply_filters( 'content_save_pre', $post_data['post_content'] );

		} elseif ( 0 === strpos( $post_data['post_name'], $post_data['post_parent'] . '-autosave' ) ) {

			// autosaves for previews are weird
			$post_data['post_content_filtered'] = $post_data['post_content'];
			$post_data['post_content'] = $this->transform( $post_data['post_content'], array( 'id' => $post_data['post_parent'] ) );

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
	 *                       decode_code_blocks: when true, assume that text in fenced code blocks is already
	 *                       HTML encoded and should be decoded before being passed to Markdown, which does
	 *                       its own encoding.
	 * @return string Markdown-processed content
	 */
	public function transform( $text, $args = array() ) {

		$args = wp_parse_args( $args, array(
			'id' => false,
			'unslash' => true
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

		// Transform it!
		$text = $this->get_parser()->transform( $text );

		// Render Github Flavored Markdown task lists if this module is enabled.
		if ( $this->is_support_task_list ) {
			$text = Module\TaskList::parse_gfm_task_list( $text );
		}

		// Render KaTeX inline markup.
		if ( $this->is_support_katex ) {
			$text = Module\KaTeX::katex_inline_markup( $text );
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
}
