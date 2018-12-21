

$(function() {

    var wp_editor = 'wp-content-editor-container';
    var config = window.editormd_config;

    if ($('#' + wp_editor).length == 1) {
        testEditor = editormd(wp_editor, {
            width: '100%',
            height: 640,
            path: config.editor_modules_url,
            placeholder: config.placeholder,
            syncScrolling: (config.editor_sync_scrolling == 'yes'),
            watch: (config.editor_live_preview == 'yes'),        
            htmlDecode: (config.editor_html_decode == 'yes'),
            theme: config.editor_toolbar_theme, 
            previewTheme: 'default',
            editorTheme: config.editor_editor_theme, 
            tocContainer: (config.support_toc === 'yes') ? '' : false,
            emoji: (config.support_emoji == 'yes'),   
            tex: (config.support_latex == 'yes'),
            flowChart: (config.support_flowchart == 'yes'),  
            sequenceDiagram: (config.support_sequence_diagram == 'yes'), 
            taskList: (config.support_task_list == 'yes'),
            toolbarAutoFixed: true, 
            tocm: false, 
            tocDropdown: false,    
            atLink: false,
            imagePasteCallback: config.image_paste_callback,
            toolbarIcons: function () {
                return [
                    'undo', 'redo', '|',
                    'bold', 'del', 'italic', 'quote', '|',
                    'h1', 'h2', 'h3', 'h4', '|',
                    'list-ul', 'list-ol', 'hr', '|',
                    'link', 'reference-link', 'image', 'code', 'code-block', 'table', 'datetime', 'html-entities', 'more', 'pagebreak', config.support_emoji !== 'no' ? 'emoji' : '' + '|',
                    'watch', 'preview', 'fullscreen'
                ];
            },
            onfullscreen: function () {
                $('#' + wp_editor).css({
                    'position': 'fixed',
                    'z-index': '99999'
                })
            },

            onfullscreenExit: function () {
                $('#' + wp_editor).css({
                    'position': 'relative',
                    'z-index': 'auto'
                })
            },

            toolbarIconsClass: {
                toc: 'fa-list-alt',
                more: 'fa-ellipsis-h'
            },

            toolbarHandlers: {
                toc: function (cm, icon, cursor, selection) {
                    cm.replaceSelection('[toc]');
                },
                more: function (cm, icon, cursor, selection) {
                    cm.replaceSelection('\r\n<!--more-->\r\n');
                }
            },
            lang: {
                toolbar: {
                    toc: 'The Table Of Contents',
                    more: 'More'
                }
            }
        });
    }
});