<?php

namespace IndianIra;

use Illuminate\Notifications\Notifiable;
use IndianIra\Utilities\FormatsDateAndTime;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes, FormatsDateAndTime;

    /**
     * The dates that will be mutated to Carbon instance.
     *
     * @var array
     */
    protected $dates = [
        'deleted_at', 'verified_on',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name',
        'username', 'email', 'password',

        'verification_token', 'is_verified', 'verified_on',

        'contact_number',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get the full name of the user.
     *
     * @return  string
     */
    public function getFullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Check whether the user is verified on.
     *
     * @return  boolean
     */
    public function isVerified()
    {
        return $this->is_verified == true &&
                $this->verification_token == null &&
                $this->verified_on != null;
    }
}
