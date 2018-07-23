<?php

namespace IndianIra\Http\Controllers\Admin;

use IndianIra\ShippingRate;
use Maatwebsite\Excel\Facades\Excel;
use IndianIra\Http\Controllers\Controller;

class ShippingRatesExportController extends Controller
{
    /**
     * Download the shipping rates in xlsx file
     *
     * @return  void
     */
    public function export()
    {
        $shippingRates = ShippingRate::withTrashed()->get();

        $currentTime = now()->timezone('Asia/Kolkata')->format('Y_m_d_h_i_s_A');

        $file = Excel::create('shipping_rates_' . $currentTime, function ($excel) use ($shippingRates) {
            $excel->sheet('shipping_rates', function ($sheet) use ($shippingRates) {
                $sheet->fromModel($shippingRates, null, 'A1', true);
            });
        });

        $identifier = app('excel.identifier');
        $format = $identifier->getFormatByExtension('xlsx');
        $contentType = $identifier->getContentTypeByFormat($format);

        return response($file->string('xlsx'), 200, [
            'Content-Type'        => $contentType,
            'Content-Disposition' => 'attachment; filename="' . $file->getFileName() . '.xlsx"',
            'Expires'             => 'Mon, 26 Jul 1997 05:00:00 GMT', // Date in the past
            'Last-Modified'       => $currentTime,
            'Cache-Control'       => 'cache, must-revalidate',
            'Pragma'              => 'public',
        ]);
    }
}
