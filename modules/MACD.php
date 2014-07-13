<?php

class MACD implements TechnicalIndicator
{
    private $_fastPeriod;
    private $_slowPeriod;
    private $_signalPeriod;

    function __construct($fastPeriod = 12, $slowPeriod = 26, $signalPeriod = 9)
    {
        $this->_fastPeriod   = $fastPeriod;
        $this->_slowPeriod   = $slowPeriod;
        $this->_signalPeriod = $signalPeriod;
    }

    public function generate($timeSeries)
    {
        $timeSlice = array_slice($timeSeries, $this->_slowPeriod - 1);

        return $this->_formatOutput($timeSlice, $timeSeries);
    }

    private function _formatOutput($timeSlice, $timeSeries)
    {
        return array_filter(
            $this->_generate($timeSlice, $timeSeries), 
            function($value) {
                return count($value) > 0 && $value[0];
            }
        );
    }

    private function _generate($timeSlice, $timeSeries)
    {
        return array_map(function($timeEntry, $macd) {
            return array($timeEntry, $macd);
        }, $this->_getDates($timeSlice), $this->_getMACD($timeSeries));
    }

    private function _getDates($timeSeries)
    {
        return array_map(function($timeEntry) {
            return $timeEntry[0];
        }, $timeSeries);
    }

    private function _getMACD($timeSeries)
    {
        return $this->_formatMACDOutput(trader_macd(
            array_map(function($timeEntry) {
                return $timeEntry[4];
            }, $timeSeries), 
            $this->_fastPeriod,
            $this->_slowPeriod,
            $this->_signalPeriod
        ));
    }

    private function _formatMACDOutput($macd)
    {
        if (count($macd) < 3) {
            return false;
        }

        return array_map(function($slow, $fast, $signal) {
            return array($slow, $fast, $signal);
        }, $macd[0], $macd[1], $macd[2]);
    }

    public function getOffset()
    {
        return $this->_slowPeriod;
    }
}

?>
