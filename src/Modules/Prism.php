<?php
/**
 * Module Name: Prism
 * Module Description: A syntax highlighter by Prism.js
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Githuber
 * @since 1.0.0
 * @version 1.4.0
 * 
 */

namespace Githuber\Module;

class Prism extends ModuleAbstract {

	/**
	 * The version of Prism we are using.
	 *
	 * @var string
	 */
	public $prism_version = '1.23.0';

	/**
	 * The priority order to load CSS file, the value should be higher than theme's.
	 * Overwrite the theme's style to make sure that it's safe to display the correct syntax highlight.
	 *
	 * @var integer
	 */
	public $css_priority = 999;

	// This is what Prism.js uses. generated using sh/generate-prism-codes
	public static $prism_codes = array(
	    'abap' => 'ABAP',
	    'abnf' => 'ABNF',
	    'actionscript' => 'ActionScript',
	    'ada' => 'Ada',
	    'agda' => 'Agda',
	    'al' => 'AL',
	    'antlr4' => 'ANTLR4',
	    'apacheconf' => 'Apache Configuration',
	    'apex' => 'Apex',
	    'apl' => 'APL',
	    'applescript' => 'AppleScript',
	    'aql' => 'AQL',
	    'arduino' => 'Arduino',
	    'arff' => 'ARFF',
	    'asciidoc' => 'AsciiDoc',
	    'asm6502' => '6502 Assembly',
	    'aspnet' => 'ASP.NET (C#)',
	    'autohotkey' => 'AutoHotkey',
	    'autoit' => 'AutoIt',
	    'bash' => 'Bash',
	    'basic' => 'BASIC',
	    'batch' => 'Batch',
	    'bbcode' => 'BBcode',
	    'birb' => 'Birb',
	    'bison' => 'Bison',
	    'bnf' => 'BNF',
	    'brainfuck' => 'Brainfuck',
	    'brightscript' => 'BrightScript',
	    'bro' => 'Bro',
	    'bsl' => 'BSL (1C:Enterprise)',
	    'c' => 'C',
	    'cil' => 'CIL',
	    'clike' => 'C-like',
	    'clojure' => 'Clojure',
	    'cmake' => 'CMake',
	    'coffeescript' => 'CoffeeScript',
	    'concurnas' => 'Concurnas',
	    'cpp' => 'C++',
	    'crystal' => 'Crystal',
	    'csharp' => 'C#',
	    'csp' => 'Content-Security-Policy',
	    'css' => 'CSS',
	    'css-extras' => 'CSS Extras',
	    'cypher' => 'Cypher',
	    'd' => 'D',
	    'dart' => 'Dart',
	    'dataweave' => 'DataWeave',
	    'dax' => 'DAX',
	    'dhall' => 'Dhall',
	    'diff' => 'Diff',
	    'django' => 'Django/Jinja2',
	    'dns-zone-file' => 'DNS zone file',
	    'docker' => 'Docker',
	    'ebnf' => 'EBNF',
	    'editorconfig' => 'EditorConfig',
	    'eiffel' => 'Eiffel',
	    'ejs' => 'EJS',
	    'elixir' => 'Elixir',
	    'elm' => 'Elm',
	    'erb' => 'ERB',
	    'erlang' => 'Erlang',
	    'etlua' => 'Embedded Lua templating',
	    'excel-formula' => 'Excel Formula',
	    'factor' => 'Factor',
	    'firestore-security-rules' => 'Firestore security rules',
	    'flow' => 'Flow',
	    'fortran' => 'Fortran',
	    'fsharp' => 'F#',
	    'ftl' => 'FreeMarker Template Language',
	    'gcode' => 'G-code',
	    'gdscript' => 'GDScript',
	    'gedcom' => 'GEDCOM',
	    'gherkin' => 'Gherkin',
	    'git' => 'Git',
	    'glsl' => 'GLSL',
	    'gml' => 'GameMaker Language',
	    'go' => 'Go',
	    'graphql' => 'GraphQL',
	    'groovy' => 'Groovy',
	    'haml' => 'Haml',
	    'handlebars' => 'Handlebars',
	    'haskell' => 'Haskell',
	    'haxe' => 'Haxe',
	    'hcl' => 'HCL',
	    'hlsl' => 'HLSL',
	    'hpkp' => 'HTTP Public-Key-Pins',
	    'hsts' => 'HTTP Strict-Transport-Security',
	    'http' => 'HTTP',
	    'ichigojam' => 'IchigoJam',
	    'icon' => 'Icon',
	    'iecst' => 'Structured Text (IEC 61131-3)',
	    'ignore' => '.ignore',
	    'inform7' => 'Inform 7',
	    'ini' => 'Ini',
	    'io' => 'Io',
	    'j' => 'J',
	    'java' => 'Java',
	    'javadoc' => 'JavaDoc',
	    'javadoclike' => 'JavaDoc-like',
	    'javascript' => 'JavaScript',
	    'javastacktrace' => 'Java stack trace',
	    'jolie' => 'Jolie',
	    'jq' => 'JQ',
	    'js-extras' => 'JS Extras',
	    'js-templates' => 'JS Templates',
	    'jsdoc' => 'JSDoc',
	    'json' => 'JSON',
	    'json5' => 'JSON5',
	    'jsonp' => 'JSONP',
	    'jsstacktrace' => 'JS stack trace',
	    'jsx' => 'React JSX',
	    'julia' => 'Julia',
	    'keyman' => 'Keyman',
	    'kotlin' => 'Kotlin',
	    'latex' => 'LaTeX',
	    'latte' => 'Latte',
	    'less' => 'Less',
	    'lilypond' => 'LilyPond',
	    'liquid' => 'Liquid',
	    'lisp' => 'Lisp',
	    'livescript' => 'LiveScript',
	    'llvm' => 'LLVM IR',
	    'lolcode' => 'LOLCODE',
	    'lua' => 'Lua',
	    'makefile' => 'Makefile',
	    'markdown' => 'Markdown',
	    'markup' => 'Markup',
	    'markup-templating' => 'Markup templating',
	    'matlab' => 'MATLAB',
	    'mel' => 'MEL',
	    'mizar' => 'Mizar',
	    'mongodb' => 'MongoDB',
	    'monkey' => 'Monkey',
	    'moonscript' => 'MoonScript',
	    'n1ql' => 'N1QL',
	    'n4js' => 'N4JS',
	    'nand2tetris-hdl' => 'Nand To Tetris HDL',
	    'naniscript' => 'Naninovel Script',
	    'nasm' => 'NASM',
	    'neon' => 'NEON',
	    'nginx' => 'nginx',
	    'nim' => 'Nim',
	    'nix' => 'Nix',
	    'nsis' => 'NSIS',
	    'objectivec' => 'Objective-C',
	    'ocaml' => 'OCaml',
	    'opencl' => 'OpenCL',
	    'oz' => 'Oz',
	    'parigp' => 'PARI/GP',
	    'parser' => 'Parser',
	    'pascal' => 'Pascal',
	    'pascaligo' => 'Pascaligo',
	    'pcaxis' => 'PC-Axis',
	    'peoplecode' => 'PeopleCode',
	    'perl' => 'Perl',
	    'php' => 'PHP',
	    'php-extras' => 'PHP Extras',
	    'phpdoc' => 'PHPDoc',
	    'plsql' => 'PL/SQL',
	    'powerquery' => 'PowerQuery',
	    'powershell' => 'PowerShell',
	    'processing' => 'Processing',
	    'prolog' => 'Prolog',
	    'promql' => 'PromQL',
	    'properties' => '.properties',
	    'protobuf' => 'Protocol Buffers',
	    'pug' => 'Pug',
	    'puppet' => 'Puppet',
	    'pure' => 'Pure',
	    'purebasic' => 'PureBasic',
	    'purescript' => 'PureScript',
	    'python' => 'Python',
	    'q' => 'Q (kdb+ database)',
	    'qml' => 'QML',
	    'qore' => 'Qore',
	    'r' => 'R',
	    'racket' => 'Racket',
	    'reason' => 'Reason',
	    'regex' => 'Regex',
	    'renpy' => 'Ren\'py',
	    'rest' => 'reST (reStructuredText)',
	    'rip' => 'Rip',
	    'roboconf' => 'Roboconf',
	    'robotframework' => 'Robot Framework',
	    'ruby' => 'Ruby',
	    'rust' => 'Rust',
	    'sas' => 'SAS',
	    'sass' => 'Sass (Sass)',
	    'scala' => 'Scala',
	    'scheme' => 'Scheme',
	    'scss' => 'Sass (Scss)',
	    'shell-session' => 'Shell session',
	    'smali' => 'Smali',
	    'smalltalk' => 'Smalltalk',
	    'smarty' => 'Smarty',
	    'sml' => 'SML',
	    'solidity' => 'Solidity (Ethereum)',
	    'solution-file' => 'Solution file',
	    'soy' => 'Soy (Closure Template)',
	    'sparql' => 'SPARQL',
	    'splunk-spl' => 'Splunk SPL',
	    'sqf' => 'SQF: Status Quo Function (Arma 3)',
	    'sql' => 'SQL',
	    'stan' => 'Stan',
	    'stylus' => 'Stylus',
	    'swift' => 'Swift',
	    't4-cs' => 'T4 Text Templates (C#)',
	    't4-templating' => 'T4 templating',
	    't4-vb' => 'T4 Text Templates (VB)',
	    'tap' => 'TAP',
	    'tcl' => 'Tcl',
	    'textile' => 'Textile',
	    'toml' => 'TOML',
	    'tsx' => 'React TSX',
	    'tt2' => 'Template Toolkit 2',
	    'turtle' => 'Turtle',
	    'twig' => 'Twig',
	    'typescript' => 'TypeScript',
	    'typoscript' => 'TypoScript',
	    'unrealscript' => 'UnrealScript',
	    'vala' => 'Vala',
	    'vbnet' => 'VB.Net',
	    'velocity' => 'Velocity',
	    'verilog' => 'Verilog',
	    'vhdl' => 'VHDL',
	    'vim' => 'vim',
	    'visual-basic' => 'Visual Basic',
	    'warpscript' => 'WarpScript',
	    'wasm' => 'WebAssembly',
	    'wiki' => 'Wiki markup',
	    'xeora' => 'Xeora',
	    'xml-doc' => 'XML doc (.net)',
	    'xojo' => 'Xojo (REALbasic)',
	    'xquery' => 'XQuery',
	    'yaml' => 'YAML',
	    'yang' => 'YANG',
	    'zig' => 'Zig',
	);
	
	// The below codes need a parent component being loaded before. generated using sh/generate-prism-codes
	public static $prism_component_parent = array(
	    'actionscript' => array('javascript'),
	    'apex' => array('clike', 'sql'),
	    'arduino' => array('cpp'),
	    'aspnet' => array('markup', 'csharp'),
	    'birb' => array('clike'),
	    'bison' => array('c'),
	    'c' => array('clike'),
	    'coffeescript' => array('javascript'),
	    'cpp' => array('c'),
	    'crystal' => array('ruby'),
	    'csharp' => array('clike'),
	    'css-extras' => array('css'),
	    'd' => array('clike'),
	    'dart' => array('clike'),
	    'django' => array('markup-templating'),
	    'ejs' => array('javascript', 'markup-templating'),
	    'erb' => array('ruby', 'markup-templating'),
	    'etlua' => array('lua', 'markup-templating'),
	    'firestore-security-rules' => array('clike'),
	    'flow' => array('javascript'),
	    'fsharp' => array('clike'),
	    'ftl' => array('markup-templating'),
	    'glsl' => array('c'),
	    'gml' => array('clike'),
	    'go' => array('clike'),
	    'groovy' => array('clike'),
	    'haml' => array('ruby'),
	    'handlebars' => array('markup-templating'),
	    'haxe' => array('clike'),
	    'hlsl' => array('c'),
	    'java' => array('clike'),
	    'javadoc' => array('markup', 'java', 'javadoclike'),
	    'javascript' => array('clike'),
	    'jolie' => array('clike'),
	    'js-extras' => array('javascript'),
	    'js-templates' => array('javascript'),
	    'jsdoc' => array('javascript', 'javadoclike', 'typescript'),
	    'json5' => array('json'),
	    'jsonp' => array('json'),
	    'jsx' => array('markup', 'javascript'),
	    'kotlin' => array('clike'),
	    'latte' => array('clike', 'markup-templating', 'php'),
	    'less' => array('css'),
	    'lilypond' => array('scheme'),
	    'markdown' => array('markup'),
	    'markup-templating' => array('markup'),
	    'mongodb' => array('javascript'),
	    'n4js' => array('javascript'),
	    'nginx' => array('clike'),
	    'objectivec' => array('c'),
	    'opencl' => array('c'),
	    'parser' => array('markup'),
	    'php' => array('markup-templating'),
	    'php-extras' => array('php'),
	    'phpdoc' => array('php', 'javadoclike'),
	    'plsql' => array('sql'),
	    'processing' => array('clike'),
	    'protobuf' => array('clike'),
	    'pug' => array('markup', 'javascript'),
	    'purebasic' => array('clike'),
	    'purescript' => array('haskell'),
	    'qml' => array('javascript'),
	    'qore' => array('clike'),
	    'racket' => array('scheme'),
	    'reason' => array('clike'),
	    'ruby' => array('clike'),
	    'sass' => array('css'),
	    'scala' => array('java'),
	    'scss' => array('css'),
	    'shell-session' => array('bash'),
	    'smarty' => array('markup-templating'),
	    'solidity' => array('clike'),
	    'soy' => array('markup-templating'),
	    'sparql' => array('turtle'),
	    'sqf' => array('clike'),
	    'swift' => array('clike'),
	    't4-cs' => array('t4-templating', 'csharp'),
	    't4-vb' => array('t4-templating', 'vbnet'),
	    'tap' => array('yaml'),
	    'textile' => array('markup'),
	    'tsx' => array('jsx', 'typescript'),
	    'tt2' => array('clike', 'markup-templating'),
	    'twig' => array('markup'),
	    'typescript' => array('javascript'),
	    'vala' => array('clike'),
	    'vbnet' => array('basic'),
	    'velocity' => array('markup'),
	    'wiki' => array('markup'),
	    'xeora' => array('markup'),
	    'xml-doc' => array('markup'),
	    'xquery' => array('markup'),
	);

	/**
	 * Constant. Should be same as `Markdown::MD_POST_META_PRISM`.
	 */
	const MD_POST_META_PRISM = '_githuber_prismjs';
	
	/**
	 * Constructer.
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Initialize.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'wp_enqueue_scripts', array( $this, 'front_enqueue_styles' ), $this->css_priority );
		add_action( 'wp_print_footer_scripts', array( $this, 'auto_loader_config_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'front_enqueue_scripts' ) );
		add_action( 'wp_print_footer_scripts', array( $this, 'front_print_footer_scripts' ) );
	}
 
	/**
	 * Register CSS style files for frontend use.
	 * 
	 * @return void
	 */
	public function front_enqueue_styles() {

		if ( $this->is_module_should_be_loaded( self::MD_POST_META_PRISM ) ) {
			$prism_src         = githuber_get_option( 'prism_src', 'githuber_modules' );
			$prism_theme       = githuber_get_option( 'prism_theme', 'githuber_modules' );
			$prism_line_number = githuber_get_option( 'prism_line_number', 'githuber_modules' );
			$theme             = ( 'default' === $prism_theme || empty( $prism_theme ) ) ? 'prism' : 'prism-' . $prism_theme;

			switch ( $prism_src ) {
				case 'cloudflare':
					$style_url[] = 'https://cdnjs.cloudflare.com/ajax/libs/prism/' . $this->prism_version . '/themes/' . $theme . '.min.css';
					if ( 'yes' === $prism_line_number ) {
						$style_url[] = 'https://cdnjs.cloudflare.com/ajax/libs/prism/' . $this->prism_version . '/plugins/line-numbers/prism-line-numbers.min.css';
					}
					break;

				case 'jsdelivr':
					$style_url[] = 'https://cdn.jsdelivr.net/npm/prismjs@' . $this->prism_version . '/themes/' . $theme . '.css';
					if ( 'yes' === $prism_line_number ) {
						$style_url[] ='https://cdn.jsdelivr.net/npm/prismjs@' . $this->prism_version . '/plugins/line-numbers/prism-line-numbers.css';
					}
					break;

				default:
					$style_url[] = $this->githuber_plugin_url . 'assets/vendor/prism/themes/' . $theme . '.min.css';
					if ( 'yes' === $prism_line_number ) {
						$style_url[] = $this->githuber_plugin_url . 'assets/vendor/prism/plugins/line-numbers/prism-line-numbers.css';
					}
					break;
			}

			foreach ( $style_url as $key => $url ) {
				wp_enqueue_style( 'prism-css-' . $key, $url, array(), $this->prism_version, 'all' );
			}
		}
	}

	/**
	 * Register JS files for frontend use.
	 * 
	 * @return void
	 */
	public function front_enqueue_scripts() {
		if ( $this->is_module_should_be_loaded( self::MD_POST_META_PRISM ) ) {
			$prism_src         = githuber_get_option( 'prism_src', 'githuber_modules' );
			$prism_line_number = githuber_get_option( 'prism_line_number', 'githuber_modules' );
			$post_id           = githuber_get_current_post_id();
			$prism_meta_string = get_metadata( 'post', $post_id, self::MD_POST_META_PRISM );
			$prism_meta_array  = explode( ',', $prism_meta_string[0] );

			switch ( $prism_src ) {
				case 'cloudflare':
					$script_url[] = 'https://cdnjs.cloudflare.com/ajax/libs/prism/' . $this->prism_version . '/components/prism-core.min.js';
					$script_url[] = 'https://cdnjs.cloudflare.com/ajax/libs/prism/' . $this->prism_version . '/prism.min.js';

					if ( 'yes' === $prism_line_number ) {
						$script_url[] = 'https://cdnjs.cloudflare.com/ajax/libs/prism/' . $this->prism_version . '/plugins/line-numbers/prism-line-numbers.min.js';
					}

					// AutoLoader plugin
					$script_url[] = 'https://cdnjs.cloudflare.com/ajax/libs/prism/' . $this->prism_version . '/plugins/autoloader/prism-autoloader.min.js';

					break;

				case 'jsdelivr':
					$script_url[] = 'https://cdn.jsdelivr.net/npm/prismjs@' . $this->prism_version . '/components/prism-core.min.js';
					$script_url[] = 'https://cdn.jsdelivr.net/npm/prismjs@' . $this->prism_version . '/prism.min.js';

					if ( 'yes' === $prism_line_number ) {
						$script_url[] = 'https://cdn.jsdelivr.net/npm/prismjs@' . $this->prism_version . '/plugins/line-numbers/prism-line-numbers.min.js';
					}

					// AutoLoader plugin (Add since 1.11.4)
					$script_url[] = 'https://cdn.jsdelivr.net/npm/prismjs@' . $this->prism_version . '/plugins/autoloader/prism-autoloader.min.js';

					break;

				default: 
					$script_url[] = $this->githuber_plugin_url . 'assets/vendor/prism/components/prism-core.min.js';
					$script_url[] = $this->githuber_plugin_url . 'assets/vendor/prism/prism.min.js';

					if ( 'yes' === $prism_line_number ) {
						$script_url[] = $this->githuber_plugin_url . 'assets/vendor/prism/plugins/line-numbers/prism-line-numbers.min.js';
					}

					// AutoLoader plugin (Add since 1.11.4)
					$script_url[] = $this->githuber_plugin_url . 'assets/vendor/prism/plugins/autoloader/prism-autoloader.min.js';

					break;
			}

			foreach ( $script_url as $key => $url ) {
				wp_enqueue_script( 'prism-js-' . $key, $url, array(), $this->prism_version, true );
			}
		}
	}

	/**
	 * Configure auto loader path.
	 * 
	 * @since 1.11.4
	 */
	public function auto_loader_config_scripts() {
		if ( $this->is_module_should_be_loaded( self::MD_POST_META_PRISM ) ) {
			$prism_src = githuber_get_option( 'prism_src', 'githuber_modules' );

			switch ( $prism_src ) {
				case 'cloudflare':
					$script_path = 'https://cdnjs.cloudflare.com/ajax/libs/prism/' . $this->prism_version . '/components/';

					break;

				case 'jsdelivr':
					$script_path = 'https://cdn.jsdelivr.net/npm/prismjs@' . $this->prism_version . '/components/';

					break;

				default: 
					$script_path = $this->githuber_plugin_url . 'assets/vendor/prism/components/';

					break;
			}

			$script = '
				<script id="auto_loader_config_scripts">
					Prism.plugins.autoloader.languages_path = "' . $script_path . '";
				</script>
			';

			echo preg_replace( '/\s+/', ' ', $script );
		}
	}

	/**
	 * Print Javascript plaintext in page footer.
	 */
	public function front_print_footer_scripts() {
		$prism_line_number = githuber_get_option( 'prism_line_number', 'githuber_modules' );

		if ( 'yes' === $prism_line_number ) {
			$script = '
				<script id="module-prism-line-number">
					(function($) {
						$(function() {
							$("code").each(function() {
								var parent_div = $(this).parent("pre");
								var pre_css = $(this).attr("class");
								if (typeof pre_css !== "undefined" && -1 !== pre_css.indexOf("language-")) {
									parent_div.addClass("line-numbers");
								}
							});
						});
					})(jQuery);
				</script>
			';
			echo preg_replace( '/\s+/', ' ', $script );
		}
	}

	/**
	 * (Deprecated since 1.11.4) (Use auto-loader instead)
	 * 
	 *
	 * Check if component is already loaded or not.
	 * Those scripts are already included in prism.js, so we do not need to load those scripts again.
	 *
	 * @param string $name Prism component name.
	 *
	 * @return boolean
	 */
	public function is_component_already_loaded( $name ) {
		switch ( $name ) {
			case 'markup':
			case 'xml':
			case 'html':
			case 'mathml':
			case 'svg':
			case 'clike':
			case 'javascript':
			case 'js':
				return true;
				break;
			default:
				return false;
		}
	}
}
