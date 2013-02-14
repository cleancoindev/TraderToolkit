(function($) {
    $('#news-display').hide();

    $(function() {
        $('#news-form').submit(function() {
            quoteUrl = $(this).attr('action');
            data = $(this).serialize();

            $.ajax({
                type: $(this).attr('method'),
                url: quoteUrl,
                data: data,
                dataType: 'json',
                success: function(data) {
                    $('#news-display').empty();
                    $('#news-display').fadeIn();
                    $.each(data, function(index, value) {
                        $('#news-display').append(
                            '<h3>' + value[1][0] + '</h3>'
                            + '<h5>' + value[0][0] + '</h5>'
                            + '<p>' + value[2][0] + '</p>'
                            + '<a href="' + value[3][0] + '">' 
                            + value[3][0] + '</a>'
                        );
                    });
                }
            });

            return false;
        });
    });
})(jQuery)
