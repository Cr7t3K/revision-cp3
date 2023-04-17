<?php

namespace App\Service;

class TaxCalculator
{
    public function calculate(float $priceHT, float $tva): float
    {
        $priceTTC = $priceHT * (1 + $tva / 100);

        return $priceTTC;
    }

}