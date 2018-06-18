<?php

namespace IndianIra\Http\Controllers\Admin;

use IndianIra\ShippingRate;
use Illuminate\Http\Request;
use IndianIra\Http\Controllers\Controller;

class ShippingRatesController extends Controller
{
    /**
     * Display all the shipping rates.
     *
     * @return  \Illuminate\View\View
     */
    public function index()
    {
        $shippingRates = $this->getAllShippingRates();

        return view('admin.shipping-rates.index', compact('shippingRates'));
    }

    /**
     * Store the shipping company data.
     *
     * @param   \Illuminate\Http\Request  $request
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'shipping_company_name'         => 'required|string|max:250',
            'shipping_company_tracking_url' => 'required|url|max:250',
            'weight_from'                   => 'required|regex:/^\d+(\.(\d{0,2}))?$/',
            'weight_to'                     => 'required|regex:/^\d+(\.(\d{0,2}))?$/',
            'amount'                        => 'required|regex:/^\d+(\.(\d{0,2}))?$/',
        ], [
            'weight_from.regex' => 'The weight from field should contain only numbers with decimal upto 2 precisions only.',
            'weight_to.regex' => 'The weight to field should contain only numbers with decimal upto 2 precisions only.',
            'amount.regex' => 'The amount field should contain only numbers with decimal upto 2 precisions only.',
        ]);

        ShippingRate::create($request->all());

        $shippingRates = $this->getAllShippingRates();

        return response([
            'status'   => 'success',
            'title'    => 'Success !',
            'delay'    => 3000,
            'message'  => 'Shipping Rates added successfully!',
            'location' => route('admin.shippingRates'),
            'htmlResult' => view('admin.shipping-rates.table', compact('shippingRates'))->render(),
        ]);
    }

    /**
     * Update the shipping company data of the given id.
     *
     * @param   integer  $id
     * @param   \Illuminate\Http\Request  $request
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function update($id, Request $request)
    {
        $this->validate($request, [
            'shipping_company_name'         => 'required|string|max:250',
            'shipping_company_tracking_url' => 'required|url|max:250',
            'weight_from'                   => 'required|min:0|regex:/^\d+(\.(\d{0,2}))?$/',
            'weight_to'                     => 'required|min:0|regex:/^\d+(\.(\d{0,2}))?$/',
            'amount'                        => 'required|min:0|regex:/^\d+(\.(\d{0,2}))?$/',
        ]);

        $shippingRate = ShippingRate::find($id);

        if (! $shippingRate) {
            return response([
                'status'  => 'failed',
                'title'   => 'Failed !',
                'message' => 'Shipping Rate with that id cannot be found.'
            ]);
        }

        $shippingRate->update($request->all());

        $shippingRates = $this->getAllShippingRates();

        return response([
            'status'   => 'success',
            'title'    => 'Success !',
            'delay'    => 3000,
            'message'  => 'Shipping Rate updated successfully!',
            'location' => route('admin.shippingRates'),
            'htmlResult' => view('admin.shipping-rates.table', compact('shippingRates'))->render(),
        ]);
    }

    /**
     * Temporarily delete the shipping company data of the given id.
     *
     * @param   integer  $id
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function delete($id)
    {
        $shippingRate = ShippingRate::find($id);

        if (! $shippingRate) {
            return response([
                'status'  => 'failed',
                'title'   => 'Failed !',
                'message' => 'Shipping Rate with that id cannot be found.'
            ]);
        }

        $shippingRate->delete();

        $shippingRates = $this->getAllShippingRates();

        return response([
            'status'     => 'success',
            'title'      => 'Success !',
            'delay'      => 3000,
            'message'    => 'Shipping Rate deleted temporarily!',
            'location'   => route('admin.shippingRates'),
            'htmlResult' => view('admin.shipping-rates.table', compact('shippingRates'))->render(),
        ]);
    }

    /**
     * Restore the temporarily deleted shipping company data of the given id.
     *
     * @param   integer  $id
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function restore($id)
    {
        $shippingRate = ShippingRate::onlyTrashed()->find($id);

        if (! $shippingRate) {
            return response([
                'status'  => 'failed',
                'title'   => 'Failed !',
                'message' => 'Shipping Rate with that id cannot be found.'
            ]);
        }

        $shippingRate->restore();

        $shippingRates = $this->getAllShippingRates();

        return response([
            'status'     => 'success',
            'title'      => 'Success !',
            'delay'      => 3000,
            'message'    => 'Shipping Rate restored successfully!',
            'location'   => route('admin.shippingRates'),
            'htmlResult' => view('admin.shipping-rates.table', compact('shippingRates'))->render(),
        ]);
    }

    /**
     * Permanently delete the shipping company data of the given id.
     *
     * @param   integer  $id
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function destroy($id)
    {
        $shippingRate = ShippingRate::onlyTrashed()->find($id);

        if (! $shippingRate) {
            return response([
                'status'  => 'failed',
                'title'   => 'Failed !',
                'message' => 'Shipping Rate with that id cannot be found.'
            ]);
        }

        $shippingRate->forceDelete();

        $shippingRates = $this->getAllShippingRates();

        return response([
            'status'     => 'success',
            'title'      => 'Success !',
            'delay'      => 3000,
            'message'    => 'Shipping Rate destroyed successfully!',
            'location'   => route('admin.shippingRates'),
            'htmlResult' => view('admin.shipping-rates.table', compact('shippingRates'))->render(),
        ]);
    }

    /**
     * Get all the shipping rates.
     *
     * @return  Illuminate\Database\Eloquent\Collection
     */
    protected function getAllShippingRates()
    {
        return ShippingRate::withTrashed()->orderBy('id', 'DESC')->get();
    }
}
