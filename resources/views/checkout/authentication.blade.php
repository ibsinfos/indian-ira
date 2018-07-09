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
                        <li class="breadcrumb-item active" aria-current="page">Checkout: Authentication</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <h1 class="display-6 text-uppercase text-center mb-4" style="font-size: 30px;">
            Checkout Authentication

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
                @include('checkout._register')
            </div>

            <div class="col-md-6">
                @include('checkout._login')
            </div>
        </div>
    </div>

    <div class="mb-5"></div>
@endsection

@section('pageScripts')
    <script>
        $('.btnLoginUser').click(function (e) {
            e.preventDefault();

            var form = $("#formLoginUser"),
                self = $(this);

            self.prop('disabled', true);
            self.html('<i class="fas fa-spinner fa-spin"></i> Submitting...');

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                success: function (res) {
                    self.prop('disabled', false);
                    self.html('Submit');

                    displayGrowlNotification(res.status, res.title, res.message, res.delay);

                    if (res.status == 'success') {
                        setTimeout(function () {
                            window.location = res.location;
                        }, 4000);
                    } else {
                        $('#usernameOrEmail').focus();
                    }
                },
                error: function (err) {
                    self.prop('disabled', false);
                    self.html('Submit');

                    if ( err.status == 422) {
                        displayAlertNotification(err, 'errorsInLoggingUser');
                    } else {
                        alert('Something went wrong. Please try again later.');
                    }
                },
            });

            return false;
        });


        $('.btnRegisterUser').click(function (e) {
            e.preventDefault();

            var form = $("#formRegisterUser"),
                self = $(this);

            self.prop('disabled', true);
            self.html('<i class="fas fa-spinner fa-spin"></i> Submitting...');

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                success: function (res) {
                    self.prop('disabled', false);
                    self.html('Submit');

                    displayGrowlNotification(res.status, res.title, res.message, res.delay);

                    if (res.status == 'success') {
                        setTimeout(function () {
                            window.location = res.location;
                        }, 3500);
                    }
                },
                error: function (err) {
                    self.prop('disabled', false);
                    self.html('Submit');

                    if ( err.status == 422) {
                        displayAlertNotification(err, 'errorsInRegisteringUser');
                    } else {
                        alert('Something went wrong. Please try again later.');
                    }
                },
            });

            return false;
        });
    </script>
@endsection
