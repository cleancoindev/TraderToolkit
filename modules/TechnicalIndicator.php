<?php

interface TechnicalIndicator
{
    public function getOffset();

    public function generate($timeSeries);
}

?>
