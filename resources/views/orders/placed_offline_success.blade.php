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

        <p class="test-justify mt-4 mb-5">
            You have successfully placed the order. Kindly make the payment to {{ config('app.name') }} at the following bank details.
        </p>

        <p class="test-justify mt-4 mb-5" style="font-size: 15px;">
            Your Order Code:
            <span class="font-weight-bold">{{ session('offlineOrders')->first()->order_code }}</span><br /><br />
            Order Payable Amount:
            <span class="font-weight-bold">
                <i class="fas fa-rupee-sign"></i>
                {{ number_format(session('offlineOrders')->first()->cart_total_payable_amount, 2) }}
            </span>
        </p>

        <h2 class="display-6 font-weight-bold my-4 p-0" style="font-size: 24px;">
            {{ config('app.name') }}'s Bank Details
        </h2>

        <div class="mb-4">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <tr>
                        <th class="font-weight-normal">Account Holder Name:</th>
                        <td>{{ $bank->account_holder_name }}</td>
                    </tr>
                    <tr>
                        <th class="font-weight-normal">Account Type:</th>
                        <td>{{ $bank->account_type }}</td>
                    </tr>
                    <tr>
                        <th class="font-weight-normal">Account Number:</th>
                        <td>{{ $bank->account_number }}</td>
                    </tr>
                    <tr>
                        <th class="font-weight-normal">Bank Name:</th>
                        <td>{{ $bank->bank_name }}</td>
                    </tr>
                    <tr>
                        <th class="font-weight-normal">Bank branch and City:</th>
                        <td>{{ $bank->bank_branch_and_city }}</td>
                    </tr>
                    <tr>
                        <th class="font-weight-normal">IFSC Code:</th>
                        <td>{{ $bank->bank_ifsc_code }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <p class="text-justify text-danger">
            Note: Your order won't get processed until you make the payment to the above mentioned bank details. Once you have made the payment, kindly inform us on
            <a href="mailto:orders&#64;indianira.com" class="mainSiteLink-invert font-weight-bold">orders&#64;indianira.com</a>
        </p>

        <div class="mb-5"></div>
    </div>
@endsection
