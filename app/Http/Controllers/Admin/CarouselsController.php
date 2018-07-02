<?php

namespace IndianIra\Http\Controllers\Admin;

use IndianIra\Carousel;
use Illuminate\Http\Request;
use IndianIra\Http\Controllers\Controller;

class CarouselsController extends Controller
{
    /**
     * Display all the carousels.
     *
     * @return  \Illuminate\View\View
     */
    public function index()
    {
        $carousels = $this->getAllCarousels();

        $products = \IndianIra\Product::whereDisplay('Enabled')->get();

        return view('admin.carousels.index', compact('carousels', 'products'));
    }

    /**
     * Store the carousel data.
     *
     * @param   \Illuminate\Http\Request  $request
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'              => 'bail|required|string|max:50',
            'display'           => 'bail|required|in:Enabled,Disabled',
            'short_description' => 'bail|nullable|max:250',
            'product_id'        => 'required|array',
        ], [
            'name.required'         => 'The carousel name field is required.',
            'name.max'              => 'The carousel name may not be greater than 50 characters.',
            'display.in'            => 'The display field should be either Enabled or Disabled.',
            'product_id.required'   => 'Select products you wish to add in this carousel.',
            'product_id.array'      => 'Invalid products selected.',
        ]);

        $carousel = Carousel::create($request->all());

        $carousel->products()->attach($request->product_id);

        $carousels = $this->getAllCarousels();

        return response([
            'status'   => 'success',
            'title'    => 'Success !',
            'delay'    => 3000,
            'message'  => 'Carousel added successfully!',
            'location' => route('admin.carousels'),
            'htmlResult' => view('admin.carousels.table', compact('carousels'))->render(),
        ]);
    }

    /**
     * Update the carousel data of the given id.
     *
     * @param   integer  $id
     * @param   \Illuminate\Http\Request  $request
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function update($id, Request $request)
    {
        $this->validate($request, [
            'name'              => 'bail|required|string|max:50',
            'display'           => 'bail|required|in:Enabled,Disabled',
            'short_description' => 'bail|nullable|max:250',
            'product_id'        => 'required|array',
        ], [
            'name.required'         => 'The carousel name field is required.',
            'name.max'              => 'The carousel name may not be greater than 50 characters.',
            'display.in'            => 'The display field should be either Enabled or Disabled.',
            'product_id.required'   => 'Select products you wish to add in this carousel.',
            'product_id.array'      => 'Invalid products selected.',
        ]);

        $carousel = Carousel::find($id);

        if (! $carousel) {
            return response([
                'status'  => 'failed',
                'title'   => 'Failed !',
                'message' => 'Carousel with that id cannot be found.'
            ]);
        }

        $carousel->update($request->all());

        $carousel->fresh()->products()->sync($request->product_id);

        $carousels = $this->getAllCarousels();

        return response([
            'status'   => 'success',
            'title'    => 'Success !',
            'delay'    => 3000,
            'message'  => 'Carousel updated successfully!',
            'location' => route('admin.carousels'),
            'htmlResult' => view('admin.carousels.table', compact('carousels'))->render(),
        ]);
    }

    /**
     * Temporarily delete the carousel data of the given id.
     *
     * @param   integer  $id
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function delete($id)
    {
        $carousel = Carousel::find($id);

        if (! $carousel) {
            return response([
                'status'  => 'failed',
                'title'   => 'Failed !',
                'message' => 'Carousel with that id cannot be found.'
            ]);
        }

        $carousel->delete();

        $carousels = $this->getAllCarousels();

        return response([
            'status'     => 'success',
            'title'      => 'Success !',
            'delay'      => 3000,
            'message'    => 'Carousel deleted temporarily!',
            'location'   => route('admin.carousels'),
            'htmlResult' => view('admin.carousels.table', compact('carousels'))->render(),
        ]);
    }

    /**
     * Restore the temporarily deleted carousel data of the given id.
     *
     * @param   integer  $id
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function restore($id)
    {
        $carousel = Carousel::onlyTrashed()->find($id);

        if (! $carousel) {
            return response([
                'status'  => 'failed',
                'title'   => 'Failed !',
                'message' => 'Carousel with that id cannot be found.'
            ]);
        }

        $carousel->restore();

        $carousels = $this->getAllCarousels();

        return response([
            'status'     => 'success',
            'title'      => 'Success !',
            'delay'      => 3000,
            'message'    => 'Carousel restored successfully!',
            'location'   => route('admin.carousels'),
            'htmlResult' => view('admin.carousels.table', compact('carousels'))->render(),
        ]);
    }

    /**
     * Permanently delete the carousel data of the given id.
     *
     * @param   integer  $id
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function destroy($id)
    {
        $carousel = Carousel::onlyTrashed()->find($id);

        if (! $carousel) {
            return response([
                'status'  => 'failed',
                'title'   => 'Failed !',
                'message' => 'Carousel with that id cannot be found.'
            ]);
        }

        $carousel->forceDelete();

        $carousels = $this->getAllCarousels();

        return response([
            'status'     => 'success',
            'title'      => 'Success !',
            'delay'      => 3000,
            'message'    => 'Carousel destroyed successfully!',
            'location'   => route('admin.carousels'),
            'htmlResult' => view('admin.carousels.table', compact('carousels'))->render(),
        ]);
    }

    /**
     * Get all the carousels.
     *
     * @return  Illuminate\Database\Eloquent\Collection
     */
    protected function getAllCarousels()
    {
        return Carousel::withTrashed()->orderBy('id', 'DESC')->get();
    }
}
