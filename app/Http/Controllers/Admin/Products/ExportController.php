<?php

namespace IndianIra\Http\Controllers\Admin\Products;

use IndianIra\Product;
use Maatwebsite\Excel\Facades\Excel;
use IndianIra\Http\Controllers\Controller;

class ExportController extends Controller
{
    /**
     * Download the products in xlsx file
     *
     * @return  void
     */
    public function export()
    {
        $models = $this->productsData();

        $file = $this->generateExcelFile($models);

        $file->download('xlsx');
    }

    /**
     * Get all the products data.
     *
     * @return  array
     */
    protected function productsData()
    {
        $products = Product::with(['options'])->withTrashed()->get();

        $data = [];

        foreach ($products as $product) {
            $data[] = [
                'id'                => $product->id,
                'code'              => $product->code,
                'name'              => $product->name,
                'number_of_options' => $product->number_of_options,
                'gst_percent'       => $product->gst_percent,
                'description'       => $product->description,
                'additional_notes'  => $product->additional_notes,
                'terms'             => $product->terms,
                'meta_title'        => $product->meta_title,
                'meta_description'  => $product->meta_description,
                'meta_keywords'     => $product->meta_keywords,
                'display'           => $product->display,
                'images'            => $product->images,
                'categories'        => str_replace(' - ', '>', $product->categories->implode('display_text', '; ')),
                'tags'              => $product->tags->implode('name', '; '),
                'created_at'        => $product->created_at,
                'updated_at'        => $product->updated_at,
                'deleted_at'        => $product->deleted_at,
            ];
        }

        return [
            'products' => $data,
            'options'  => \IndianIra\ProductPriceAndOption::all(),
        ];
    }

    /**
     * Generate the excel file after fetching the results from the database.
     *
     * @param   array  $models
     * @return  \Maatwebsite\Excel\Excel
     */
    protected function generateExcelFile($models)
    {
        $nowTime = \Carbon\Carbon::now()
                                ->timezone('Asia/Kolkata')
                                ->format('Y_m_d_h_i_s_A');

        return Excel::create('Products_' . $nowTime, function ($excel) use ($models) {
            $this->excel = $excel;

            foreach ($models as $sheetName => $model) {
                $this->makeSheets($sheetName, $model);
            }
        });
    }

    /**
     * Make the sheets for the excel file.
     *
     * @param  string  $model
     */
    public function makeSheets($sheetName, $model)
    {
        $this->excel->sheet($sheetName, function ($sheet) use ($model) {
            $sheet->fromArray($model, null, 'A1', true);
        });
    }
}
