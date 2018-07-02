<?php

namespace IndianIra;

use Illuminate\Database\Eloquent\Model;
use IndianIra\Utilities\FormatsDateAndTime;
use Illuminate\Database\Eloquent\SoftDeletes;

class Carousel extends Model
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
        'name', 'display', 'short_description',
    ];

    /**
     * A carousel belongs to multiple products.
     *
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany(Product::class)->withTimestamps();
    }
}
