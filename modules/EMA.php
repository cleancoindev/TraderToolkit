<?php

class EMA extends MovingAverage
{
    function __construct($period = 10)
    {
        parent::__construct('trader_ema', $period);
    }
}

?>
