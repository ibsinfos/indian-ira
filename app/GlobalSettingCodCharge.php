<?php

namespace IndianIra;

use Illuminate\Database\Eloquent\Model;

class GlobalSettingCodCharge extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var  array
     */
    protected $fillable = [
        'amount',
    ];
}
