<?php

class SMA extends TechnicalIndicator
{
    private $_movingAverage;

    function __construct($period = 30)
    {
        $this->period = $period;
        $this->_movingAverage = new MovingAverage('trader_sma', $period);
    }

    public function generate($timeSeries)
    {
        return $this->_movingAverage->generate($timeSeries);
    }
}

?>
