<?php

namespace IndianIra\Http\Controllers;

use IndianIra\Product;
use Illuminate\Http\Request;
use IndianIra\EnquireProduct;
use Illuminate\Support\Facades\Mail;
use IndianIra\Mail\ProductEnquiryReceived;

class EnquireProductsController extends Controller
{
    /**
     * Store the product's enquiry.
     *
     * @param   string   $productCode
     * @param   string   $optionCode
     * @param   \Illuminate\Http\Request  $request
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function store($productCode, $optionCode, Request $request)
    {
        $product = Product::onlyEnabled()->whereCode($productCode)->first();
        if (! $product) {
            return response([
                'status'  => 'failed',
                'title'   => 'Failed !',
                'delay'   => 3000,
                'message' => 'Product with that code could not be found!'
            ]);
        }

        $option = $product->options->where('display', 'Enabled')->where('option_code', $optionCode)->first();
        if (! $option) {
            return response([
                'status'  => 'failed',
                'title'   => 'Failed !',
                'delay'   => 3000,
                'message' => 'Product with that option code could not be found!'
            ]);
        }

        $this->validate($request, [
            'user_full_name'      => 'required|max:200',
            'user_email'          => 'required|email',
            'user_contact_number' => 'required|numeric',
            'message_body'        => 'required|max:1000',
        ]);

        $lastRecord = EnquireProduct::withTrashed()->get()->last();
        if (! $lastRecord) {
            $code = 'ENQ-PRD-1';
        } else {
            $code = last(explode('-', $lastRecord->code));
            $code = 'ENQ-PRD-'. ++$code;
        }

        $enquiry = EnquireProduct::create([
            'code'                => $code,
            'product_id'          => $product->id,
            'product_code'        => $product->code,
            'product_name'        => $product->name,
            'option_id'           => $option->id,
            'option_code'         => $option->option_code,
            'product_image'       => $product->cartImage(),
            'product_page_url'    => $product->canonicalPageUrl(),
            'user_full_name'      => $request->user_full_name,
            'user_email'          => $request->user_email,
            'user_contact_number' => $request->user_contact_number,
            'message_body'        => $request->message_body,
        ]);

        Mail::to('enquiries@indianira.com', config('app.name'))
            ->send(new ProductEnquiryReceived($enquiry, $request->all()));

        return response([
            'status'  => 'success',
            'title'   => 'Success !',
            'delay'   => 3000,
            'message' => 'Product enquiry submitted successfully !',
        ]);
    }
}
