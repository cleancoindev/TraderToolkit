<?php

class MovingAverage extends TechnicalIndicator
{
    private $movingAverageFunction;

    function __construct($movingAverageFunction, $period)
    {
        $this->movingAverageFunction = $movingAverageFunction;
        $this->period = $period;
    }

    public function generate($timeSeries)
    {
        $timeSlice = array_slice($timeSeries, $this->period - 1);

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
        return call_user_func($this->movingAverageFunction, 
            array_map(function($timeEntry) {
                return $timeEntry[4];
            }, $timeSeries), 
            $this->period
        );
    }
}

?>
