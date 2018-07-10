@extends('partials._layout')

@section('title_and_meta_info')
    <title>{{ config('app.name') }} | Checkout</title>
    <meta name="description" content="{{ config('app.name') }}" />
    <meta name="keywords" content="{{ config('app.name') }}" />
@endsection

@section('content')
    <div class="mt-4"></div>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <nav class="mb-4" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('homePage') }}" class="mainSiteLink">
                                Home
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Checkout: Pocess</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <h1 class="display-6 text-uppercase text-center mb-4" style="font-size: 30px;">
            Checkout

            <div class="float-none float-md-right">
                <a
                    href="{{ route('cart.show') }}"
                    class="btn btn-outline-dark btn-sm"
                >
                    Go back to Cart
                </a>
            </div>
        </h1>

        <div class="row">
            <div class="col-md-6">
                @include('checkout._billing')
            </div>

            <div class="col-md-6">
                @include('checkout._shipping')
            </div>
        </div>

        <div class="mb-5"></div>

        @include('checkout._choose_payment_method')

        <div class="mb-5"></div>

        <div class="card">
            <div class="card-header">
                <h2 class="header p-0 m-0" style="font-size: 22px;">
                    Confirm Your Cart
                </h2>
            </div>

            <div class="card-body px-0">
                @include('checkout._confirm_cart_table')
            </div>
        </div>
    </div>

    <div class="mb-5"></div>
@endsection

@section('pageScripts')
<script>
    var sameAsBillingAddress = $('#sameAsBillingAddress');
    if (sameAsBillingAddress.is(':checked')) {
        $('.shippingAddress input[type="text"]').prop('readonly', 'readonly');
    }

    $('.billingAddress #billing_full_name').keyup(function () {
        $('.shippingAddress #shipping_full_name').val($(this).val());
    });

    $('.billingAddress #address_line_1').keyup(function () {
        $('.shippingAddress #shipping_address_line_1').val($(this).val());
    });

    $('.billingAddress #address_line_2').keyup(function () {
        $('.shippingAddress #shipping_address_line_2').val($(this).val());
    });

    $('.billingAddress #area').keyup(function () {
        $('.shippingAddress #shipping_area').val($(this).val());
    });

    $('.billingAddress #landmark').keyup(function () {
        $('.shippingAddress #shipping_landmark').val($(this).val());
    });

    $('.billingAddress #pin_code').keyup(function () {
        $('.shippingAddress #shipping_pin_code').val($(this).val());
    });

    @if (session('shippingRateRecord') && session('shippingRateRecord')->location_type == 'City')
        $('.shippingAddress #shipping_city').prop('readonly', true)
                                            .val("{{session('shippingRateRecord')->location_name}}");
    @else
        $('.billingAddress #city').keyup(function () {
            $('.shippingAddress #shipping_city').val($(this).val());
        });
    @endif

    @if (session('shippingRateRecord') && session('shippingRateRecord')->location_type == 'State')
        $('.shippingAddress #shipping_state')
            .prop('readonly', true)
            .val("{{session('shippingRateRecord')->location_name}}");
    @else
        $('.billingAddress #state').keyup(function () {
            $('.shippingAddress #shipping_state').val($(this).val());
        });
    @endif

    @if (session('shippingRateRecord') && session('shippingRateRecord')->location_country == 'Country')
        $('.shippingAddress #shipping_country')
            .prop('readonly', true)
            .val("{{session('shippingRateRecord')->location_name}}");
    @else
        $('.billingAddress #country').keyup(function () {
            $('.shippingAddress #shipping_country').val($(this).val());
        });
    @endif

     $('.billingAddress #billing_contact_number').keyup(function () {
        $('.shippingAddress #shipping_contact_number').val($(this).val());
    });

    $('#sameAsBillingAddress').change(function () {
        $('.shippingAddress input[type="text"]').prop('readonly', false);

        if ($(this).is(':checked')) {
            $('.shippingAddress input[type="text"]').prop('readonly', true);
        }

        @if (session('shippingRateRecord') && session('shippingRateRecord')->location_type == 'City')
            $('.shippingAddress #shipping_city').prop('readonly', true)
                                                .val("{{session('shippingRateRecord')->location_name}}");
        @else
            $('.billingAddress #city').keyup(function () {
                $('.shippingAddress #shipping_city').val($(this).val());
            });
        @endif

        @if (session('shippingRateRecord') && session('shippingRateRecord')->location_type == 'State')
            $('.shippingAddress #shipping_state')
                .prop('readonly', true)
                .val("{{session('shippingRateRecord')->location_name}}");
        @else
            $('.billingAddress #state').keyup(function () {
                $('.shippingAddress #shipping_state').val($(this).val());
            });
        @endif

        @if (session('shippingRateRecord') && session('shippingRateRecord')->location_country == 'Country')
            $('.shippingAddress #shipping_country')
                .prop('readonly', true)
                .val("{{session('shippingRateRecord')->location_name}}");
        @else
            $('.billingAddress #country').keyup(function () {
                $('.shippingAddress #shipping_country').val($(this).val());
            });
        @endif
    });

    $('input[type="radio"]').on('change', function () {
        var self = $(this);

        $.ajax({
            url: "{{ route('checkout.addCodCharges') }}",
            type: 'GET',
            data: "_token={{ csrf_token() }}&payment_method=" + self.val(),
            success: function (res) {
                $('.cartTable').html(res.htmlResult);
            }
        });
    });
</script>
@endsection
