(function($) {
    google.load('visualization', '1', {packages: ['corechart']});

    var formatFunctions = {
        formatHistoryData: function(data) {
            var history = data.map(function(element) {
                var values = element.slice(1).map(function(value) {
                    return parseFloat(value);
                });
                values.unshift(element[0]);

                return values;
            });

            return history;
        },

        formatVolumeData: function(data) {
            var volume = data.map(function(element) {
                return [element[0], parseFloat(element[1])];
            });

            return volume;
        },

        formatSMAData: function(data) {
            var sma = data.map(function(element) {
                return [element[0], parseFloat(element[1])];
            });

            return sma;
        }
    };

    var visualizationFunctions = {
        historyVisualization: function(data) {
            var options = {
                legend:'none',
                width: $('#history-form').width(),
                height: $('#history-form').width() / 3,
                backgroundColor: {fill: 'transparent'},
                hAxis: {slantedText: true},
            };

            var display = $('#History-display');
            var visualization = new google.visualization.CandlestickChart(display.get(0));
            visualization.draw(data, options);
            display.prepend('<span>Price History</span>');
        },

        volumeVisualization: function(data) {
            var options = {
                legend:'none',
                width: $('#history-form').width(),
                height: $('#history-form').width() / 3,
                backgroundColor: {fill: 'transparent'},
                hAxis: {slantedText: true}
            };

            var display = $('#Volume-display');
            var visualization = new google.visualization.ColumnChart(display.get(0));
            visualization.draw(data, options);
            display.prepend('<span>Volume</span>');
        },

        smaVisualization: function(data) {
            var options = {
                legend:'none',
                width: $('#history-form').width(),
                height: $('#history-form').width() / 3,
                backgroundColor: {fill: 'transparent'},
                hAxis: {slantedText: true}
            };

            var display = $('#SMA-display');
            var visualization = new google.visualization.LineChart(display.get(0));
            visualization.draw(data, options);
            display.prepend('<span>Simple Moving Average</span>');
        }
    };

    function setupVisualization(name, data) {
        data = google.visualization.arrayToDataTable(chartData, true);
        visualizationFunctions[name.toLowerCase() + 'Visualization'](data);
    }

    function format(name, data) {
        return formatFunctions['format' + name + 'Data'](data);
    }

    function displayVisualization(data) {
        $('#charts').empty();
        for (var chart in data) {
            $('#charts').hide();
            $('#charts').append('<div id="' + chart + '-display" class="well"></div>');

            chartData = format(chart, data[chart]);
            setupVisualization(chart, chartData);

            $('#charts').fadeIn();
        }
    }

    function submitHistoryForm() {
        var quoteUrl = $(this).attr('action');
        var data = $(this).serialize();

        $.ajax({
            type: $(this).attr('method'),
            url: quoteUrl,
            data: data,
            dataType: 'json',
            success: displayVisualization
        });

        return false;
    }

    //google.setOnLoadCallback(setupVisualization);

    $(function() {
        $('.datepicker').datepicker();
        $('#history-form').submit(submitHistoryForm);
        $('#charts').sortable();
        $('#charts').disableSelection();
    });
})(jQuery)
