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
 */

namespace Githuber\Module;

/**
 * Prism.
 */
class Prism extends ModuleAbstract {

	/**
	 * The version of Prism we are using.
	 *
	 * @var string
	 */
	public $prism_version = '1.28.0';

	/**
	 * The priority order to load CSS file, the value should be higher than theme's.
	 * Overwrite the theme's style to make sure that it's safe to display the correct syntax highlight.
	 *
	 * @var integer
	 */
	public $css_priority = 999;

	/**
	 * This is what Prism.js uses. generated using sh/generate-prism-codes
	 *
	 * @var array
	 */
	public static $prism_codes = array(
		'abap'                     => 'ABAP',
		'abnf'                     => 'ABNF',
		'actionscript'             => 'ActionScript',
		'ada'                      => 'Ada',
		'adoc'                     => 'AsciiDoc',
		'agda'                     => 'Agda',
		'al'                       => 'AL',
		'antlr4'                   => 'ANTLR4',
		'apacheconf'               => 'Apache Configuration',
		'apex'                     => 'Apex',
		'apl'                      => 'APL',
		'applescript'              => 'AppleScript',
		'aql'                      => 'AQL',
		'arduino'                  => 'Arduino',
		'arff'                     => 'ARFF',
		'arm-asm'                  => 'ARM Assembly',
		'armasm'                   => 'ARM Assembly',
		'art'                      => 'Arturo',
		'arturo'                   => 'Arturo',
		'asciidoc'                 => 'AsciiDoc',
		'asm6502'                  => '6502 Assembly',
		'asmatmel'                 => 'Atmel AVR Assembly',
		'aspnet'                   => 'ASP.NET (C#)',
		'atom'                     => 'Atom',
		'autohotkey'               => 'AutoHotkey',
		'autoit'                   => 'AutoIt',
		'avdl'                     => 'Avro IDL',
		'avisynth'                 => 'AviSynth',
		'avro-idl'                 => 'Avro IDL',
		'avs'                      => 'AviSynth',
		'awk'                      => 'AWK',
		'bash'                     => 'Bash',
		'basic'                    => 'BASIC',
		'batch'                    => 'Batch',
		'bbcode'                   => 'BBcode',
		'bicep'                    => 'Bicep',
		'birb'                     => 'Birb',
		'bison'                    => 'Bison',
		'bnf'                      => 'BNF',
		'brainfuck'                => 'Brainfuck',
		'brightscript'             => 'BrightScript',
		'bro'                      => 'Bro',
		'bsl'                      => 'BSL (1C:Enterprise)',
		'c'                        => 'C',
		'cfc'                      => 'CFScript',
		'cfscript'                 => 'CFScript',
		'chaiscript'               => 'ChaiScript',
		'cil'                      => 'CIL',
		'clike'                    => 'C-like',
		'clojure'                  => 'Clojure',
		'cmake'                    => 'CMake',
		'cobol'                    => 'COBOL',
		'coffee'                   => 'CoffeeScript',
		'coffeescript'             => 'CoffeeScript',
		'conc'                     => 'Concurnas',
		'concurnas'                => 'Concurnas',
		'context'                  => 'ConTeXt',
		'cooklang'                 => 'Cooklang',
		'coq'                      => 'Coq',
		'cpp'                      => 'C++',
		'crystal'                  => 'Crystal',
		'cs'                       => 'C#',
		'csharp'                   => 'C#',
		'cshtml'                   => 'Razor C#',
		'csp'                      => 'Content-Security-Policy',
		'css'                      => 'CSS',
		'css-extras'               => 'CSS Extras',
		'csv'                      => 'CSV',
		'cue'                      => 'CUE',
		'cypher'                   => 'Cypher',
		'd'                        => 'D',
		'dart'                     => 'Dart',
		'dataweave'                => 'DataWeave',
		'dax'                      => 'DAX',
		'dhall'                    => 'Dhall',
		'diff'                     => 'Diff',
		'django'                   => 'Django/Jinja2',
		'dns-zone'                 => 'DNS zone file',
		'dns-zone-file'            => 'DNS zone file',
		'docker'                   => 'Docker',
		'dockerfile'               => 'Docker',
		'dot'                      => 'DOT (Graphviz)',
		'dotnet'                   => 'C#',
		'ebnf'                     => 'EBNF',
		'editorconfig'             => 'EditorConfig',
		'eiffel'                   => 'Eiffel',
		'ejs'                      => 'EJS',
		'elisp'                    => 'Lisp',
		'elixir'                   => 'Elixir',
		'elm'                      => 'Elm',
		'emacs'                    => 'Lisp',
		'emacs-lisp'               => 'Lisp',
		'erb'                      => 'ERB',
		'erlang'                   => 'Erlang',
		'eta'                      => 'Eta',
		'etlua'                    => 'Embedded Lua templating',
		'excel-formula'            => 'Excel Formula',
		'factor'                   => 'Factor',
		'false'                    => 'False',
		'firestore-security-rules' => 'Firestore security rules',
		'flow'                     => 'Flow',
		'fortran'                  => 'Fortran',
		'fsharp'                   => 'F#',
		'ftl'                      => 'FreeMarker Template Language',
		'g4'                       => 'ANTLR4',
		'gamemakerlanguage'        => 'GameMaker Language',
		'gap'                      => 'GAP (CAS)',
		'gawk'                     => 'GAWK',
		'gcode'                    => 'G-code',
		'gdscript'                 => 'GDScript',
		'gedcom'                   => 'GEDCOM',
		'gettext'                  => 'gettext',
		'gherkin'                  => 'Gherkin',
		'git'                      => 'Git',
		'gitignore'                => '.gitignore',
		'glsl'                     => 'GLSL',
		'gml'                      => 'GameMaker Language',
		'gn'                       => 'GN',
		'gni'                      => 'GN',
		'go'                       => 'Go',
		'go-mod'                   => 'Go module',
		'go-module'                => 'Go module',
		'graphql'                  => 'GraphQL',
		'groovy'                   => 'Groovy',
		'gv'                       => 'DOT (Graphviz)',
		'haml'                     => 'Haml',
		'handlebars'               => 'Handlebars',
		'haskell'                  => 'Haskell',
		'haxe'                     => 'Haxe',
		'hbs'                      => 'Handlebars',
		'hcl'                      => 'HCL',
		'hgignore'                 => '.hgignore',
		'hlsl'                     => 'HLSL',
		'hoon'                     => 'Hoon',
		'hpkp'                     => 'HTTP Public-Key-Pins',
		'hs'                       => 'Haskell',
		'hsts'                     => 'HTTP Strict-Transport-Security',
		'html'                     => 'HTML',
		'http'                     => 'HTTP',
		'ichigojam'                => 'IchigoJam',
		'icon'                     => 'Icon',
		'icu-message-format'       => 'ICU Message Format',
		'idr'                      => 'Idris',
		'idris'                    => 'Idris',
		'iecst'                    => 'Structured Text (IEC 61131-3)',
		'ignore'                   => '.ignore',
		'inform7'                  => 'Inform 7',
		'ini'                      => 'Ini',
		'ino'                      => 'Arduino',
		'io'                       => 'Io',
		'j'                        => 'J',
		'java'                     => 'Java',
		'javadoc'                  => 'JavaDoc',
		'javadoclike'              => 'JavaDoc-like',
		'javascript'               => 'JavaScript',
		'javastacktrace'           => 'Java stack trace',
		'jexl'                     => 'Jexl',
		'jinja2'                   => 'Django/Jinja2',
		'jolie'                    => 'Jolie',
		'jq'                       => 'JQ',
		'js'                       => 'JavaScript',
		'js-extras'                => 'JS Extras',
		'js-templates'             => 'JS Templates',
		'jsdoc'                    => 'JSDoc',
		'json'                     => 'JSON',
		'json5'                    => 'JSON5',
		'jsonp'                    => 'JSONP',
		'jsstacktrace'             => 'JS stack trace',
		'jsx'                      => 'React JSX',
		'julia'                    => 'Julia',
		'keepalived'               => 'Keepalived Configure',
		'keyman'                   => 'Keyman',
		'kotlin'                   => 'Kotlin',
		'kt'                       => 'Kotlin',
		'kts'                      => 'Kotlin Script',
		'kum'                      => 'KuMir (КуМир)',
		'kumir'                    => 'KuMir (КуМир)',
		'kusto'                    => 'Kusto',
		'latex'                    => 'LaTeX',
		'latte'                    => 'Latte',
		'ld'                       => 'GNU Linker Script',
		'less'                     => 'Less',
		'lilypond'                 => 'LilyPond',
		'linker-script'            => 'GNU Linker Script',
		'liquid'                   => 'Liquid',
		'lisp'                     => 'Lisp',
		'livescript'               => 'LiveScript',
		'llvm'                     => 'LLVM IR',
		'log'                      => 'Log file',
		'lolcode'                  => 'LOLCODE',
		'lua'                      => 'Lua',
		'ly'                       => 'LilyPond',
		'magma'                    => 'Magma (CAS)',
		'makefile'                 => 'Makefile',
		'markdown'                 => 'Markdown',
		'markup'                   => 'Markup',
		'markup-templating'        => 'Markup templating',
		'mata'                     => 'Mata',
		'mathematica'              => 'Mathematica',
		'mathml'                   => 'MathML',
		'matlab'                   => 'MATLAB',
		'maxscript'                => 'MAXScript',
		'md'                       => 'Markdown',
		'mel'                      => 'MEL',
		'mermaid'                  => 'Mermaid',
		'mizar'                    => 'Mizar',
		'mongodb'                  => 'MongoDB',
		'monkey'                   => 'Monkey',
		'moon'                     => 'MoonScript',
		'moonscript'               => 'MoonScript',
		'mscript'                  => 'PowerQuery',
		'mustache'                 => 'Mustache',
		'n1ql'                     => 'N1QL',
		'n4js'                     => 'N4JS',
		'n4jsd'                    => 'N4JS',
		'nand2tetris-hdl'          => 'Nand To Tetris HDL',
		'nani'                     => 'Naninovel Script',
		'naniscript'               => 'Naninovel Script',
		'nasm'                     => 'NASM',
		'nb'                       => 'Mathematica Notebook',
		'neon'                     => 'NEON',
		'nevod'                    => 'Nevod',
		'nginx'                    => 'nginx',
		'nim'                      => 'Nim',
		'nix'                      => 'Nix',
		'npmignore'                => '.npmignore',
		'nsis'                     => 'NSIS',
		'objc'                     => 'Objective-C',
		'objectivec'               => 'Objective-C',
		'objectpascal'             => 'Object Pascal',
		'ocaml'                    => 'OCaml',
		'odin'                     => 'Odin',
		'opencl'                   => 'OpenCL',
		'openqasm'                 => 'OpenQasm',
		'oscript'                  => 'OneScript',
		'oz'                       => 'Oz',
		'parigp'                   => 'PARI/GP',
		'parser'                   => 'Parser',
		'pascal'                   => 'Pascal',
		'pascaligo'                => 'Pascaligo',
		'pbfasm'                   => 'PureBasic',
		'pcaxis'                   => 'PC-Axis',
		'pcode'                    => 'PeopleCode',
		'peoplecode'               => 'PeopleCode',
		'perl'                     => 'Perl',
		'php'                      => 'PHP',
		'php-extras'               => 'PHP Extras',
		'phpdoc'                   => 'PHPDoc',
		'plant-uml'                => 'PlantUML',
		'plantuml'                 => 'PlantUML',
		'plsql'                    => 'PL/SQL',
		'po'                       => 'gettext',
		'powerquery'               => 'PowerQuery',
		'powershell'               => 'PowerShell',
		'pq'                       => 'PowerQuery',
		'processing'               => 'Processing',
		'prolog'                   => 'Prolog',
		'promql'                   => 'PromQL',
		'properties'               => '.properties',
		'protobuf'                 => 'Protocol Buffers',
		'psl'                      => 'PATROL Scripting Language',
		'pug'                      => 'Pug',
		'puppet'                   => 'Puppet',
		'pure'                     => 'Pure',
		'purebasic'                => 'PureBasic',
		'purescript'               => 'PureScript',
		'purs'                     => 'PureScript',
		'px'                       => 'PC-Axis',
		'py'                       => 'Python',
		'python'                   => 'Python',
		'q'                        => 'Q (kdb+ database)',
		'qasm'                     => 'OpenQasm',
		'qml'                      => 'QML',
		'qore'                     => 'Qore',
		'qs'                       => 'Q#',
		'qsharp'                   => 'Q#',
		'r'                        => 'R',
		'racket'                   => 'Racket',
		'razor'                    => 'Razor C#',
		'rb'                       => 'Ruby',
		'rbnf'                     => 'RBNF',
		'reason'                   => 'Reason',
		'regex'                    => 'Regex',
		'rego'                     => 'Rego',
		'renpy'                    => 'Ren\'py',
		'res'                      => 'ReScript',
		'rescript'                 => 'ReScript',
		'rest'                     => 'reST (reStructuredText)',
		'rip'                      => 'Rip',
		'rkt'                      => 'Racket',
		'roboconf'                 => 'Roboconf',
		'robot'                    => 'Robot Framework',
		'robotframework'           => 'Robot Framework',
		'rpy'                      => 'Ren\'py',
		'rq'                       => 'SPARQL',
		'rss'                      => 'RSS',
		'ruby'                     => 'Ruby',
		'rust'                     => 'Rust',
		'sas'                      => 'SAS',
		'sass'                     => 'Sass (Sass)',
		'scala'                    => 'Scala',
		'scheme'                   => 'Scheme',
		'sclang'                   => 'SuperCollider',
		'scss'                     => 'Sass (Scss)',
		'sh-session'               => 'Shell session',
		'shell'                    => 'Shell',
		'shell-session'            => 'Shell session',
		'shellsession'             => 'Shell session',
		'shortcode'                => 'Shortcode',
		'sln'                      => 'Solution file',
		'smali'                    => 'Smali',
		'smalltalk'                => 'Smalltalk',
		'smarty'                   => 'Smarty',
		'sml'                      => 'SML',
		'smlnj'                    => 'SML/NJ',
		'sol'                      => 'Solidity (Ethereum)',
		'solidity'                 => 'Solidity (Ethereum)',
		'solution-file'            => 'Solution file',
		'soy'                      => 'Soy (Closure Template)',
		'sparql'                   => 'SPARQL',
		'splunk-spl'               => 'Splunk SPL',
		'sqf'                      => 'SQF: Status Quo Function (Arma 3)',
		'sql'                      => 'SQL',
		'squirrel'                 => 'Squirrel',
		'ssml'                     => 'SSML',
		'stan'                     => 'Stan',
		'stata'                    => 'Stata Ado',
		'stylus'                   => 'Stylus',
		'supercollider'            => 'SuperCollider',
		'svg'                      => 'SVG',
		'swift'                    => 'Swift',
		'systemd'                  => 'Systemd configuration file',
		't4'                       => 'T4 Text Templates (C#)',
		't4-cs'                    => 'T4 Text Templates (C#)',
		't4-templating'            => 'T4 templating',
		't4-vb'                    => 'T4 Text Templates (VB)',
		'tap'                      => 'TAP',
		'tcl'                      => 'Tcl',
		'tex'                      => 'TeX',
		'textile'                  => 'Textile',
		'toml'                     => 'TOML',
		'tremor'                   => 'Tremor',
		'trickle'                  => 'trickle',
		'trig'                     => 'TriG',
		'troy'                     => 'troy',
		'ts'                       => 'TypeScript',
		'tsconfig'                 => 'TSConfig',
		'tsx'                      => 'React TSX',
		'tt2'                      => 'Template Toolkit 2',
		'turtle'                   => 'Turtle',
		'twig'                     => 'Twig',
		'typescript'               => 'TypeScript',
		'typoscript'               => 'TypoScript',
		'uc'                       => 'UnrealScript',
		'unrealscript'             => 'UnrealScript',
		'uorazor'                  => 'UO Razor Script',
		'uri'                      => 'URI',
		'url'                      => 'URL',
		'uscript'                  => 'UnrealScript',
		'v'                        => 'V',
		'vala'                     => 'Vala',
		'vb'                       => 'Visual Basic',
		'vba'                      => 'VBA',
		'vbnet'                    => 'VB.Net',
		'velocity'                 => 'Velocity',
		'verilog'                  => 'Verilog',
		'vhdl'                     => 'VHDL',
		'vim'                      => 'vim',
		'visual-basic'             => 'Visual Basic',
		'warpscript'               => 'WarpScript',
		'wasm'                     => 'WebAssembly',
		'web-idl'                  => 'Web IDL',
		'webidl'                   => 'Web IDL',
		'webmanifest'              => 'Web App Manifest',
		'wiki'                     => 'Wiki markup',
		'wl'                       => 'Wolfram language',
		'wolfram'                  => 'Wolfram language',
		'wren'                     => 'Wren',
		'xeora'                    => 'Xeora',
		'xeoracube'                => 'XeoraCube',
		'xls'                      => 'Excel Formula',
		'xlsx'                     => 'Excel Formula',
		'xml'                      => 'XML',
		'xml-doc'                  => 'XML doc (.net)',
		'xojo'                     => 'Xojo (REALbasic)',
		'xquery'                   => 'XQuery',
		'yaml'                     => 'YAML',
		'yang'                     => 'YANG',
		'yml'                      => 'YAML',
		'zig'                      => 'Zig',
	);

	/**
	 * The below codes need a parent component being loaded before. generated using sh/generate-prism-codes
	 *
	 * @var array
	 */
	public static $prism_component_parent = array(
		'actionscript'             => array( 'javascript' ),
		'apex'                     => array( 'clike', 'sql' ),
		'arduino'                  => array( 'cpp' ),
		'aspnet'                   => array( 'markup', 'csharp' ),
		'birb'                     => array( 'clike' ),
		'bison'                    => array( 'c' ),
		'c'                        => array( 'clike' ),
		'cfc'                      => array( 'clike' ),
		'cfscript'                 => array( 'clike' ),
		'chaiscript'               => array( 'clike', 'cpp' ),
		'coffee'                   => array( 'javascript' ),
		'coffeescript'             => array( 'javascript' ),
		'cpp'                      => array( 'c' ),
		'crystal'                  => array( 'ruby' ),
		'cs'                       => array( 'clike' ),
		'csharp'                   => array( 'clike' ),
		'cshtml'                   => array( 'markup', 'csharp' ),
		'css-extras'               => array( 'css' ),
		'd'                        => array( 'clike' ),
		'dart'                     => array( 'clike' ),
		'django'                   => array( 'markup-templating' ),
		'dotnet'                   => array( 'clike' ),
		'ejs'                      => array( 'javascript', 'markup-templating' ),
		'erb'                      => array( 'ruby', 'markup-templating' ),
		'eta'                      => array( 'javascript', 'markup-templating' ),
		'etlua'                    => array( 'lua', 'markup-templating' ),
		'firestore-security-rules' => array( 'clike' ),
		'flow'                     => array( 'javascript' ),
		'fsharp'                   => array( 'clike' ),
		'ftl'                      => array( 'markup-templating' ),
		'gamemakerlanguage'        => array( 'clike' ),
		'glsl'                     => array( 'c' ),
		'gml'                      => array( 'clike' ),
		'go'                       => array( 'clike' ),
		'groovy'                   => array( 'clike' ),
		'haml'                     => array( 'ruby' ),
		'handlebars'               => array( 'markup-templating' ),
		'haxe'                     => array( 'clike' ),
		'hbs'                      => array( 'markup-templating' ),
		'hlsl'                     => array( 'c' ),
		'idr'                      => array( 'haskell' ),
		'idris'                    => array( 'haskell' ),
		'ino'                      => array( 'cpp' ),
		'java'                     => array( 'clike' ),
		'javadoc'                  => array( 'markup', 'java', 'javadoclike' ),
		'javascript'               => array( 'clike' ),
		'jinja2'                   => array( 'markup-templating' ),
		'jolie'                    => array( 'clike' ),
		'js'                       => array( 'clike' ),
		'js-extras'                => array( 'javascript' ),
		'js-templates'             => array( 'javascript' ),
		'jsdoc'                    => array( 'javascript', 'javadoclike', 'typescript' ),
		'json5'                    => array( 'json' ),
		'jsonp'                    => array( 'json' ),
		'jsx'                      => array( 'markup', 'javascript' ),
		'kotlin'                   => array( 'clike' ),
		'kt'                       => array( 'clike' ),
		'kts'                      => array( 'clike' ),
		'latte'                    => array( 'clike', 'markup-templating', 'php' ),
		'less'                     => array( 'css' ),
		'lilypond'                 => array( 'scheme' ),
		'liquid'                   => array( 'markup-templating' ),
		'ly'                       => array( 'scheme' ),
		'markdown'                 => array( 'markup' ),
		'markup-templating'        => array( 'markup' ),
		'md'                       => array( 'markup' ),
		'mongodb'                  => array( 'javascript' ),
		'mustache'                 => array( 'markup-templating' ),
		'n4js'                     => array( 'javascript' ),
		'n4jsd'                    => array( 'javascript' ),
		'objc'                     => array( 'c' ),
		'objectivec'               => array( 'c' ),
		'opencl'                   => array( 'c' ),
		'parser'                   => array( 'markup' ),
		'pbfasm'                   => array( 'clike' ),
		'php'                      => array( 'markup-templating' ),
		'php-extras'               => array( 'php' ),
		'phpdoc'                   => array( 'php', 'javadoclike' ),
		'plsql'                    => array( 'sql' ),
		'processing'               => array( 'clike' ),
		'protobuf'                 => array( 'clike' ),
		'pug'                      => array( 'markup', 'javascript' ),
		'purebasic'                => array( 'clike' ),
		'purescript'               => array( 'haskell' ),
		'purs'                     => array( 'haskell' ),
		'qml'                      => array( 'javascript' ),
		'qore'                     => array( 'clike' ),
		'qs'                       => array( 'clike' ),
		'qsharp'                   => array( 'clike' ),
		'racket'                   => array( 'scheme' ),
		'razor'                    => array( 'markup', 'csharp' ),
		'rb'                       => array( 'clike' ),
		'reason'                   => array( 'clike' ),
		'rkt'                      => array( 'scheme' ),
		'rq'                       => array( 'turtle' ),
		'ruby'                     => array( 'clike' ),
		'sass'                     => array( 'css' ),
		'scala'                    => array( 'java' ),
		'scss'                     => array( 'css' ),
		'sh-session'               => array( 'bash' ),
		'shell-session'            => array( 'bash' ),
		'shellsession'             => array( 'bash' ),
		'smarty'                   => array( 'markup-templating' ),
		'sol'                      => array( 'clike' ),
		'solidity'                 => array( 'clike' ),
		'soy'                      => array( 'markup-templating' ),
		'sparql'                   => array( 'turtle' ),
		'sqf'                      => array( 'clike' ),
		'squirrel'                 => array( 'clike' ),
		'stata'                    => array( 'mata', 'java', 'python' ),
		't4'                       => array( 't4-templating', 'csharp' ),
		't4-cs'                    => array( 't4-templating', 'csharp' ),
		't4-vb'                    => array( 't4-templating', 'vbnet' ),
		'tap'                      => array( 'yaml' ),
		'textile'                  => array( 'markup' ),
		'ts'                       => array( 'javascript' ),
		'tsx'                      => array( 'jsx', 'typescript' ),
		'tt2'                      => array( 'clike', 'markup-templating' ),
		'twig'                     => array( 'markup-templating' ),
		'typescript'               => array( 'javascript' ),
		'v'                        => array( 'clike' ),
		'vala'                     => array( 'clike' ),
		'vbnet'                    => array( 'basic' ),
		'velocity'                 => array( 'markup' ),
		'wiki'                     => array( 'markup' ),
		'xeora'                    => array( 'markup' ),
		'xeoracube'                => array( 'markup' ),
		'xml-doc'                  => array( 'markup' ),
		'xquery'                   => array( 'markup' ),
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
						$style_url[] = 'https://cdn.jsdelivr.net/npm/prismjs@' . $this->prism_version . '/plugins/line-numbers/prism-line-numbers.css';
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
			default:
				return false;
		}
	}
}
