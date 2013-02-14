<?php

class YahooFinanceQuotes
{
    const URI = 'http://download.finance.yahoo.com/d/quotes.csv';

    const DEFAULT_PROPERTIES = 'nsl1op';

    public static function getQuotes($quotes, $properties = null)
    {
        $params = array(
            's' => implode(',', array_map('urlencode', $quotes)), 
            'f' => $properties ?: self::DEFAULT_PROPERTIES,
            'e' => '.csv'
        );

        $query = self::URI . '?' . http_build_query($params);

        $result = file_get_contents($query);

        return array_map('str_getcsv', str_getcsv($result, "\n"));
    }
}

?>
