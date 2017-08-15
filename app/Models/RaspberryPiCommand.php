<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RaspberryPiCommand extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'shutdown', 'restart', 'update'
    ];

    /**
     * A Raspberry Pi Command belongs to one Raspberry Pi
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function raspberryPi()
    {
        return $this->belongsTo('App\Models\RaspberryPi');
    }
}
