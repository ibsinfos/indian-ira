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
}