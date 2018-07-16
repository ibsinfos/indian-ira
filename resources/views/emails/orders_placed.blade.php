<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Received</title>

    {{-- <link rel="stylesheet" href="{{ url('/css/app.css') }}" /> --}}


    <style>
        body {
            font-family: Arial;
            width: 100%;
            margin: 0;
            padding: 0;
            font-size: 16px;
            color: #000;
        }

        .text-right {
            text-align: right;
        }

        .container {
            margin: auto;
            width: 90%;
        }

        .text-center {
            text-align: center;
        }

        .mb-2 { margin-bottom: 8px; }
        .mb-3 { margin-bottom: 16px; }
        .mb-4 { margin-bottom: 25px; }
        .mr-3 { margin-right: 16px; }

        .img {
            vertical-align: middle;
        }

        .float-left { float: left; }

        .img-responsive {
            display: block;
            max-width: 100%;
            height: auto;
        }

        table {
            border-collapse: collapse;
            border: 1px solid #000;
        }

        table td {
            border: 1px solid #000;
            padding: 10px 20px;
        }

        .font-weight-bold {
            font-weight: bold;
        }

        .mainSiteLink {
            color: #00cbc3;
            outline: none;
            text-decoration: none;
        }

        .mainSiteLink:hover {
            text-decoration: none;
            outline: none;
            color: #00cbc3;
        }

        .systemGenerated {
            border-top: 1px dashed #ddd;
            font-size: 11px;
            color: #cb5e00;
            font-style: italic;
            padding-top: 5px;
        }
    </style>
</head>
<body class="bg-white">
    <div class="container">
        <p style="margin-bottom: 20px;">
            Dear {{ $user->getFullName() }},
        </p>

        <p style="margin-bottom: 20px;">
            You have successfully placed your order <span class="font-weight-bold">(Order Code: {{ $orders->first()->order_code }})</span>.
            Following are the details of your order.
        </p>

        <table  style="font-family: 'Athiti', sans-serif; margin: 0 auto; font-size: 14px; width: 100%">
            <thead>
                <th style="width: 40%">Product Details</th>
                <th class="text-center">Net Price</th>
                <th class="text-center">GST Amount with %</th>
                <th class="text-center">Rate</th>
                <th class="text-center">Qty</th>
                <th class="text-center">Amount</th>
            </thead>

            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td style="width: 20%; padding: 5px 5px; font-weight: bold;">
                            <img
                                src="{{ url('/images/no-image.jpg') }}"
                                alt="Image Not Found"
                                class="img-fluid float-left mr-3"
                            />

                            <div class="mb-2">
                                <a
                                    href="javascript:void(0)"
                                    class="mainSiteLink font-weight-bold"
                                    style="font-size: 15px;"
                                >
                                    {{ title_case($order->product_name) }}
                                </a>
                            </div>

                            <div class="mb-2">
                                {{ $order->product_code }}
                                @if ($order->product_number_of_options >= 1)
                                    / {{ $order->product_option_code }}
                                @endif
                            </div>

                            {{ number_format($order->product_weight, 2) }} grams
                        </td>

                        <td style="width: 20%; padding: 5px 5px; font-weight: bold; text-align: right">
                            <i class="fas fa-rupee-sign"></i>
                            {{ number_format($order->product_net_amount, 2) }}
                        </td>

                        <td style="width: 20%; padding: 5px 5px; font-weight: bold; text-align: right">
                            <i class="fas fa-rupee-sign"></i>
                            {{ number_format($order->product_gst_amount, 2) }} &#64; ({{ $order->product_gst_percent }}%)
                        </td>

                        <td style="width: 20%; padding: 5px 5px; font-weight: bold; text-align: right">
                            <i class="fas fa-rupee-sign"></i>
                            {{ number_format(($order->product_total_amount / $order->product_quantity), 2) }}
                        </td>

                        <td style="width: 10%">{{ $order->product_quantity }}</td>

                        <td style="width: 20%; padding: 5px 5px; font-weight: bold; text-align: right">
                            <i class="fas fa-rupee-sign"></i>
                            {{ number_format($order->product_total_amount, 2) }}
                        </td>
                    </tr>
                @endforeach

                <tr>
                    <td colspan="5" class="font-weight-bold text-right">Total Net Amount:</td>
                    <td style="width: 20%; padding: 5px 5px; font-weight: bold; text-align: right">
                        <i class="fas fa-rupee-sign"></i>
                        {{ number_format($orders->first()->cart_total_net_amount, 2) }}
                    </td>
                </tr>

                <tr>
                    <td colspan="5" class="font-weight-bold text-right">Total GST Amount:</td>
                    <td style="width: 20%; padding: 5px 5px; font-weight: bold; text-align: right">
                        <i class="fas fa-rupee-sign"></i>
                        {{ number_format($orders->first()->cart_total_gst_amount, 2) }}
                    </td>
                </tr>

                <tr>
                    <td colspan="5" class="font-weight-bold text-right">Grand Total:</td>
                    <td style="width: 20%; padding: 5px 5px; font-weight: bold; text-align: right">
                        <i class="fas fa-rupee-sign"></i>
                        {{ number_format(($orders->first()->cart_total_net_amount + $orders->first()->cart_total_gst_amount), 2) }}
                    </td>
                </tr>

                <tr>
                    <td colspan="5" class="font-weight-bold text-right">
                        Total Shipping:
                    </td>
                    <td style="width: 20%; padding: 5px 5px; font-weight: bold; text-align: right">
                        <i class="fas fa-rupee-sign"></i>
                        {{ number_format($orders->first()->cart_total_shipping_amount, 2) }}
                    </td>
                </tr>

                <tr>
                    <td colspan="5" class="font-weight-bold text-right">
                        COD Charges
                    </td>

                    <td style="width: 20%; padding: 5px 5px; font-weight: bold; text-align: right">
                        <i class="fas fa-rupee-sign"></i>
                        {{ number_format($orders->first()->cart_total_cod_amount, 2) }}
                    </td>
                </tr>

                <tr>
                    <td colspan="5" class="font-weight-bold text-right">
                        Coupon Discount:
                        {{ session('appliedDiscount') ? session('appliedDiscount')['coupon']->code : '' }}
                    </td>

                    <td style="width: 20%; padding: 5px 5px; font-weight: bold; text-align: right">
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
                    <td style="width: 20%; padding: 5px 5px; font-weight: bold; text-align: right">
                        <i class="fas fa-rupee-sign"></i>
                        {{ number_format($orders->first()->cart_total_payable_amount, 2) }}
                    </td>
                </tr>
            </tbody>
        </table>

        <div style="margin-bottom: 20px;"></div>

        Thank You.

        <div style="margin-bottom: 20px;"></div>

        <span class="font-weight-bold">Team {{ config('app.name') }}</span>

        <div style="margin-bottom: 20px;"></div>

        <div class="systemGenerated">
            This is a system generated E-Mail. Kindly do not reply to this E-Mail address.
        </div>
    </div>

    <div class="mb-4"></div>
</body>
</html>
