<?php

namespace IndianIra;

use Illuminate\Database\Eloquent\Model;
use IndianIra\Utilities\FormatsDateAndTime;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes, FormatsDateAndTime;

    /**
     * The dates attributes that will be mutated to Carbon instance.
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
        'parent_id',
        'name', 'slug', 'display_text', 'display', 'display_in_menu', 'page_url',
        'short_description',
        'meta_title', 'meta_description', 'meta_keywords',
    ];

    /**
     * A category belongs to a parent category.
     *
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * A category belongs to many products.
     *
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function products()
    {
        return $this->belongsToMany(Product::class)->withTimestamps();
    }

    /**
     * Check whether the category is the super parent category.
     *
     * @return  boolean
     */
    public function isSuperParent()
    {
        return $this->parent_id == 0;
    }

    /**
     * Check whether the category is the parent category.
     *
     * @return  boolean
     */
    public function isParent()
    {
        return $this->parent_id != 0;
    }

    /**
     * Get the super parent category.
     *
     * @return  \IndianIra\Category
     */
    public function getSuperParent()
    {
        if ($this->parent_id == 0) {
            return $this;
        }

        if ($this->parent->parent == null) {
            return $this->parent;
        }

        return $this->parent->parent;
    }

    /**
     * Check whether the category can any child category.
     *
     * @return  boolean
     */
    public function canAddChildCategory()
    {
        if ($this->parent_id == 0) {
            return true;
        }

        if ($this->parent == null) {
            return true;
        }

        if ($this->parent->parent == null) {
            return true;
        }

        return false;
    }

    /**
     * Check whether the category can be seen on navigation menu.
     *
     * @return  boolean
     */
    public function seenInNavigationMenu()
    {
        return $this->display_in_menu == true &&
                $this->isSuperParent();
    }
}
