<?php

namespace IndianIra;

use Illuminate\Database\Eloquent\Model;

class GlobalSettingPaymentOption extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var  array
     */
    protected $fillable = [
        'chosen',
    ];
}
