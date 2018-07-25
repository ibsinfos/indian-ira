<?php

namespace IndianIra\Http\Controllers\Admin\Products;

use Illuminate\Http\Request;
use IndianIra\EnquireProduct;
use IndianIra\Http\Controllers\Controller;

class ProductEnquiriesController extends Controller
{
    /**
     * Display all the product enquiries.
     *
     * @return  \Illuminate\View\View
     */
    public function index()
    {
        $enquiries = $this->getEnquiries();

        return view('admin.products-enquiries.index', compact('enquiries'));
    }

    /**
     * Temporarily delete the product enquiry of the given code.
     *
     * @param   string  $code
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function delete($code)
    {
        $enquiry = $this->getEnquiries()->where('code', $code)->first();
        if (! $enquiry) {
            return response([
                'status' => 'failed',
                'title' => 'Failed !',
                'delay' => 3000,
                'message' => 'Product Enquiry with that code cannot be found!',
            ]);
        }

        $enquiry->delete();

        $enquiries = $this->getEnquiries();

        return response([
            'status'     => 'success',
            'title'      => 'Success !',
            'delay'      => 3000,
            'message'    => 'Product Enquiry deleted successfully !',
            'htmlResult' => view('admin.products-enquiries.table', compact('enquiries'))->render()
        ]);
    }

    /**
     * Restore the temporarily deleted product enquiry of the given code.
     *
     * @param   string  $code
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function restore($code)
    {
        $enquiry = $this->getEnquiries()->where('code', $code)->first();
        if (! $enquiry) {
            return response([
                'status' => 'failed',
                'title' => 'Failed !',
                'delay' => 3000,
                'message' => 'Product Enquiry with that code cannot be found!',
            ]);
        }

        $enquiry->restore();

        $enquiries = $this->getEnquiries();

        return response([
            'status'     => 'success',
            'title'      => 'Success !',
            'delay'      => 3000,
            'message'    => 'Product Enquiry restored successfully !',
            'htmlResult' => view('admin.products-enquiries.table', compact('enquiries'))->render()
        ]);
    }

    /**
     * Permanently delete the temporarily deleted product enquiry of the given code.
     *
     * @param   string  $code
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function destroy($code)
    {
        $enquiry = $this->getEnquiries()->where('code', $code)->first();
        if (! $enquiry) {
            return response([
                'status' => 'failed',
                'title' => 'Failed !',
                'delay' => 3000,
                'message' => 'Product Enquiry with that code cannot be found!',
            ]);
        }

        $enquiry->forceDelete();

        $enquiries = $this->getEnquiries();

        return response([
            'status'     => 'success',
            'title'      => 'Success !',
            'delay'      => 3000,
            'message'    => 'Product Enquiry destroyed successfully !',
            'htmlResult' => view('admin.products-enquiries.table', compact('enquiries'))->render()
        ]);
    }

    /**
     * Get all the products enquiries.
     *
     * @return  \Illuminate\Support\Collection
     */
    protected function getEnquiries()
    {
        return EnquireProduct::withTrashed()
                                ->orderBy('id', 'DESC')
                                ->get();
    }
}
