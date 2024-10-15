<?php

namespace App\Services;

class OrderFormatService
{
    public function checkAndTransform(array $orderData)
    {
        if ($orderData['currency'] === 'USD') {
            $orderData['price'] = $this->convertPriceToTWD($orderData['price']);
            $orderData['currency'] = 'TWD';
        }

        return $orderData;
    }

    private function convertPriceToTWD($price)
    {
        $exchangeRate = 31;
        return round($price * $exchangeRate);
    }
}
