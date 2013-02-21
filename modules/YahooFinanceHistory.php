<?php

class YahooFinanceHistory
{
    const URI = 'http://ichart.yahoo.com/table.csv';

    public static function getHistory($quote, $startDate, $endDate, $interval) {
        list($startMonth, $startDay, $startYear) = array_map(function($f) use ($startDate) {
            return date($f, strtotime($startDate));
        }, array('n', 'j', 'Y'));

        list($endMonth, $endDay, $endYear) = array_map(function($f) use ($endDate) {
            return date($f, strtotime($endDate));
        }, array('n', 'j', 'Y'));

        return self::_getHistory(
            $quote,
            $startMonth - 1,
            $startDay,
            $startYear,
            $endMonth - 1,
            $endDay,
            $endYear,
            $interval
        );
    }

    private static function _getHistory()
    {
        $args   = func_get_args();
        $params = array('s', 'a', 'b', 'c', 'd', 'e', 'f', 'g');

        $query = self::URI . '?' . http_build_query(array_combine($params, $args)) . '&ignore=.csv';

        $result = file_get_contents($query);

        $history = array_map('str_getcsv', str_getcsv($result, "\n"));
        array_shift($history);

        return array_reverse($history);
    }
}

?>
