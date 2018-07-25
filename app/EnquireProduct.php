<?php

namespace IndianIra;

use Illuminate\Database\Eloquent\Model;
use IndianIra\Utilities\FormatsDateAndTime;
use Illuminate\Database\Eloquent\SoftDeletes;

class EnquireProduct extends Model
{
    use SoftDeletes, FormatsDateAndTime;

    /**
     * The dates that will be mutated to Carbon instance.
     *
     * @var  array
     */
    protected $dates = [
        'deleted_at'
    ];

    /**
     * The attributes that are mass assignable
     *
     * @var  array
     */
    protected $fillable = [
        'code',
        'product_id', 'product_code',
        'option_id', 'option_code',
        'product_name', 'product_image', 'product_page_url',

        'user_full_name', 'user_email', 'user_contact_number',

        'message_body',
    ];
}
