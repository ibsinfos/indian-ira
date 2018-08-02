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
        'stock', 'sort_number', 'weight', 'display',
        'image',
        'gallery_image_1', 'gallery_image_2', 'gallery_image_3',
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
    public function cartImage($galleryField = null)
    {
        $images = explode('; ', $this->image);
        if ($galleryField != null && $this->$galleryField != null) {
            $images = explode('; ', $this->$galleryField);
        }
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
     * Get the catalogue image of the product's option.
     *
     * @return  string
     */
    public function catalogueImage()
    {
        $images = explode('; ', $this->image);
        $images = collect($images)->filter();

        if (
            $images->isNotEmpty() &&
            \Illuminate\Support\Facades\File::exists($this->getPublicPath() . $images[1])
        ) {
            return $images[1];
        }

        return 'images/no-image-catalogue.png';
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
    public function zoomedImage($galleryField = null)
    {
        $images = explode('; ', $this->image);
        if ($galleryField != null && $this->$galleryField != null) {
            $images = explode('; ', $this->$galleryField);
        }
        $images = collect($images)->filter();

        if (
            $images->isNotEmpty() &&
            \Illuminate\Support\Facades\File::exists($this->getPublicPath() . $images->last())
        ) {
            return $images->last();
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
