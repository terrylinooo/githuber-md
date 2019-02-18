(function($) {
    $(function() {

        $('#btn-markdown-this-post').click(function() {
            var md_this_post = window.markdown_this_post_config;
            var markdown_this_post = $('input[name=markdown_this_post]:checked').val();

            $.ajax({
                url: md_this_post.ajax_url,
                type: 'post',
                dataType: 'json',
                data: {
                    action: 'githuber_markdown_this_post',
                    post_id: md_this_post.post_id,
                    markdown_this_post: markdown_this_post
                },
                success: function(data) {
                    if (data.success) {
                        location.reload();
                    }
                }
            });
        });
    });
})(jQuery);