(function($) {
    google.load('visualization', '1', {packages: ['corechart']});

    var formatFunctions = {
        format: function(data) {
            data = data.map(function(element) {
                return [element[0], parseFloat(element[1])];
            });

            return data;
        },

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
            return formatFunctions.format(data);
        },

        formatEMAData: function(data) {
            return formatFunctions.format(data);
        },

        formatMACDData: function(data) {
            var data = data.map(function(element) {
                var values = element.slice(1).map(function(value) {
                    return parseFloat(value);
                });
                values.unshift(element[0]);

                return values;
            });

            return data;
        }
    };

    var visualizationFunctions = {
        options: {
            legend:'none',
            width: $('#history-form').width(),
            height: $('#history-form').width() / 3,
            backgroundColor: {fill: 'transparent'},
            hAxis: {slantedText: true},
        },

        historyVisualization: function(data, options) {
            $.extend(options, {
                title: 'Price History'
            });
            var display = $('#History-display');
            var visualization = new google.visualization.CandlestickChart(display.get(0));
            visualization.draw(data, options);
        },

        volumeVisualization: function(data, options) {
            $.extend(options, {
                title: 'Volume'
            });
            var display = $('#Volume-display');
            var visualization = new google.visualization.ColumnChart(display.get(0));
            visualization.draw(data, options);
        },

        smaVisualization: function(data, options) {
            $.extend(options, {
                title: 'Simple Moving Average (10 week)'
            });
            var display = $('#SMA-display');
            var visualization = new google.visualization.LineChart(display.get(0));
            visualization.draw(data, options);
        },

        emaVisualization: function(data, options) {
            $.extend(options, {
                title: 'Exponential Moving Average (10 week)'
            });
            var display = $('#EMA-display');
            var visualization = new google.visualization.LineChart(display.get(0));
            visualization.draw(data, options);
        },

        macdVisualization: function(data, options) {
            $.extend(options, {
                title: 'Moving Average Convergence/Divergence (26 week slow, 12 week fast, 9 week signal)'
            });
            var display = $('#MACD-display');
            var visualization = new google.visualization.LineChart(display.get(0));
            visualization.draw(data, options);
        }
    };

    function setupVisualization(name, data) {
        var options = visualizationFunctions.options;
        data = google.visualization.arrayToDataTable(chartData, true);
        visualizationFunctions[name.toLowerCase() + 'Visualization'](data, options);
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
