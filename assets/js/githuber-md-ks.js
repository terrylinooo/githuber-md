(function($) {
    $(function() {
        var ks = window.ks_config;

        $('#btn-keyword-suggestion-query').on('click', function () {
            $.ajax({
                    url: ks.ajax_url,
                    type: 'get',
                    dataType: 'json',
                    data: {
                    action: 'githuber_keyword_suggestion',
                    post_id: ks.post_id,
                    keyword: $('input[name=ks_keyword]').val(),
                    _wpnonce: $('input[name=ks_nonce]').val()
                }
            }).done(function (data) {
                if (data && data.success && Array.isArray(data.result)) {
                    const $box = $('#display-keyword-suggestion').empty();
                    data.result.forEach(function (word) {

                        $('<span>', { class: 'githuber-md-keyword', text: word }).appendTo($box);
                    });
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