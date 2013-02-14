(function($) {
    $('#quote-display').hide();

    $(function() {
        $('#quote-form').submit(function() {
            quoteUrl = $(this).attr('action');
            data = $(this).serialize();

            $.ajax({
                type: $(this).attr('method'),
                url: quoteUrl,
                data: data,
                dataType: 'json',
                success: function(data) {
                    $('#quote-display').fadeIn();
                    $('#stock').text(data[0][0] + ' (' + data[0][1] + ')');
                    $('#latest').text(data[0][2]);
                    $('#open').text(data[0][3]);
                    $('#close').text(data[0][4]);
                }
            });

            //event.preventDefault();
            return false;
        });
    });
})(jQuery)
