<?php

class MovingAverage implements TechnicalIndicator
{
    private $_period;
    private $_movingAverageFunction;

    function __construct($movingAverageFunction, $period)
    {
        $this->_movingAverageFunction = $movingAverageFunction;
        $this->_period = $period;
    }

    public function generate($timeSeries)
    {
        $timeSlice = array_slice($timeSeries, $this->_period - 1);

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
        return array_map(function($timeEntry, $ma) {
            return array($timeEntry, $ma);
        }, $this->_getDates($timeSlice), $this->_getMA($timeSeries));
    }

    private function _getDates($timeSeries)
    {
        return array_map(function($timeEntry) {
            return $timeEntry[0];
        }, $timeSeries);
    }

    private function _getMA($timeSeries)
    {
        return call_user_func($this->_movingAverageFunction, 
            array_map(function($timeEntry) {
                return $timeEntry[4];
            }, $timeSeries), 
            $this->_period
        );
    }

    public function getOffset()
    {
        return $this->_period;
    }
}

?>
