(function($) {

    $(function() {

        var setting = window.md_frontend_settings;

        if (setting.link_opening_method == '_blank') {

            $('.post a').each(function() {
                var link_href = $(this).attr('href');
                if (link_href.indexOf('#') == -1) {
                    $(this).attr('target', '_blank');
                }
            });
        }

        if (setting.copy_to_clipboard == 'yes') {

            (function(){

                // Get the elements.
                // - the 'pre' element.
                // - the 'div' with the 'paste-content' id.

                var pre = document.getElementsByTagName('pre');
                var pasteContent = document.getElementById('paste-content');

                // Add a copy button in the 'pre' element.
                // which only has the className of 'language-'.
                var hasLanguage = false;

                for (var i = 0; i < pre.length; i++) {
                    var codeClass = pre[i].children[0].className;
                    var isLanguage = codeClass.indexOf('language-');

                    var excludedCodeClassNames = [
                        'language-katex',
                        'language-seq',
                        'language-sequence',
                        'language-flow',
                        'language-flowchart',
                        'language-mermaid',
                    ];

                    var isExcluded = excludedCodeClassNames.indexOf(codeClass);

                    if (isExcluded !== -1) {
                        isLanguage = -1;
                    }

                    if (isLanguage !== -1) {
                        var button = document.createElement('button');
                        button.className = 'copy-button';
                        button.textContent = 'Copy';
    
                        pre[i].appendChild(button);
                        hasLanguage = true;
                    }
                };

                // Run Clipboard
                if (hasLanguage) {
                    var copyCode = new ClipboardJS('.copy-button', {
                        target: function(trigger) {
                            return trigger.previousElementSibling;
                        }
                    });
    
                    // On success:
                    // - Change the "Copy" text to "Copied".
                    // - Swap it to "Copy" in 2s.
    
                    copyCode.on('success', function(event) {
                        event.clearSelection();
                        event.trigger.textContent = 'Copied';
                        window.setTimeout(function() {
                            event.trigger.textContent = 'Copy';
                        }, 2000);
                    });

                    // On error (Safari):
                    // - Change the  "Press Ctrl+C to copy"
                    // - Swap it to "Copy" in 2s.
    
                    copyCode.on('error', function(event) { 
                        event.trigger.textContent = 'Press "Ctrl + C" to copy';
                        window.setTimeout(function() {
                            event.trigger.textContent = 'Copy';
                        }, 5000);
                    });
                }
            })();
        }
    });

 })(jQuery);
 


