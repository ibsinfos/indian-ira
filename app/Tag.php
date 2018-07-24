<?php

namespace IndianIra;

use Illuminate\Database\Eloquent\Model;
use IndianIra\Utilities\FormatsDateAndTime;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
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
        'name', 'slug', 'short_description',
        'meta_title', 'meta_description', 'meta_keywords',
    ];

    /**
     * A tag belongs to multiple products.
     *
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function products()
    {
        return $this->belongsToMany(Product::class)->withTimestamps();
    }

    /**
     * The page url of the tag.
     *
     * @return  string
     */
    public function pageUrl()
    {
        return '/products-tagged/'. $this->slug;
    }

    /**
     * The product page url for the tag.
     *
     * @param   \IndianIra\Product  $product
     * @return  string
     */
    public function productPageUrl($product)
    {
        if (! $product) {
            return 'javascript:void(0)';
        }

        return '/products-tagged/'. $this->slug . '/'. $product->code;
    }
}
