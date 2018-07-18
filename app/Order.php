<?php

namespace IndianIra;

use Illuminate\Database\Eloquent\Model;
use IndianIra\Utilities\FormatsDateAndTime;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes, FormatsDateAndTime;

    /**
     * The dates that will be mutated to Carbon instance.
     *
     * @var  array
     */
    protected $dates = [
        'deleted_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var  array
     */
    protected $fillable = [
        'order_code',

        'user_id', 'user_full_name', 'user_username', 'user_email', 'user_contact_number',

        'product_id', 'product_code', 'product_name', 'product_cart_image', 'product_page_url',
        'product_number_of_options',

        'product_option_id', 'product_option_code',
        'product_option_1_heading', 'product_option_1_value',
        'product_option_2_heading', 'product_option_2_value',
        'product_stock', 'product_weight', 'product_quantity',

        'product_selling_price', 'product_discount_price',
        'product_net_amount', 'product_gst_amount', 'product_gst_percent', 'product_total_amount',

        'payment_method',

        'coupon_code', 'coupon_discount_percent',

        'cart_total_net_amount', 'cart_total_gst_amount', 'cart_total_shipping_amount',
        'cart_total_cod_amount', 'cart_coupon_amount', 'cart_total_payable_amount',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * An order has only 1 billing and shipping address.
     *
     * @return  \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function address()
    {
        return $this->hasOne(OrderAddress::class, 'order_code', 'order_code');
    }

    /**
     * An order has only 1 billing and shipping address.
     *
     * @return  \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function history()
    {
        return $this->hasMany(OrderHistory::class, 'order_code', 'order_code');
    }

    /**
     * An order belongs to a single User.
     *
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
