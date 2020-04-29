(function($) {
    $(function() {
        var ks = window.ks_config;

        $('#btn-keyword-suggestion-query').click(function() {
            $.ajax({
                url: ks.ajax_url,
                type: 'get',
                dataType: 'json',
                data: {
                    action: 'githuber_keyword_suggestion',
                    post_id: ks.post_id,
                    keyword: $('input[name=ks_keyword]').val(),
                    _wpnonce: $('input[name=ks_nonce]').val()
                },
                success: function(data) {
                    if (data.success) {
                        $('#display-keyword-suggestion').html(data.result);
                    }
                }
            });
        });

        $('#btn-keyword-suggestion-reset').click(function() {
            $('input[name=ks_keyword]').val('');
            $('#display-keyword-suggestion').html('');
        });

        $(document).on('click', '.githuber-md-keyword', function(event) {
            var thisKeyword = $(this).html();
            if (thisKeyword !== '') {
                githuber_md_editor.appendMarkdown(thisKeyword);
            }
        });
    });

})(jQuery);