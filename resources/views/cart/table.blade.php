<table class="table table-bordered cartTable" style="font-size: 13px;">
    <thead>
        <th style="width: 40%">Product Details</th>
        <th class="text-center">Net Price</th>
        <th class="text-center">GST Amount with %</th>
        <th class="text-center">Rate</th>
        <th class="text-center">Qty</th>
        <th class="text-center">Amount</th>
        <th>Remove</th>
    </thead>

    <tbody>
        @forelse ($cart as $code => $row)
            @php
            $sellingPrice = $row['options']->selling_price;

            if ($row['options']->discount_price > 0.0) {
                $sellingPrice = $row['options']->discount_price;
            }

            $netPrice = ($sellingPrice / (1 + ($row['product']->gst_percent / 100)));

            $gstAmount = $sellingPrice - $netPrice;

            // $gstAmount = ($row['options']->selling_price * ($row['product']->gst_percent / 100));
            // $netPrice = $row['options']->selling_price - $gstAmount;
            @endphp
            <tr>
                <td>
                    @if ($row['options']->hasUploadedImageFile())
                        <img
                            src="{{ $row['options']->cartImage() }}"
                            alt="{{ $row['product']->name }}"
                            class="img-fluid float-left mr-3"
                        >
                    @elseif($row['product']->hasUploadedImageFile())
                        <img
                            src="{{ $row['product']->cartImage() }}"
                            alt="{{ $row['product']->name }}"
                            class="img-fluid float-left mr-3"
                        >
                    @else
                        <img
                            src="{{ url('/images/no-image.jpg') }}"
                            alt="Image Not Found"
                            class="img-fluid float-left mr-3"
                        />
                    @endif

                    <div class="mb-2">
                        <a
                            href="{{ $row['product']->pageUrl() }}"
                            class="mainSiteLink font-weight-bold"
                            style="font-size: 15px;"
                        >
                            {{ title_case($row['product']->name) }}
                        </a>
                    </div>

                    <div class="mb-2">
                        {{ $row['product']->code }}
                        @if ($row['product']->number_of_options >= 1)
                            / {{ $row['options']->option_code }}
                        @endif
                    </div>

                    {{ number_format($row['options']->weight, 2) }} grams
                </td>

                <td class="text-right">
                    <i class="fas fa-rupee-sign"></i>
                    {{ number_format($netPrice, 2) }}
                </td>

                <td class="text-right">
                    <i class="fas fa-rupee-sign"></i>
                    {{ number_format($gstAmount, 2) }} &#64; ({{ $row['product']->gst_percent }}%)
                </td>

                <td class="text-right">
                    <i class="fas fa-rupee-sign"></i>
                    {{ number_format($sellingPrice, 2) }}
                </td>

                <td style="width: 10%">
                    <form
                        method="POST"
                        class="formUpdateQty"
                    >
                        @csrf

                        <input
                            type="text"
                            name="quantity"
                            value="{{ $row['quantity'] }}"
                            class="form-control-sm txtUpdateQty w-100"
                            data-code="{{ $code }}"
                            data-action="{{ route('cart.updateQty', $code) }}"
                        />

                        <span id="{{ $code }}" class="text-danger" style="font-size: 13px;"></span>
                    </form>
                </td>

                <td class="text-right">
                    <i class="fas fa-rupee-sign"></i>
                    {{ number_format((\IndianIra\Utilities\Cart::netAmount($code) * $row['quantity']) + (\IndianIra\Utilities\Cart::gstAmount($code) * $row['quantity']), 2) }}
                </td>

                <td class="text-center">
                    <a
                        href="{{ route('cart.remove', $code) }}"
                        class="btn btn-outline-danger btn-sm btnRemove"
                        title="Remove this product"
                        data-toggle="tooltip"
                        data-placement="top"
                    >
                        <i class="fas fa-trash"></i>
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7">No data available</td>
            </tr>
        @endforelse

        <tr>
            <td colspan="5" class="font-weight-bold text-right">Total Net Amount:</td>
            <td class="text-right">
                <i class="fas fa-rupee-sign"></i>
                {{ number_format(\IndianIra\Utilities\Cart::totalNetAmount(), 2) }}
            </td>
            <td></td>
        </tr>

        <tr>
            <td colspan="5" class="font-weight-bold text-right">Total GST Amount:</td>
            <td class="text-right">
                <i class="fas fa-rupee-sign"></i>
                {{ number_format(\IndianIra\Utilities\Cart::totalGstAmount(), 2) }}
            </td>
            <td></td>
        </tr>

        <tr>
            <td colspan="5" class="font-weight-bold text-right">Grand Total:</td>
            <td class="text-right">
                <i class="fas fa-rupee-sign"></i>
                {{ number_format(\IndianIra\Utilities\Cart::grandTotalAmount(), 2) }}
            </td>
            <td></td>
        </tr>

        <tr>
            <td colspan="5" class="font-weight-bold text-right">
                Total Shipping:

                <a
                    href="#"
                    class="btn btn-sm btn-warning"
                    data-toggle="modal"
                    data-target="#selectLocationModal"
                >Select Location</a>
            </td>
            <td class="text-right">
                <i class="fas fa-rupee-sign"></i>
                {{ number_format(\IndianIra\Utilities\Cart::totalShippingAmount(), 2) }}
            </td>
            <td></td>
        </tr>

        <tr>
            <td colspan="5" class="font-weight-bold text-right">
                Apply Coupon Discount:
                <form
                    action="{{ route('cart.applyCoupon') }}"
                    method="POST"
                    id="formApplyCoupon"
                >
                    @csrf

                    <div class="text-right float-right">
                        <input
                            type="text"
                            name="couponCode"
                            value="{{ session('appliedDiscount') ? session('appliedDiscount')['coupon']->code : '' }}"
                            class="form-control-sm txtCouponCode"
                            placeholder="Apply Coupon Code"
                        />
                    </div>
                </form>
            </td>
            <td class="text-right">
                <i class="fas fa-rupee-sign"></i>
                @if (session('appliedDiscount'))
                    {{ number_format(session('appliedDiscount')['amount'], 2) }}
                @else
                    0.00
                @endif
            </td>
            <td class="text-center">
                @if (session('appliedDiscount'))
                    <a
                        href="{{ route('cart.removeCoupon') }}"
                        class="btn btn-outline-danger btn-sm btnRemove"
                        title="Remove the applied coupon"
                        data-toggle="tooltip"
                        data-placement="top"
                    >
                        <i class="fas fa-trash"></i>
                    </a>
                @endif
            </td>
        </tr>

        <tr style="font-size: 18px;">
            <td colspan="5" class="font-weight-bold text-right">
                Cart Total Payable
            </td>
            <td class="text-right">
                <i class="fas fa-rupee-sign"></i>
                {{ number_format(\IndianIra\Utilities\Cart::totalPayableAmount(), 2) }}
            </td>
            <td></td>
        </tr>
    </tbody>
</table>
