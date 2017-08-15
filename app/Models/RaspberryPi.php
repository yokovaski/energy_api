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

    /**
     * A Raspberry Pi belongs to one User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * A Raspberry Pi has many Ten Second Metrics
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tenSecondMetrics()
    {
        return $this->hasMany('App\Models\TenSecondMetric');
    }

    /**
     * A Raspberry Pi has many Minute Metrics
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function minutedMetrics()
    {
        return $this->hasMany('App\Models\MinuteMetric');
    }

    /**
     * A Raspberry Pi has many Hour Metrics
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function hourMetrics()
    {
        return $this->hasMany('App\Models\HourMetric');
    }

    /**
     * A Raspberry Pi has many Day Metrics
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dayMetrics()
    {
        return $this->hasMany('App\Models\DayMetric');
    }

    /**
     * A Raspberry Pi has many Day Metrics
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function raspberryPiCommand()
    {
        return $this->hasOne('App\Models\RaspberryPiCommand');
    }

    /**
     * A Raspberry Pi has many Raspberry Pi Errors
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function raspberryPiError()
    {
        return $this->hasMany('App\Models\RaspberryPiError');
    }
}
