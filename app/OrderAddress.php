<?php

namespace IndianIra;

use Illuminate\Database\Eloquent\Model;

class OrderAddress extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var  array
     */
    protected $fillable = [
        'order_id',
        'order_code',

        'user_id', 'full_name',
        'address_line_1', 'address_line_2', 'area', 'landmark',
        'city', 'pin_code', 'state', 'country',

        'shipping_same_as_billing', 'shipping_full_name',
        'shipping_address_line_1', 'shipping_address_line_2', 'shipping_area', 'shipping_landmark',
        'shipping_city', 'shipping_pin_code', 'shipping_state', 'shipping_country',
    ];
}
