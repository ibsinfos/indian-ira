<?php

namespace IndianIra;

use IndianIra\Utilities\Directories;
use Illuminate\Database\Eloquent\Model;
use IndianIra\Utilities\FormatsDateAndTime;

class ProductPriceAndOption extends Model
{
    use FormatsDateAndTime, Directories;

    /**
     * The attributes that are mass assignable.
     *
     * @var  array
     */
    protected $fillable = [
        'product_id',
        'option_code',
        'option_1_heading', 'option_1_value', 'option_2_heading', 'option_2_value',
        'selling_price', 'discount_price',
        'stock', 'weight', 'display',
        'image',
    ];

    /**
     * A price and option belongs to a single product.
     *
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the cart image of the product.
     *
     * @return  string
     */
    public function cartImage()
    {
        $images = explode('; ', $this->image);
        $images = collect($images)->filter();

        if (
            $images->isNotEmpty() &&
            \Illuminate\Support\Facades\File::exists($this->getPublicPath() . $images[0])
        ) {
            return $images[0];
        }

        return 'images/no-image.jpg';
    }

    /**
     * Scope the query to fetch only the Enabled Price and Option.
     *
     * @param   \Illuminate\Database\Eloquent\Builder  $query
     * @return  \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOnlyEnabled($query)
    {
        return $query->whereDisplay('Enabled');
    }

    /**
     * Get the zoomed image of the product.
     *
     * @return  string
     */
    public function zoomedImage()
    {
        $images = explode('; ', $this->image);
        $images = collect($images)->filter();

        if (
            $images->isNotEmpty() &&
            \Illuminate\Support\Facades\File::exists($this->getPublicPath() . $images[2])
        ) {
            return $images[2];
        }

        return 'images/no-image-zoomed.png';
    }

    /**
     * Check whether product's image exists.
     *
     * @return boolean
     */
    public function hasUploadedImageFile()
    {
        return ($this->image != null || $this->image != '');
    }
}
