<table class="table table-bordered cartTable" style="font-size: 13px;">
    <thead>
        <th style="width: 40%">Product Details</th>
        <th class="text-center">Net Price</th>
        <th class="text-center">GST Amount with %</th>
        <th class="text-center">Rate</th>
        <th class="text-center">Qty</th>
        <th class="text-center">Amount</th>
    </thead>

    <tbody>
        @forelse ($cart as $code => $row)
            @php
            $gstAmount = ($row['options']->selling_price * ($row['product']->gst_percent / 100));
            $netPrice = $row['options']->selling_price - $gstAmount;
            @endphp

            <tr>
                <td>
                    @if ($row['options']->hasUploadedImageFile())
                        <img
                            src="{{ url($row['options']->cartImage()) }}"
                            alt="{{ $row['product']->name }}"
                            class="img-fluid float-left mr-3"
                        >
                    @elseif($row['product']->hasUploadedImageFile())
                        <img
                            src="{{ url($row['product']->cartImage()) }}"
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
                    {{ number_format($row['options']->selling_price, 2) }}
                </td>

                <td style="width: 10%">
                    {{ $row['quantity'] }}
                </td>

                <td class="text-right">
                    <i class="fas fa-rupee-sign"></i>
                    {{ number_format($row['product_total'], 2) }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6">No data available</td>
            </tr>
        @endforelse

        <tr>
            <td colspan="5" class="font-weight-bold text-right">Total Net Amount:</td>
            <td class="text-right">
                <i class="fas fa-rupee-sign"></i>
                {{ number_format(\IndianIra\Utilities\Cart::totalNetAmount(), 2) }}
            </td>
        </tr>

        <tr>
            <td colspan="5" class="font-weight-bold text-right">Total GST Amount:</td>
            <td class="text-right">
                <i class="fas fa-rupee-sign"></i>
                {{ number_format(\IndianIra\Utilities\Cart::totalGstAmount(), 2) }}
            </td>
        </tr>

        <tr>
            <td colspan="5" class="font-weight-bold text-right">Grand Total:</td>
            <td class="text-right">
                <i class="fas fa-rupee-sign"></i>
                {{ number_format(\IndianIra\Utilities\Cart::grandTotalAmount(), 2) }}
            </td>
        </tr>

        <tr>
            <td colspan="5" class="font-weight-bold text-right">
                Total Shipping:
            </td>
            <td class="text-right">
                <i class="fas fa-rupee-sign"></i>
                {{ number_format(\IndianIra\Utilities\Cart::totalShippingAmount(), 2) }}
            </td>
        </tr>

        <tr>
            <td colspan="5" class="font-weight-bold text-right">
                COD Charges
            </td>

            <td class="text-right">
                <i class="fas fa-rupee-sign"></i>
                {{ number_format(\IndianIra\Utilities\Cart::codCharges(), 2) }}
            </td>
        </tr>

        <tr>
            <td colspan="5" class="font-weight-bold text-right">
                Apply Coupon Discount:
                {{ session('appliedDiscount') ? session('appliedDiscount')['coupon']->code : '' }}
            </td>

            <td class="text-right">
                <i class="fas fa-rupee-sign"></i>
                @if (session('appliedDiscount'))
                    {{ number_format(session('appliedDiscount')['amount'], 2) }}
                @else
                    0.00
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
        </tr>
    </tbody>
</table>
