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

    /**
     * Get the address.
     *
     * @return  string
     */
    public function getCompleteAddress()
    {
        $address = $this->address_line_1 . ', <br />';
        if ($this->address_line_2 != null || $this->address_line_2 != '') {
            $address .= $this->address_line_2 . ', <br />';
        }
        $address .= $this->area . ', <br />';
        $address .= $this->city . ' - ';
        $address .= $this->pin_code . ', <br />';
        $address .= $this->state . ', ';
        $address .= $this->country;

        if ($this->landmark != null || $this->landmark != '') {
            $address .= '.<br /><br />Landmark: '. $this->landmark;
        }

        return $address;
    }
}
