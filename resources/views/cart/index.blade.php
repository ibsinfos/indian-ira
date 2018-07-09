@extends('partials._layout')

@section('title_and_meta_info')
    <title>{{ config('app.name') }} | Cart</title>
    <meta name="description" content="{{ config('app.name') }}" />
    <meta name="keywords" content="{{ config('app.name') }}" />
@endsection

@section('content')
    <div class="container mt-4">
        <h1 class="display-6 text-uppercase text-center mb-4" style="font-size: 30px;">
            Cart

            <div class="float-none float-md-right">
                <a
                    href="{{ route('cart.empty') }}"
                    class="btn btn-outline-dark btn-sm btnRemove"
                    title="Remove all the products from the cart"
                    data-toggle="tooltip"
                >
                    Empty Cart
                </a>
            </div>
        </h1>

        <div class="table-responsive">
            @include('cart.table')
        </div>

        <div class="float-right">
            <a href="{{ route('checkout') }}" class="btn btn-success font-weight-bold btnProceedToCheckout">
                Proceed To Checkout
            </a>
        </div>
    </div>

    <div class="mb-5"></div>
@endsection

@section('pageScripts')
    <script>
        $('body').on('focus', '.txtUpdateQty', function (e) {
            $(this).select();
        });

        var timeout = null;
        $('body').on('keyup', '.txtUpdateQty', function (e) {
            e.preventDefault();

            var self = $(this)
                code = self.data('code')
                urlAction = self.data('action');

            clearTimeout(timeout);

            timeout = setTimeout(function () {
                if ((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 96 && e.keyCode <= 105)) {
                    $.ajax({
                        url: urlAction,
                        type: 'POST',
                        data: '_token={{ csrf_token() }}&quantity=' + (self.val()),
                        success: function (res) {
                            displayGrowlNotification(res.status, res.title, res.message, res.delay);

                            $('.cartTable').html(res.htmlResult);
                        },
                        error: function (err) {
                            $('span#'+code+'.error').html(err.responseText);
                        },
                    });
                }
            }, 300);
        });

        $('body').on('click', '.btnRemove', function (e) {
            e.preventDefault();

            var self = $(this);

            if (confirm('Are you sure that you want to proceed? This cannot be undone.')) {
                $.ajax({
                    url: self.attr('href'),
                    type: 'GET',
                    success: function (res) {
                        $('[data-toggle="tooltip"]').tooltip('hide');

                        displayGrowlNotification(res.status, res.title, res.message, res.delay);

                        $('.cartTable').html(res.htmlResult);

                        if (res.location) {
                            setTimeout(function () {
                                window.location = res.location;
                            }, res.delay + 1000);
                        }
                    },
                    error: function (err) {
                        alert('Something went wrong. Please try again later.');
                    },
                });
            }

            return false;
        });

        var timeout = null;
        $('.txtCouponCode').on('keyup', function (e) {
            e.preventDefault();

            clearTimeout(timeout);

            timeout = setTimeout(function () {
                $.ajax({
                    url: "{{ route('cart.applyCoupon') }}",
                    type: 'POST',
                    data: $('#formApplyCoupon').serialize(),
                    success: function (res) {
                        displayGrowlNotification(res.status, res.title, res.message, res.delay);

                        $('.cartTable').html(res.htmlResult);
                    },
                    error: function (err) {
                        displayGrowlNotification(
                            'failed',
                            'Failed !',
                            err.responseJSON.errors.couponCode[0],
                            3000
                        );
                    },
                });
            }, 1000);
        });
    </script>
@endsection
