<?php

namespace IndianIra;

use Illuminate\Database\Eloquent\Model;
use IndianIra\Utilities\FormatsDateAndTime;

class UserBillingAddress extends Model
{
    use FormatsDateAndTime;

    /**
     * The attributes that are mass assignable.
     *
     * @var  array
     */
    protected $fillable = [
        'user_id',
        'address_line_1', 'address_line_2', 'area', 'landmark',
        'city', 'pin_code', 'state', 'country',
    ];

    /**
     * A Billing Address belongs to a single user.
     *
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
