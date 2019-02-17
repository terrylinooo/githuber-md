(function($) {
    $(function() {

        $('#btn-markdown-per-post').click(function() {
            var md_per_post = window.markdown_per_post_config;
            var markdown_per_post = $('input[name=markdown_per_post]:checked').val();

            $.ajax({
                url: md_per_post.ajax_url,
                type: 'post',
                dataType: 'json',
                data: {
                    action: 'githuber_markdown_per_post',
                    post_id: md_per_post.post_id,
                    markdown_per_post: markdown_per_post
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