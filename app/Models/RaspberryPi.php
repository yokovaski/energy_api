<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RaspberryPi extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ip_address', 'name', 'mac_address',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function tenSecondMetrics()
    {
        return $this->hasMany('App\Models\TenSecondMetric');
    }

    public function minutedMetrics()
    {
        return $this->hasMany('App\Models\MinuteMetric');
    }

    public function hourMetrics()
    {
        return $this->hasMany('App\Models\HourMetric');
    }

    public function dayMetrics()
    {
        return $this->hasMany('App\Models\DayMetric');
    }
}
