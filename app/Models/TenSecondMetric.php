<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenSecondMetric extends Model
{
    public function raspberryPi()
    {
        return $this->belongsTo('App\Models\RaspberryPi');
    }
}
