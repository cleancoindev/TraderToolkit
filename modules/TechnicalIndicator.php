<?php

abstract class TechnicalIndicator
{
    protected $period;

    public function getPeriod()
    {
        return $this->period;
    }

    abstract public function generate($timeSeries);
}

?>
