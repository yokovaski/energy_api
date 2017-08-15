<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RaspberryPiError extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'message', 'endpoint', 'data_send'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function raspberryPi()
    {
        return $this->belongsTo('App\RaspberryPi');
    }
}
