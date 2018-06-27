<?php

namespace IndianIra;

use Illuminate\Database\Eloquent\Model;
use IndianIra\Utilities\FormatsDateAndTime;

class ForgotPassword extends Model
{
    use FormatsDateAndTime;

    /**
     * The dates that will be mutated to Carbon instance.
     *
     * @var  array
     */
    protected $dates = [
        'expires_on',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var  array
     */
    protected $fillable = [
        'email', 'token', 'expires_on',
    ];
}
