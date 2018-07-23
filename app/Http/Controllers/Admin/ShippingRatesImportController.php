<?php

namespace IndianIra\Http\Controllers\Admin;

use IndianIra\ShippingRate;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use IndianIra\Http\Controllers\Controller;

class ShippingRatesImportController extends Controller
{
    /**
     * Store / Update the shipping rates file.
     *
     * @param   \Illuminate\Http\Request  $request
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request)
    {
        if ($request->excel_file != null) {
            $request['extension'] = strtolower(($request->excel_file->getClientOriginalExtension()));
        }

        $this->validate($request, [
            'excel_file' => 'required',
            'extension'  => 'required|in:xlsx,xls'
        ], [
            'excel_file.required' => 'The upload excel file field is required.',
            'extension.in' => 'Invalid file uploaded. Only xlsx and xls file can be uploaded.',
        ]);

        Excel::filter('chunk')
            ->selectSheets('shipping_rates')
            ->load($request->excel_file)
            ->chunk(500, function ($reader) {
                foreach ($reader->toArray() as $data) {
                    $shippingRate = ShippingRate::find($data['id']);

                    if ($shippingRate == null) {
                        ShippingRate::create($data);
                    } else {
                        $shippingRate->update($data);
                    }
                }
            }, false);

        return response([
            'status'   => 'success',
            'title'    => 'Success !',
            'delay'    => 3000,
            'message'  => 'Shipping Rates imported successfully.. Reloading in few seconds...',
            'location' => route('admin.shippingRates'),
        ]);
    }
}
