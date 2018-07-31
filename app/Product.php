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
        'code', 'name', 'number_of_options', 'sort_number', 'gst_percent',
        'description', 'additional_notes', 'terms',
        'meta_title', 'meta_description', 'meta_keywords',
        'display', 'images',
        'gallery_image_1', 'gallery_image_2', 'gallery_image_3',
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
     * A product belongs to multiple products.
     *
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function interRelated()
    {
        return $this->belongsToMany(Product::class, 'interrelated_products', 'product_id', 'related_product_id')
                    ->withTimestamps();
    }

    /**
     * Scope the query to fetch only the Enabled Products.
     *
     * @param   \Illuminate\Database\Eloquent\Builder  $query
     * @return  \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOnlyEnabled($query)
    {
        return $query->whereDisplay('Enabled');
    }

    /**
     * Check whether the product exists in the given user's wishlist.
     *
     * @param   \IndianIra\User  $user
     * @return  boolean
     */
    public function existsInWishlist($user)
    {
        if ($user->wishlist->where('product_id', $this->id)->first() != null) {
            return true;
        }

        return false;
    }

    /**
     * Get the cart image of the product.
     *
     * @return  string
     */
    public function cartImage($galleryField = null)
    {
        $images = explode('; ', $this->images);
        if ($galleryField != null && $this->$galleryField != null) {
            $images = explode('; ', $this->$galleryField);
        }
        $images = collect($images)->filter();

        if (
            $images->isNotEmpty() &&
            \Illuminate\Support\Facades\File::exists($this->getPublicPath() . $images->first())
        ) {
            return $images->first();
        }

        return 'images/no-image.jpg';
    }

    /**
     * Get the catalogue image of the product.
     *
     * @return  string
     */
    public function catalogueImage()
    {
        $images = explode('; ', $this->images);
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
     * Get the zoomed image of the product.
     *
     * @return  string
     */
    public function zoomedImage($galleryField = null)
    {
        $images = explode('; ', $this->images);
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

    /**
     * Get the page url of the given category. If the category is not provided
     * then go with the Canonical page url.
     *
     * @param   \IndianIra\Category  $category
     * @return  string
     */
    public function pageUrl($category = null)
    {
        if ($category == null || func_num_args() <= 0) {
            return $this->canonicalPageUrl();
        }

        return $category->pageUrl()
                .'/products/'
                .$this->code.'/'.str_slug($this->name);
    }

    /**
     * Get the canonical page url of the product.
     *
     * @return  string
     */
    public function canonicalPageUrl()
    {
        $category = $this->categories->first();

        if ($category->first() == null) {
            return 'javascript:void(0)';
        }

        return $category->first()->pageUrl()
                .'/products/'
                .$this->code.'/'.str_slug($this->name);
    }
}
