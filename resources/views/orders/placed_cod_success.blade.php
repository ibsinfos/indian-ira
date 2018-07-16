@extends('partials._layout')

@section('title_and_meta_info')
    <title>{{ config('app.name') }} | Order Successfully Placed</title>
    <meta name="description" content="{{ config('app.name') }}" />
    <meta name="keywords" content="{{ config('app.name') }}" />
@endsection

@section('content')
    <div class="container">
        <h1 class="display-6 font-weight-bold my-4 p-0" style="font-size: 30px;">
            Thank You for placing order with us.
        </h1>

        <p class="test-justify mt-4 mb-5" style="font-size: 15px;">
            Your Order Code:
            <span class="font-weight-bold">
                {{ session('codOrders')
                    ? session('codOrders')->first()->order_code
                    : ''
                }}
            </span>

            <br /><br />

            Order Payable Amount:
            <span class="font-weight-bold">
                <i class="fas fa-rupee-sign"></i>
                {{ session('codOrders')
                    ? number_format(session('codOrders')->first()->cart_total_payable_amount, 2)
                    : ''
                }}
            </span>
        </p>

        <div class="mb-5"></div>
    </div>

    @php
    \IndianIra\Utilities\Cart::empty();
    @endphp
@endsection
