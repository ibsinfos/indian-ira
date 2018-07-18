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
        @foreach ($orders as $row)
            <tr>
                <td>
                    <img
                        src="{{ url($row->product_cart_image) }}"
                        alt="{{ $row->product_name }}"
                        class="img-fluid float-left mr-3"
                    />

                    <div class="mb-2">
                        <a
                            href="{{ $row->product_page_url }}"
                            class="mainSiteLink font-weight-bold"
                            style="font-size: 15px;"
                        >
                            {{ title_case($row->product_name) }}
                        </a>
                    </div>

                    <div class="mb-2">
                        {{ $row->product_code }}
                        @if ($row->product_number_of_options >= 1)
                            / {{ $row->product_option_code }}
                        @endif
                    </div>

                    {{ number_format($row->product_weight, 2) }} grams
                </td>

                <td class="text-right">
                    <i class="fas fa-rupee-sign"></i>
                    {{ number_format($row->product_net_amount, 2) }}
                </td>

                <td class="text-right">
                    <i class="fas fa-rupee-sign"></i>
                    {{ number_format($row->product_gst_amount, 2) }} @
                    {{ $row->product_gst_percent }}%
                </td>

                <td class="text-right">
                    <i class="fas fa-rupee-sign"></i>
                    @if ($row->product_discount_price > 0)
                        {{ number_format($row->product_discount_price, 2) }}
                    @else
                        {{ number_format($row->product_selling_price, 2) }}
                    @endif
                </td>

                <td class="text-center">{{ $row->product_quantity }}</td>

                <td class="text-right">
                    <i class="fas fa-rupee-sign"></i>
                    {{ number_format($row->product_total_amount, 2) }}
                </td>
            </tr>
        @endforeach

        <tr>
            <td colspan="5" class="font-weight-bold text-right">Total Net Amount:</td>
            <td class="text-right">
                <i class="fas fa-rupee-sign"></i>
                {{ number_format($orders->first()->cart_total_net_amount, 2) }}
            </td>
        </tr>

        <tr>
            <td colspan="5" class="font-weight-bold text-right">Total GST Amount:</td>
            <td class="text-right">
                <i class="fas fa-rupee-sign"></i>
                {{ number_format($orders->first()->cart_total_gst_amount, 2) }}
            </td>
        </tr>

        <tr>
            <td colspan="5" class="font-weight-bold text-right">Grand Total:</td>
            <td class="text-right">
                <i class="fas fa-rupee-sign"></i>
                {{ number_format(($orders->first()->cart_total_net_amount + $orders->first()->cart_total_gst_amount), 2) }}
            </td>
        </tr>

        <tr>
            <td colspan="5" class="font-weight-bold text-right">
                Total Shipping:
            </td>
            <td class="text-right">
                <i class="fas fa-rupee-sign"></i>
                {{ number_format($orders->first()->cart_total_shipping_amount, 2) }}
            </td>
        </tr>

        <tr>
            <td colspan="5" class="font-weight-bold text-right">
                COD Charges
            </td>

            <td class="text-right">
                <i class="fas fa-rupee-sign"></i>
                {{ number_format($orders->first()->cart_total_cod_amount, 2) }}
            </td>
        </tr>

        <tr>
            <td colspan="5" class="font-weight-bold text-right">
                Coupon Discount:
                {{ $orders->first()->coupon_code }}
            </td>

            <td class="text-right">
                <i class="fas fa-rupee-sign"></i>
                {{ number_format($orders->first()->cart_coupon_amount, 2) }}
            </td>
        </tr>

        <tr style="font-size: 18px;">
            <td colspan="5" class="font-weight-bold text-right">
                Cart Total Payable
            </td>
            <td class="text-right">
                <i class="fas fa-rupee-sign"></i>
                {{ number_format($orders->first()->cart_total_payable_amount, 2) }}
            </td>
        </tr>
    </tbody>
</table>
