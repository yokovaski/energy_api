<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DayMetric extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'mode', 'usage_now', 'redelivery_now', 'solar_now', 'usage_total_high', 'redelivery_total_high',
        'usage_total_low', 'redelivery_total_low', 'solar_total', 'usage_gas_now', 'usage_gas_total',
    ];

    public function raspberryPi()
    {
        return $this->belongsTo('App\Models\RaspberryPi');
    }
}
