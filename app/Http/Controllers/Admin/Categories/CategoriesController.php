<?php

namespace IndianIra\Http\Controllers\Admin\Categories;

use IndianIra\Category;
use Illuminate\Http\Request;
use IndianIra\Http\Controllers\Controller;

class CategoriesController extends Controller
{
    /**
     * Display all the categories.
     *
     * @return  \Illuminate\View\View
     */
    public function index()
    {
        $categories = $this->getAllCategories();

        // $parentCategories = $categories->where('parent_id', '<>', 0);

        return view('admin.categories.index', compact('categories', 'parentCategories'));
    }

    /**
     * Store the category data.
     *
     * @param   \Illuminate\Http\Request  $request
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'parent_id'         => 'bail|required|integer|min:0',
            'name'              => 'bail|required|string|max:250',
            'display'           => 'bail|required|in:Enabled,Disabled',
            'display_in_menu'   => 'bail|required|in:0,1',
            'short_description' => 'bail|nullable|max:250',
            'meta_title'        => 'bail|required|max:60',
            'meta_description'  => 'bail|required|max:160',
            'meta_keywords'     => 'nullable|max:150',
        ], [
            'name.required'      => 'The category name field is required.',
            'name.unique'        => 'The category name has already been taken.',
            'name.max'           => 'The category name may not be greater than 250 characters.',
            'parent_id.integer'  => 'Invalid parent category selected.',
            'parent_id.min'      => 'Invalid parent category selected.',
            'display.in'         => 'The display field should be either Enabled or Disabled.',
            'display_in_menu.in' => 'The display in menu field should be either Yes or No.',
        ]);

        $parentCategory = null;
        if ($request->parent_id > 0) {
            $parentCategory = Category::find($request->parent_id);

            if (! $parentCategory->canAddChildCategory()) {
                return response([
                    'status'  => 'failed',
                    'title'   => 'Failed !',
                    'delay'   => 3000,
                    'message' => 'Only three levels of category can be added.',
                ]);
            }
        }

        $request['slug']         = str_slug($request->name);
        $request['display_text'] = title_case($request->name);
        $request['page_url']     = '/' . str_slug($request->name);
        if ($parentCategory != null) {
            $request['slug']         = $parentCategory->slug . '-' . str_slug($request->name);
            $request['display_text'] = $parentCategory->display_text . ' > ' . title_case($request->name);
            $request['page_url']     = '/' . $parentCategory->slug . '-' . str_slug($request->name);
        }

        Category::create($request->all());

        $categories = $this->getAllCategories();

        return response([
            'status'   => 'success',
            'title'    => 'Success !',
            'delay'    => 3000,
            'message'  => 'Category added successfully!',
            'location' => route('admin.categories'),
            'htmlResult' => view('admin.categories.table', compact('categories'))->render(),
        ]);
    }

    /**
     * Update the category data of the given id.
     *
     * @param   integer  $id
     * @param   \Illuminate\Http\Request  $request
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function update($id, Request $request)
    {
        $this->validate($request, [
            'parent_id'         => 'bail|required|integer|min:0',
            'name'              => 'bail|required|string|max:250',
            'display'           => 'bail|required|in:Enabled,Disabled',
            'display_in_menu'   => 'bail|required|in:0,1',
            'short_description' => 'bail|nullable|max:250',
            'meta_title'        => 'bail|required|max:60',
            'meta_description'  => 'bail|required|max:160',
            'meta_keywords'     => 'nullable|max:150',
        ], [
            'name.required'      => 'The category name field is required.',
            'name.unique'        => 'The category name has already been taken.',
            'name.max'           => 'The category name may not be greater than 250 characters.',
            'parent_id.integer'  => 'Invalid parent category selected.',
            'parent_id.min'      => 'Invalid parent category selected.',
            'display.in'         => 'The display field should be either Enabled or Disabled.',
            'display_in_menu.in' => 'The display in menu field should be either Yes or No.',
        ]);

        $category = Category::find($id);

        if (! $category) {
            return response([
                'status'  => 'failed',
                'title'   => 'Failed !',
                'message' => 'Category with that id cannot be found.'
            ]);
        }

        $parentCategory = null;
        if ($request->parent_id > 0) {
            $parentCategory = Category::find($request->parent_id);

            if (! $parentCategory->canAddChildCategory()) {
                return response([
                    'status'  => 'failed',
                    'title'   => 'Failed !',
                    'delay'   => 3000,
                    'message' => 'Only three levels of category can be added.',
                ]);
            }
        }

        $request['slug']         = str_slug($request->name);
        $request['display_text'] = title_case($request->name);
        $request['page_url']     = '/' . str_slug($request->name);
        if ($parentCategory != null) {
            $request['slug']         = $parentCategory->slug . '-' . str_slug($request->name);
            $request['display_text'] = $parentCategory->display_text . ' > ' . title_case($request->name);
            $request['page_url']     = '/' . $parentCategory->slug . '-' . str_slug($request->name);
        }
        $category->update($request->all());

        $categories = $this->getAllCategories();

        return response([
            'status'   => 'success',
            'title'    => 'Success !',
            'delay'    => 3000,
            'message'  => 'Category updated successfully!',
            'location' => route('admin.categories'),
            'htmlResult' => view('admin.categories.table', compact('categories'))->render(),
        ]);
    }

    /**
     * Temporarily delete the category data of the given id.
     *
     * @param   integer  $id
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function delete($id)
    {
        $category = Category::find($id);

        if (! $category) {
            return response([
                'status'  => 'failed',
                'title'   => 'Failed !',
                'message' => 'Category with that id cannot be found.'
            ]);
        }

        $category->delete();

        $categories = $this->getAllCategories();

        return response([
            'status'     => 'success',
            'title'      => 'Success !',
            'delay'      => 3000,
            'message'    => 'Category deleted temporarily!',
            'location'   => route('admin.categories'),
            'htmlResult' => view('admin.categories.table', compact('categories'))->render(),
        ]);
    }

    /**
     * Restore the temporarily deleted category data of the given id.
     *
     * @param   integer  $id
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function restore($id)
    {
        $category = Category::onlyTrashed()->find($id);

        if (! $category) {
            return response([
                'status'  => 'failed',
                'title'   => 'Failed !',
                'message' => 'Category with that id cannot be found.'
            ]);
        }

        $category->restore();

        $categories = $this->getAllCategories();

        return response([
            'status'     => 'success',
            'title'      => 'Success !',
            'delay'      => 3000,
            'message'    => 'Category restored successfully!',
            'location'   => route('admin.categories'),
            'htmlResult' => view('admin.categories.table', compact('categories'))->render(),
        ]);
    }

    /**
     * Permanently delete the category data of the given id.
     *
     * @param   integer  $id
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function destroy($id)
    {
        $category = Category::onlyTrashed()->find($id);

        if (! $category) {
            return response([
                'status'  => 'failed',
                'title'   => 'Failed !',
                'message' => 'Category with that id cannot be found.'
            ]);
        }

        $category->forceDelete();

        $categories = $this->getAllCategories();

        return response([
            'status'     => 'success',
            'title'      => 'Success !',
            'delay'      => 3000,
            'message'    => 'Category destroyed successfully!',
            'location'   => route('admin.categories'),
            'htmlResult' => view('admin.categories.table', compact('categories'))->render(),
        ]);
    }

    /**
     * Get all the categories.
     *
     * @return  Illuminate\Database\Eloquent\Collection
     */
    protected function getAllCategories()
    {
        return Category::withTrashed()->orderBy('id', 'DESC')->get();
    }
}
