<?php

public class


public function getCacheSeries($series)
{
    return $series
        ? ['series cacheadas' => $series]
        : 'Series no encontradas';
}
