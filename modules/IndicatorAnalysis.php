<?php

class IndicatorAnalysis
{
    private $_maxPeriod;
    private $_quote;
    private $_startDate;
    private $_endDate;
    private $_indicators;

    function __construct($quote, $startDate, $endDate, $indicators = null)
    {
        $this->_maxPeriod  = 0;
        $this->_quote      = $quote;
        $this->_startDate  = $startDate;
        $this->_endDate    = $endDate;
        $this->_indicators = $indicators ?: array();
    }

    public function addIndicator($name, TechnicalIndicator $indicator)
    {
        if ($indicator->getPeriod() > $this->_maxPeriod)
        {
            $this->_maxPeriod = $indicator->getPeriod();
        }

        $this->_indicators[$name] = $indicator;

        return $this;
    }

    public function generate()
    {
        $startDate = date('Y-m-d', 
            strtotime('-' . $this->_maxPeriod . ' week', strtotime($this->_startDate))
        );

        $timeSeries = YahooFinanceHistory::getHistory($this->_quote, $startDate, $this->_endDate, 'w');

        //print_r(array($this->_maxPeriod, count($timeSeries), $startDate));
        //die();
        return $this->_formatAnalysisOutput($timeSeries, $this->_indicators, $this->_maxPeriod - 1);
    }

    private function _formatAnalysisOutput($timeSeries, $indicators, $offset)
    {
        return array_merge(
            $this->_formatHistoryOutput($timeSeries, $offset), 
            $this->_formatVolumeOutput($timeSeries, $offset),
            count($indicators) > 0 
                ? $this->_formatIndicatorOutput($indicators, $timeSeries)
                : array()
        );
    }

    private function _formatHistoryOutput($timeSeries, $offset)
    {
        return array('History' => array_map(function($timeEntry) {
            return array_slice($timeEntry, 0, 5);
        }, array_slice($timeSeries, $offset)));
    }

    private function _formatVolumeOutput($timeSeries, $offset)
    {
        return array('Volume' => array_map(function($timeEntry) {
            return array($timeEntry[0], $timeEntry[5]);
        }, array_slice($timeSeries, $offset)));
    }

    private function _formatIndicatorOutput($indicators, $timeSeries)
    {
        return array_combine(
            array_keys($indicators),
            $this->_generate($indicators, $timeSeries)
        );
    }

    private function _generate($indicators, $timeSeries)
    {
        return array_map(function($indicator) use ($timeSeries) {
            return $indicator->generate($timeSeries);
        }, $indicators);
    }
}

?>
