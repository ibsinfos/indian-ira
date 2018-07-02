<?php

namespace IndianIra;

use IndianIra\Utilities\Directories;
use Illuminate\Database\Eloquent\Model;
use IndianIra\Utilities\FormatsDateAndTime;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes, FormatsDateAndTime, Directories;

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
     * A product belongs to multiple tags.
     *
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    /**
     * A product has many prices and options.
     *
     * @return  \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function options()
    {
        return $this->hasMany(ProductPriceAndOption::class);
    }

    /**
     * A product belongs to multiple carousels.
     *
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function carousels()
    {
        return $this->belongsToMany(Carousel::class)->withTimestamps();
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

        if (
            $images->isNotEmpty() &&
            \Illuminate\Support\Facades\File::exists($this->getPublicPath() . $images[0])
        ) {
            return $images[0];
        }

        return 'images/no-image.jpg';
    }

    /**
     * Check whether products detailed information section contains any data.
     * Specifically, it should contain the description as additional notes and
     * terms are optional fields.
     *
     * @return boolean
     */
    public function hasFilledDetailedInformation()
    {
        return ($this->description != null || $this->description != '');
    }

    /**
     * Check whether products meta information section contains any data.
     * Specifically, it should contain the meta title and meta description
     * as meta keywords is an optional field.
     *
     * @return boolean
     */
    public function hasFilledMetaInformation()
    {
        return ($this->meta_title != null || $this->meta_title != '') &&
                ($this->meta_description != null || $this->meta_description != '');
    }

    /**
     * Check whether product's image exists.
     *
     * @return boolean
     */
    public function hasUploadedImageFile()
    {
        return ($this->images != null || $this->images != '');
    }
}
