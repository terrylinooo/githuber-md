<?php
/**
 * Module Name: HightLight
 * Module Description: A syntax highlighter by highlight.js
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

class Highlight extends ModuleAbstract {

	/**
	 * The version of Prism we are using.
	 *
	 * @var string
	 */
	public $highlight_version = '9.15.10';

	/**
	 * The priority order to load CSS file, the value should be higher than theme's.
	 * Overwrite the theme's style to make sure that it's safe to display the correct syntax highlight.
	 *
	 * @var integer
	 */
	public $css_priority = 999;

	// This is what highlight.js uses.
	public static $highlight_codes = array(
		'1c'             => '1C:Enterprise (v7, v8)',
		'abnf'           => 'Augmented Backus-Naur Form',
		'accesslog'      => 'Access log',
		'actionscript'   => 'ActionScript',
		'ada'            => 'Ada',
		'angelscript'    => 'AngelScript',
		'apache'         => 'Apache',
		'applescript'    => 'AppleScript',
		'arcade'         => 'ArcGIS Arcade',
		'arduino'        => 'Arduino',
		'armasm'         => 'ARM Assembly',
		'asciidoc'       => 'AsciiDoc',
		'aspectj'        => 'AspectJ',
		'autohotkey'     => 'AutoHotkey',
		'autoit'         => 'AutoIt',
		'avrasm'         => 'AVR Assembler',
		'awk'            => 'Awk',
		'axapta'         => 'Microsoft Axapta (now Dynamics 365)',
		'bash'           => 'Bash',
		'basic'          => 'Basic',
		'bnf'            => 'Backus–Naur Form',
		'brainfuck'      => 'Brainfuck',
		'c'              => 'C',
		'cal'            => 'C/AL',
		'capnproto'      => 'Cap’n Proto',
		'ceylon'         => 'Ceylon',
		'clean'          => 'Clean',
		'clojure-repl'   => 'Clojure REPL',
		'clojure'        => 'Clojure',
		'cmake'          => 'CMake',
		'coffeescript'   => 'CoffeeScript',
		'coq'            => 'Coq',
		'cos'            => 'Cache Object Script',
		'cpp'            => 'C++',
		'crmsh'          => 'crmsh',
		'crystal'        => 'Crystal',
		'cs'             => 'C#',
		'csp'            => 'CSP',
		'css'            => 'CSS',
		'd'              => 'D',
		'dart'           => 'Dart',
		'delphi'         => 'Delphi',
		'diff'           => 'Diff',
		'django'         => 'Django',
		'dns'            => 'DNS Zone file',
		'dockerfile'     => 'Dockerfile',
		'dos'            => 'DOS .bat',
		'dsconfig'       => 'dsconfig',
		'dts'            => 'Device Tree',
		'dust'           => 'Dust',
		'ebnf'           => 'Extended Backus-Naur Form',
		'elixir'         => 'Elixir',
		'elm'            => 'Elm',
		'erb'            => 'ERB (Embedded Ruby)',
		'erlang-repl'    => 'Erlang REPL',
		'erlang'         => 'Erlang',
		'excel'          => 'Excel',
		'fix'            => 'FIX',
		'flix'           => 'Flix',
		'fortran'        => 'Fortran',
		'fsharp'         => 'F#',
		'gams'           => 'GAMS',
		'gauss'          => 'GAUSS',
		'gcode'          => 'G-code (ISO 6983)',
		'gherkin'        => 'Gherkin',
		'glsl'           => 'GLSL',
		'gml'            => 'GML',
		'go'             => 'Golang',
		'golo'           => 'Golo',
		'gradle'         => 'Gradle',
		'groovy'         => 'Groovy',
		'haml'           => 'Haml',
		'handlebars'     => 'Handlebars',
		'haskell'        => 'Haskell',
		'haxe'           => 'Haxe',
		'hsp'            => 'HSP',
		'htmlbars'       => 'HTMLBars',
		'http'           => 'HTTP (Header Plaintext)',
		'hy'             => 'Hy',
		'inform7'        => 'Inform 7',
		'ini'            => 'TOML, also INI',
		'irpf90'         => 'IRPF90',
		'isbl'           => 'ISBL',
		'java'           => 'Java',
		'javascript'     => 'JavaScript',
		'jboss-cli'      => 'jboss-cli',
		'json'           => 'JSON / JSON with Comments',
		'julia-repl'     => 'Julia REPL',
		'julia'          => 'Julia',
		'kotlin'         => 'Kotlin',
		'lasso'          => 'Lasso',
		'ldif'           => 'LDIF',
		'leaf'           => 'Leaf',
		'less'           => 'Less',
		'lisp'           => 'Lisp',
		'livecodeserver' => 'LiveCode',
		'livescript'     => 'LiveScript',
		'llvm'           => 'LLVM IR',
		'lsl'            => 'LSL (Linden Scripting Language)',
		'lua'            => 'Lua',
		'makefile'       => 'Makefile',
		'markdown'       => 'Markdown',
		'mathematica'    => 'Mathematica',
		'matlab'         => 'Matlab',
		'maxima'         => 'Maxima',
		'mel'            => 'MEL',
		'mercury'        => 'Mercury',
		'mipsasm'        => 'MIPS Assembly',
		'mizar'          => 'Mizar',
		'mojolicious'    => 'Mojolicious',
		'monkey'         => 'Monkey',
		'moonscript'     => 'MoonScript',
		'n1ql'           => 'N1QL',
		'nginx'          => 'Nginx',
		'nimrod'         => 'Nim (formerly Nimrod)',
		'nix'            => 'Nix',
		'nsis'           => 'NSIS',
		'objectivec'     => 'Objective-C',
		'ocaml'          => 'OCaml',
		'openscad'       => 'OpenSCAD',
		'oxygene'        => 'Oxygene',
		'parser3'        => 'Parser3',
		'perl'           => 'Perl',
		'pf'             => 'pf.conf',
		'pgsql'          => 'PostgreSQL SQL dialect and PL/pgSQL',
		'php'            => 'PHP',
		'plaintext'      => 'Plaintext',
		'pony'           => 'Pony',
		'powershell'     => 'PowerShell',
		'processing'     => 'Processing',
		'profile'        => 'Python profile',
		'prolog'         => 'Prolog',
		'properties'     => 'Properties',
		'protobuf'       => 'Protocol Buffers',
		'puppet'         => 'Puppet',
		'purebasic'      => 'PureBASIC',
		'python'         => 'Pythin',
		'q'              => 'Q',
		'qml'            => 'QML',
		'r'              => 'R',
		'reasonml'       => 'ReasonML',
		'rib'            => 'RenderMan RIB',
		'roboconf'       => 'Roboconf',
		'routeros'       => 'Microtik RouterOS script',
		'rsl'            => 'RenderMan RSL',
		'ruby'           => 'Ruby',
		'ruleslanguage'  => 'Oracle Rules Language',
		'rust'           => 'Rust',
		'sas'            => 'SAS',
		'scala'          => 'Scala',
		'scheme'         => 'Scheme',
		'scilab'         => 'Scilab',
		'scss'           => 'SCSS',
		'shell'          => 'Shell Session',
		'smali'          => 'Smali',
		'smalltalk'      => 'Smalltalk',
		'sml'            => 'SML (Standard ML)',
		'sqf'            => 'SQF',
		'sql'            => 'SQL (Structured Query Language)',
		'stan'           => 'Stan',
		'stata'          => 'Stata',
		'step21'         => 'STEP Part 21',
		'stylus'         => 'Stylus',
		'subunit'        => 'SubUnit',
		'swift'          => 'Swift',
		'taggerscript'   => 'Tagger Script',
		'tap'            => 'Test Anything Protocol',
		'tcl'            => 'Tcl',
		'tex'            => 'TeX',
		'thrift'         => 'Thrift',
		'tp'             => 'TP',
		'twig'           => 'Twig',
		'typescript'     => 'TypeScript',
		'vala'           => 'Vala',
		'vbnet'          => 'VB.NET',
		'vbscript-html'  => 'VBScript in HTML',
		'vbscript'       => 'VBScript in HTML',
		'verilog'        => 'Verilog',
		'vhdl'           => 'VHDL',
		'vim'            => 'Vim Script',
		'x86asm'         => 'Intel x86 Assembly',
		'xl'             => 'XL',
		'xml'            => 'HTML, XML',
		'xquery'         => 'XQuery',
		'yaml'           => 'YAML',
		'zephir'         => 'Zephir',
	);

	/**
	 * Constant. Should be same as `Markdown::MD_POST_META_HIGHLIGHT`.
	 */
	const MD_POST_META_HIGHLIGHT = '_githuber_highlightjs';
	
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
		add_action( 'wp_enqueue_scripts', array( $this, 'front_enqueue_scripts' ) );
		add_action( 'wp_print_footer_scripts', array( $this, 'front_print_footer_scripts' ) );
	}
 
	/**
	 * Register CSS style files for frontend use.
	 * 
	 * @return void
	 */
	public function front_enqueue_styles() {
		if ( $this->is_module_should_be_loaded( self::MD_POST_META_HIGHLIGHT ) ) {
			$highlight_src   = githuber_get_option( 'highlight_src', 'githuber_modules' );
			$highlight_theme = githuber_get_option( 'highlight_theme', 'githuber_modules' );

			$theme = ( 'default' === $highlight_theme || empty( $highlight_theme ) ) ? 'default' : $highlight_theme;

			switch ( $highlight_src ) {
				case 'cloudflare':
					$style_url[] = 'https://cdnjs.cloudflare.com/ajax/libs/highlight.js/' . $this->highlight_version . '/styles/' . $theme . '.min.css';
					break;

				default:
					$style_url[] = $this->githuber_plugin_url . 'assets/vendor/highlight.js/styles/' . $theme . '.min.css';
					break;
			}

			foreach ( $style_url as $key => $url ) {
				wp_enqueue_style( 'highlight-css-' . $key, $url, array(), $this->highlight_version, 'all' );
			}
		}
	}

	/**
	 * Register JS files for frontend use.
	 * 
	 * @return void
	 */
	public function front_enqueue_scripts() {
		if ( $this->is_module_should_be_loaded( self::MD_POST_META_HIGHLIGHT ) ) {
			$highlight_src         = githuber_get_option( 'highlight_src', 'githuber_modules' );
			$post_id               = githuber_get_current_post_id();
			$highlight_meta_string = get_metadata( 'post', $post_id, self::MD_POST_META_HIGHLIGHT );
			$highlight_meta_array  = explode( ',', $highlight_meta_string[0] );

			switch ( $highlight_src ) {
				case 'cloudflare':
					$script_url[] = 'https://cdnjs.cloudflare.com/ajax/libs/highlight.js/' . $this->highlight_version . '/highlight.min.js';

					if ( ! empty( $highlight_meta_array ) ) {
						foreach ( array_reverse( $highlight_meta_array ) as $component_name ) {
							if ( 'c' === $component_name ) {
								$component_name = 'cpp';
							}
							$script_url[] = 'https://cdnjs.cloudflare.com/ajax/libs/highlight.js/' . $this->highlight_version . '/languages/' . $component_name . '.min.js';
						}
					}
					break;

				default: 
					$script_url[] = $this->githuber_plugin_url . 'assets/vendor/highlight.js/highlight.min.js';

					if ( ! empty( $highlight_meta_array ) ) {
						foreach ( array_reverse( $highlight_meta_array ) as $component_name ) {
							if ( 'c' === $component_name ) {
								$component_name = 'cpp';
							}
							$script_url[] = $this->githuber_plugin_url . 'assets/vendor/highlight.js/languages/' . $component_name . '.min.js';
						}
					}

					break;
			}

			foreach ( $script_url as $key => $url ) {
				wp_enqueue_script( 'highlight-js-' . $key, $url, array(), $this->highlight_version, true );
			}
		}
	}

	/**
	 * Print Javascript plaintext in page footer.
	 */
	public function front_print_footer_scripts() {
		$script = '
			<script id="module-highlight-js">
				(function($) {
					$(function() {
						$("pre code").each(function(i, e) {
							var thisclass = $(this).attr("class");

							if (typeof thisclass !== "undefined") {
								if (
									thisclass.indexOf("katex") === -1 &&
									thisclass.indexOf("mermaid") === -1 &&
									thisclass.indexOf("seq") === -1 &&
									thisclass.indexOf("flow") === -1
								) {
									if (typeof hljs !== "undefined") {
										$(this).closest("pre").addClass("hljs");
										hljs.highlightBlock(e);
									} else {
										
										console.log("%c WP Githuber MD %c You have enabled highlight.js modules already, but you have to update this post to take effect, identifying which file should be loaded.\nGithuber MD does not load a whole-fat-packed file for every post.", "background: #222; color: #bada55", "color: #637338");
									}
								}
							}
						});
					});
				})(jQuery);
			</script>
		';
		echo preg_replace( '/\s+/', ' ', $script );
	}
}
