<?php

namespace IndianIra\Http\Controllers\Admin\Categories;

use IndianIra\Category;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use IndianIra\Http\Controllers\Controller;

class ExportController extends Controller
{
    /**
     * Download the categories in xlsx file
     *
     * @return  void
     */
    public function export()
    {
        $categories = Category::withTrashed()->get();

        $currentTime = \Carbon\Carbon::now()
                                ->timezone('Asia/Kolkata')
                                ->format('Y_m_d_h_i_s_A');

        $file = Excel::create('categories_' . $currentTime, function ($excel) use ($categories) {
            $excel->sheet('categories', function ($sheet) use ($categories) {
                $sheet->fromModel($categories, null, 'A1', true);
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
