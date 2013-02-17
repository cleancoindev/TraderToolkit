(function($) {
    var history = [[]];

    google.load('visualization', '1', {packages: ['corechart']});

    $('#history-display').hide();

    function drawVisualization() {
        var data = google.visualization.arrayToDataTable(history, true);

        options = {
            legend:'none',
            width: $('#history-form').width(),
            height: $('#history-form').width() / 3,
            backgroundColor: {fill: 'transparent'},
            seriesType: 'candlesticks'
        };

        chart = new google.visualization.ComboChart(document.getElementById('history-display'));
        chart.draw(data, options);
    }

    google.setOnLoadCallback(drawVisualization);

    $(function() {
        $('.datepicker').datepicker();
        $('#history-form').submit(function() {
            var quoteUrl = $(this).attr('action');
            var data = $(this).serialize();

            $.ajax({
                type: $(this).attr('method'),
                url: quoteUrl,
                data: data,
                dataType: 'json',
                success: function(data) {
                    $('#history-display').fadeIn();
                    history = data.map(function(element) {
                        var values = element.slice(1, 5).map(function(value) {
                            return parseInt(value);
                        });
                        values.unshift(element.shift());
                        return values;
                    });

                    history.shift();
                    history.reverse();

                    history = google.visualization.arrayToDataTable(history, true);
                    chart.draw(history, options);
                }
            });

            return false;
        });
    });
})(jQuery)
