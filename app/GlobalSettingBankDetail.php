<?php

namespace IndianIra;

use Illuminate\Database\Eloquent\Model;

class GlobalSettingBankDetail extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account_holder_name', 'account_type', 'account_number',
        'bank_name', 'bank_branch_and_city', 'bank_ifsc_code',
    ];
}
