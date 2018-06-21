<?php

namespace IndianIra\Http\Controllers\Admin\Categories;

use IndianIra\Category;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use IndianIra\Http\Controllers\Controller;

class ImportController extends Controller
{
    /**
     * Upload the product categories file
     *
     * @param   \Illuminate\Http\Request  $request
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function import(Request $request)
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
            ->selectSheets('categories')
            ->load($request->excel_file)
            ->chunk(400, function ($reader) {
                foreach ($reader->toArray() as $data) {
                    $category = Category::find($data['id']);

                    if ($category == null) {
                        Category::create($data);
                    } else {
                        $category->update($data);
                    }
                }
            }, false);

        return response([
            'status'   => 'success',
            'title'    => 'Success !',
            'delay'    => 3000,
            'message'  => 'Categories imported successfully.. Reloading in few seconds...',
            'location' => route('admin.categories'),
        ]);
    }
}
