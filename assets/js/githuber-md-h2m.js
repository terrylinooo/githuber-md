(function($) {
    $(function() {

        var h2m = window.h2m_config;
        var h2m_strip_tags = $('input[name=h2m_strip_tags]:checked').val();
        var h2m_line_break = $('input[name=h2m_line_break]:checked').val();

        $('#btn-html2markdown').click(function() {

            var h2m_post_content = githuber_md_editor.getValue();

            $.ajax({
                url: h2m.ajax_url,
                type: 'post',
                dataType: 'json',
                data: {
                    action: 'githuber_html2markdown',
                    strip_tags: h2m_strip_tags,
                    line_break: h2m_line_break,
                    post_id: h2m.post_id,
                    post_content: h2m_post_content
                },
                success: function(data) {
                    if (data.success) {
                        githuber_md_editor.setValue(data.result);
                    }
                }
            });
        });
    });
})(jQuery);