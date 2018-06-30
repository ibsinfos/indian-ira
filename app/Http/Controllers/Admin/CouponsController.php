<?php

namespace IndianIra\Http\Controllers\Admin;

use IndianIra\Coupon;
use Illuminate\Http\Request;
use IndianIra\Http\Controllers\Controller;

class CouponsController extends Controller
{
    /**
     * Display all the coupons.
     *
     * @return  \Illuminate\View\View
     */
    public function index()
    {
        $coupons = $this->getAllCoupons();

        return view('admin.coupons.index', compact('coupons'));
    }

    /**
     * Store the coupon data.
     *
     * @param   \Illuminate\Http\Request  $request
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'code'             => 'required|alpha_dash|max:30',
            'discount_percent' => 'required|max:10|regex:/^\d+(\.(\d{0,2}))?$/'
        ], [
            'code.required'          => 'The coupon code field is required.',
            'code.max'               => 'The coupon code may not be greater than 30 characters.',
            'discount_percent.regex' => 'The discount percent should contain only numbers with optional decimal upto 2 precisions.'
        ]);

        Coupon::create($request->all());

        $coupons = $this->getAllCoupons();

        return response([
            'status'     => 'success',
            'title'      => 'Success !',
            'delay'      => 3000,
            'message'    => 'Coupon added successfully!',
            'htmlResult' => view('admin.coupons.table', compact('coupons'))->render(),
        ]);
    }

    /**
     * Update the coupon data of the given id.
     *
     * @param   integer  $id
     * @param   \Illuminate\Http\Request  $request
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function update($id, Request $request)
    {
        $this->validate($request, [
            'code'             => 'required|alpha_dash|max:30',
            'discount_percent' => 'required|max:10|regex:/^\d+(\.(\d{0,2}))?$/'
        ], [
            'code.required'          => 'The coupon code field is required.',
            'code.max'               => 'The coupon code may not be greater than 30 characters.',
            'discount_percent.regex' => 'The discount percent should contain only numbers with optional decimal upto 2 precisions.'
        ]);

        $coupon = $this->getAllCoupons()->where('id', $id)->first();

        if (! $coupon) {
            return response([
                'status'  => 'failed',
                'title'   => 'Failed !',
                'message' => 'Coupon with that id cannot be found.'
            ]);
        }

        $coupon->update($request->all());

        $coupons = $this->getAllCoupons();

        return response([
            'status'     => 'success',
            'title'      => 'Success !',
            'delay'      => 3000,
            'message'    => 'Coupon updated successfully!',
            'htmlResult' => view('admin.coupons.table', compact('coupons'))->render(),
        ]);
    }

    /**
     * Temporarily delete the coupon data of the given id.
     *
     * @param   integer  $id
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function delete($id)
    {
        $coupon = $this->getAllCoupons()->where('id', $id)->first();

        if (! $coupon) {
            return response([
                'status'  => 'failed',
                'title'   => 'Failed !',
                'message' => 'Coupon with that id cannot be found.'
            ]);
        }

        $coupon->delete();

        $coupons = $this->getAllCoupons();

        return response([
            'status'     => 'success',
            'title'      => 'Success !',
            'delay'      => 3000,
            'message'    => 'Coupon deleted temporarily!',
            'htmlResult' => view('admin.coupons.table', compact('coupons'))->render(),
        ]);
    }

    /**
     * Restore the temporarily deleted coupon data of the given id.
     *
     * @param   integer  $id
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function restore($id)
    {
        $coupon = $this->getAllCoupons()->where('deleted_at', '<>', null)->where('id', $id)->first();

        if (! $coupon) {
            return response([
                'status'  => 'failed',
                'title'   => 'Failed !',
                'message' => 'Coupon with that id cannot be found.'
            ]);
        }

        $coupon->restore();

        $coupons = $this->getAllCoupons();

        return response([
            'status'     => 'success',
            'title'      => 'Success !',
            'delay'      => 3000,
            'message'    => 'Coupon restored successfully!',
            'htmlResult' => view('admin.coupons.table', compact('coupons'))->render(),
        ]);
    }

    /**
     * Permanently delete the coupon data of the given id.
     *
     * @param   integer  $id
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function destroy($id)
    {
        $coupon = $this->getAllCoupons()->where('deleted_at', '<>', null)->where('id', $id)->first();

        if (! $coupon) {
            return response([
                'status'  => 'failed',
                'title'   => 'Failed !',
                'message' => 'Coupon with that id cannot be found.'
            ]);
        }

        $coupon->forceDelete();

        $coupons = $this->getAllCoupons();

        return response([
            'status'     => 'success',
            'title'      => 'Success !',
            'delay'      => 3000,
            'message'    => 'Coupon destroyed successfully!',
            'htmlResult' => view('admin.coupons.table', compact('coupons'))->render(),
        ]);
    }

    /**
     * Get all the coupons.
     *
     * @return  Illuminate\Database\Eloquent\Collection
     */
    protected function getAllCoupons()
    {
        return Coupon::withTrashed()->orderBy('id', 'DESC')->get();
    }
}
