<?php

namespace IndianIra;

use Illuminate\Database\Eloquent\Model;

class GlobalSettingCompanyAddress extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var  array
     */
    protected $fillable = [
        'address_line_1', 'address_line_2', 'area', 'landmark',
        'city', 'pin_code', 'state', 'country',
    ];
}
