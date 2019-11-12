<?php
namespace App\Helpers;

use App\Order;

trait Barcode
{
    function generateBarcodeNumber()
    {
        $number = mt_rand(1000000000, mt_getrandmax());
        if ($this->barcodeNumberExists($number)) {
            return $this->generateBarcodeNumber();
        }

        return $number;
    }
    function barcodeNumberExists($number)
    {
        return Order::where('bar_code', $number)->exists();
    }
}
