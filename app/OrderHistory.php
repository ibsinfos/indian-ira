<?php

namespace IndianIra;

use Illuminate\Database\Eloquent\Model;

class OrderHistory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var  array
     */
    protected $fillable = [
        'order_id', 'order_code',

        'user_id', 'user_full_name', 'user_email',

        'product_id', 'product_code', 'product_name',
        'product_option_id', 'product_option_code',

        'shipping_company', 'shipping_tracking_url',

        'notes',
    ];
}
