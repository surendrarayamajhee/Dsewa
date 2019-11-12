<?php
namespace App\Helpers;

use App\Order;

trait TrackingId

{
    public function generateid()
    {
        $trakingid = substr(md5(uniqid(rand(), true)), 0, 16);
        if ($this->trackingidexit($trakingid)) {
            return $this->generateid();
        }

        return $trakingid;
    }
    function trackingidexit($trakingid)
    {
        return Order::where('tracking_id', $trakingid)->exists();
    }
}
