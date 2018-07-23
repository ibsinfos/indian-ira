<?php

namespace IndianIra\Http\Controllers\Checkout;

use IndianIra\Order;
use IndianIra\OrderAddress;
use IndianIra\OrderHistory;
use Illuminate\Http\Request;
use IndianIra\Utilities\Cart;
use IndianIra\Mail\OrderPlaced;
use IndianIra\Mail\OrderReceived;
use Illuminate\Support\Facades\Mail;
use IndianIra\Http\Controllers\Controller;

class OfflineController extends Controller
{
    /**
     * Store the offline order.
     *
     * @param   \Illuminate\Http\Request  $request
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'full_name'               => 'required|max:200|regex:/^[a-zA-Z0-9 \/_.,\-\']+$/',
            'address_line_1'          => 'required|max:100|regex:/^[a-zA-Z0-9 \/_.,\-\']+$/',
            'address_line_2'          => 'nullable|max:100|regex:/^[a-zA-Z0-9 \/_.,\-\']+$/',
            'area'                    => 'required|max:100|regex:/^[a-zA-Z0-9 \/_.,\-\']+$/',
            'landmark'                => 'nullable|max:100|regex:/^[a-zA-Z0-9 \/_.,\-\']+$/',
            'city'                    => 'required|max:100|regex:/^[a-zA-Z0-9 \/_.,\-\']+$/',
            'pin_code'                => 'required|max:100|regex:/^[a-zA-Z0-9 \/_.,\-\']+$/',
            'state'                   => 'required|max:100|regex:/^[a-zA-Z0-9 \/_.,\-\']+$/',
            'country'                 => 'required|max:100|regex:/^[a-zA-Z0-9 \/_.,\-\']+$/',
            'contact_number'          => 'required|max:50|regex:/^[0-9 ]+$/',

            'sameAsBillingAddress'    => 'nullable|in:yes',
            'payment_method'          => 'required|in:online,offline,cod',

            'shipping_full_name'      => 'required|max:200|regex:/^[a-zA-Z0-9 \/_.,\-\']+$/',
            'shipping_address_line_1' => 'required|max:100|regex:/^[a-zA-Z0-9 \/_.,\-\']+$/',
            'shipping_address_line_2' => 'nullable|max:100|regex:/^[a-zA-Z0-9 \/_.,\-\']+$/',
            'shipping_area'           => 'required|max:100|regex:/^[a-zA-Z0-9 \/_.,\-\']+$/',
            'shipping_landmark'       => 'nullable|max:100|regex:/^[a-zA-Z0-9 \/_.,\-\']+$/',
            'shipping_city'           => 'required|max:100|regex:/^[a-zA-Z0-9 \/_.,\-\']+$/',
            'shipping_pin_code'       => 'required|max:100|regex:/^[a-zA-Z0-9 \/_.,\-\']+$/',
            'shipping_state'          => 'required|max:100|regex:/^[a-zA-Z0-9 \/_.,\-\']+$/',
            'shipping_country'        => 'required|max:100|regex:/^[a-zA-Z0-9 \/_.,\-\']+$/',
            'shipping_contact_number' => 'required|max:50|regex:/^[0-9 ]+$/',
        ]);

        $user = auth()->user();

        $cart = session('cart');

        $orders = collect();
        foreach ($cart as $productOptionCode => $data) {

            if ($data['options']->hasUploadedImageFile()) {
                $image = $data['options']->cartImage();
            } elseif ($data['product']->hasUploadedImageFile()) {
                $image = $data['product']->cartImage();
            } else {
                $image = '/images/no-image.jpg';
            }

            $order = Order::create([
                'order_code'                 => 'ORD-'. mt_rand(1, 99999999),

                'user_id'                    => $user->id,
                'user_full_name'             => $user->getFullName(),
                'user_username'              => $user->username,
                'user_email'                 => $user->email,
                'user_contact_number'        => $user->contact_number,

                'product_id'                 => $data['product']->id,
                'product_code'               => $data['product']->code,
                'product_name'               => $data['product']->name,
                'product_cart_image'         => $image,
                'product_page_url'           => $data['product']->canonicalPageUrl(),
                'product_number_of_options'  => $data['product']->number_of_options,

                'product_option_id'          => $data['options']->id,
                'product_option_code'        => $data['options']->option_code,
                'product_option_1_heading'   => $data['options']->option_1_heading,
                'product_option_1_value'     => $data['options']->option_1_value,
                'product_option_2_heading'   => $data['options']->option_2_heading,
                'product_option_2_value'     => $data['options']->option_2_value,
                'product_stock'              => $data['options']->stock,
                'product_weight'             => $data['options']->weight,
                'product_quantity'           => $data['quantity'],

                'product_selling_price'      => $data['options']->selling_price,
                'product_discount_price'     => $data['options']->selling_price,
                'product_net_amount'         => Cart::netAmount($productOptionCode),
                'product_gst_amount'         => Cart::gstAmount($productOptionCode),
                'product_gst_percent'        => $data['product']->gst_percent,
                'product_total_amount'       => $data['product_total'],

                'payment_method'             => $request->payment_method,

                'coupon_code'                => session('appliedDiscount')
                                                ? session('appliedDiscount')['coupon']->code
                                                : null,
                'coupon_discount_percent'    => session('appliedDiscount')
                                                ? session('appliedDiscount')['coupon']->discount_percent
                                                : 0.0,

                'cart_total_net_amount'      => Cart::totalNetAmount(),
                'cart_total_gst_amount'      => Cart::totalGstAmount(),
                'cart_total_shipping_amount' => Cart::totalShippingAmount(),
                'cart_total_cod_amount'      => Cart::codCharges(),
                'cart_coupon_amount'         => session('appliedDiscount')
                                                ? session('appliedDiscount')['amount']
                                                : 0.0,
                'cart_total_payable_amount'  => Cart::totalPayableAmount(),
            ]);

            $data['options']->update([
                'stock' => $data['options']->stock - $data['quantity']
            ]);

            OrderHistory::create([
                'order_id'              => $order->id,
                'order_code'            => $order->order_code,
                'user_id'               => $order->user_id,
                'user_full_name'        => $order->user_full_name,
                'user_email'            => $order->user_email,
                'product_id'            => $order->product_id,
                'product_code'          => $order->product_code,
                'product_name'          => $order->product_name,
                'product_option_id'     => $order->product_option_id,
                'product_option_code'   => $order->product_option_code,
                'shipping_company'      => session('shippingCompany')->shipping_company_name,
                'shipping_tracking_url' => session('shippingCompany')->shipping_company_tracking_url,
                'status'                => 'Processing',
                'notes'                 => 'Order placed successfully...',
            ]);

            $orders->push($order);
        }

        session(['offlineOrders' => $orders]);

        if (! empty($orders)) {
            $order = $orders->first();

            $action = $user->billingAddress != null ? 'update' : 'create';

            $user->billingAddress()->$action([
                'address_line_1' => $request->address_line_1,
                'address_line_2' => $request->address_line_2,
                'area'           => $request->area,
                'landmark'       => $request->landmark,
                'city'           => $request->city,
                'pin_code'       => $request->pin_code,
                'state'          => $request->state,
                'country'        => $request->country,
            ]);

            OrderAddress::create([
                'order_id'                 => $order->id,
                'order_code'               => $order->order_code,
                'user_id'                  => $user->id,
                'full_name'                => $request->full_name,
                'address_line_1'           => $request->address_line_1,
                'address_line_2'           => $request->address_line_2,
                'area'                     => $request->area,
                'landmark'                 => $request->landmark,
                'city'                     => $request->city,
                'pin_code'                 => $request->pin_code,
                'state'                    => $request->state,
                'country'                  => $request->country,

                'shipping_same_as_billing' => $request->sameAsBillingAddress,

                'shipping_full_name'       => $request->shipping_full_name,
                'shipping_address_line_1'  => $request->shipping_address_line_1,
                'shipping_address_line_2'  => $request->shipping_address_line_2,
                'shipping_area'            => $request->shipping_area,
                'shipping_landmark'        => $request->shipping_landmark,
                'shipping_city'            => $request->shipping_city,
                'shipping_pin_code'        => $request->shipping_pin_code,
                'shipping_state'           => $request->shipping_state,
                'shipping_country'         => $request->shipping_country,
            ]);
        }

        Mail::to($user->email, $user->getFullName())
            ->send(new OrderPlaced($user, $orders));

        Mail::to('info@indianira.com', config('app.name'))
            ->send(new OrderReceived($user, $orders));

        if ($orders->isNotEmpty()) {
            return response(['status' => 'success', 'location' => route('orderPlacedOfflineSuccess')]);
        }
    }

    /**
     * Display the offline thank you page with bank details.
     *
     * @return  \Illuminate\View\View
     */
    public function show()
    {
        $bank = \IndianIra\GlobalSettingBankDetail::first();

        if (! \IndianIra\GlobalSettingBankDetail::first()) {
            $bank = factory(\IndianIra\GlobalSettingBankDetail::class)->create();
        }

        $companyAddress = \IndianIra\GlobalSettingCompanyAddress::first();

        if (! \IndianIra\GlobalSettingCompanyAddress::first()) {
            $companyAddress = factory(\IndianIra\GlobalSettingCompanyAddress::class)->create();
        }

        return view('orders.placed_offline_success', compact('bank', 'companyAddress'));
    }
}
