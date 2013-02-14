<?php

class YahooFinanceNews
{
    const URI = 'http://feeds.finance.yahoo.com/rss/2.0/headline';

    public static function getNews($quote)
    {
        $params = array(
            's' => $quote, 
            'region' => 'US', 
            'lang' => 'en-US'
        );

        $query = self::URI . '?' . http_build_query($params);

        $rss = file_get_contents($query);

        $xml = new SimpleXMLElement($rss);

        $news = array();
        foreach ($xml->channel->item as $item) {
            $news[] = array($item->pubDate, $item->title, $item->description, $item->link);
        }

        return $news;
    }
}

?>
