<?php

namespace IndianIra;

use Illuminate\Database\Eloquent\Model;
use IndianIra\Utilities\FormatsDateAndTime;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes, FormatsDateAndTime;

    /**
     * The attributes that will be mutated to Carbon instance.
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
        'code', 'name', 'number_of_options', 'gst_percent',
        'description', 'additional_notes', 'terms',
        'meta_title', 'meta_description', 'meta_keywords',
        'display', 'images',
    ];

    /**
     * A product belongs to multiple categories.
     *
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }

    /**
     * Get the cart image of the product.
     *
     * @return  string
     */
    public function cartImage()
    {
        $images = explode('; ', $this->images);
        $images = collect($images)->filter();

        if ($images->isNotEmpty()) {
            return $images[0];
        }

        return 'images/no-image.jpg';
    }
}
