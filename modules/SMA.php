<?php

class SMA extends MovingAverage
{
    function __construct($period = 10)
    {
        parent::__construct('trader_sma', $period);
    }
}

?>
