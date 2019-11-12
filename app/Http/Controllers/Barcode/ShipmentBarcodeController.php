<?php

namespace App\Http\Controllers\Barcode;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use CodeItNow\BarcodeBundle\Utils\BarcodeGenerator;
use App\ShipmentSent;
   


class BarcodeController extends Controller
{

    
    //
   public function getbarcode($id)
    {

         $shipments = ShipmentSent::findOrfail($id);

        $barcode = new BarcodeGenerator();
        $barcode->setText(strval($shipments->barcode));
        $barcode->setType(BarcodeGenerator::Code128);
        $barcode->setScale(3);
        $barcode->setThickness(40);
        $barcode->setFontSize(10);
       $barcode->setLabel($shipments->barcode);
        $code = $barcode->generate();
        return view('Barcode.barcode',compact('code','shipments'));
    }
}
