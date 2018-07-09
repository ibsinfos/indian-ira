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
                <div class="card">
                    <div class="card-header">
                        <h2 class="header p-0 m-0" style="font-size: 22px;">
                            Billing And Shipping Address
                        </h2>
                    </div>

                    <div class="card-body" style="border: 1px solid #ddd">
                        <form
                            action="{{ route('checkout.postLogin') }}"
                            method="POST"
                            id="formLoginUser"
                            autocomplete="notWanted"
                        >
                            @csrf

                            <div class="billingAddress">

                                <h5 class="p-0 m-0 text-center font-weight-bold">
                                    Billing Address
                                </h5>

                                <div class="form-group">
                                    <label class="normal" for="full_name">Full Name:</label>
                                    <input
                                        type="text"
                                        name="full_name"
                                        id="full_name"
                                        value="{{ $user->getFullName() }}"
                                        class="form-control hasMaxLength"
                                        placeholder="Eg. Hojn Doe"
                                        required="required"
                                        autocomplete="nope"
                                        maxlength="200"
                                    />
                                </div>

                                <div class="form-group">
                                    <label class="normal" for="address_line_1">Address Line 1:</label>
                                    <input
                                        type="text"
                                        name="address_line_1"
                                        id="address_line_1"
                                        value="{{ $billingAddress != null ? $billingAddress->address_line_1 : '' }}"
                                        class="form-control hasMaxLength"
                                        placeholder="Eg. Flat No., Building Name"
                                        required="required"
                                        autocomplete="nope"
                                        maxlength="100"
                                    />
                                </div>

                                <div class="form-group">
                                    <label class="normal" for="address_line_2">Address Line 2: (Optional)</label>
                                    <input
                                        type="text"
                                        name="address_line_2"
                                        id="address_line_2"
                                        value="{{ $billingAddress != null ? $billingAddress->address_line_2 : '' }}"
                                        class="form-control hasMaxLength"
                                        placeholder="Eg. Street Name"
                                        autocomplete="nope"
                                        maxlength="100"
                                    />
                                </div>

                                <div class="form-group">
                                    <label class="normal" for="area">Area:</label>
                                    <input
                                        type="text"
                                        name="area"
                                        id="area"
                                        value="{{ $billingAddress != null ? $billingAddress->area : '' }}"
                                        class="form-control hasMaxLength"
                                        placeholder="Eg. Station Name"
                                        required="required"
                                        autocomplete="nope"
                                        maxlength="100"
                                    />
                                </div>

                                <div class="form-group">
                                    <label class="normal" for="landmark">Landmark (Optional):</label>
                                    <input
                                        type="text"
                                        name="landmark"
                                        id="landmark"
                                        value="{{ $billingAddress != null ? $billingAddress->landmark : '' }}"
                                        class="form-control hasMaxLength"
                                        placeholder="Eg. Some Famous Restaurant"
                                        autocomplete="nope"
                                        maxlength="100"
                                    />
                                </div>

                                <div class="form-group">
                                    <label class="normal" for="city">City:</label>
                                    <input
                                        type="text"
                                        name="city"
                                        id="city"
                                        value="{{ $billingAddress != null ? $billingAddress->city : '' }}"
                                        class="form-control hasMaxLength"
                                        placeholder="Eg. Mumbai"
                                        required="required"
                                        autocomplete="nope"
                                        maxlength="100"
                                    />
                                </div>

                                <div class="form-group">
                                    <label class="normal" for="pin_code">Pin / Zip Code:</label>
                                    <input
                                        type="text"
                                        name="pin_code"
                                        id="pin_code"
                                        value="{{ $billingAddress != null ? $billingAddress->pin_code : '' }}"
                                        class="form-control hasMaxLength"
                                        placeholder="Eg. 400034"
                                        required="required"
                                        autocomplete="nope"
                                        maxlength="100"
                                    />
                                </div>

                                <div class="form-group">
                                    <label class="normal" for="state">State:</label>
                                    <input
                                        type="text"
                                        name="state"
                                        id="state"
                                        value="{{ $billingAddress != null ? $billingAddress->state : '' }}"
                                        class="form-control hasMaxLength"
                                        placeholder="Eg. Maharashtra"
                                        required="required"
                                        autocomplete="nope"
                                        maxlength="100"
                                    />
                                </div>

                                <div class="form-group">
                                    <label class="normal" for="country">Country:</label>
                                    <input
                                        type="text"
                                        name="country"
                                        id="country"
                                        value="{{ $billingAddress != null ? $billingAddress->country : '' }}"
                                        class="form-control hasMaxLength"
                                        placeholder="Eg. India"
                                        required="required"
                                        autocomplete="nope"
                                        maxlength="100"
                                    />
                                </div>

                                <div class="form-group">
                                    <label class="normal" for="contact_number">Contact Number:</label>
                                    <input
                                        type="text"
                                        name="contact_number"
                                        id="contact_number"
                                        value="{{ $user->contact_number }}"
                                        class="form-control hasMaxLength"
                                        placeholder="Eg. India"
                                        required="required"
                                        autocomplete="nope"
                                        maxlength="100"
                                    />
                                </div>

                                <div class="form-group">
                                    <input
                                        type="checkbox"
                                        name="sameAsBillingAddress"
                                        id="sameAsBillingAddress"
                                        value="yes"
                                        checked="checked"
                                    >
                                    Shipping Address Same as Billing Address
                                </div>
                            </div>

                            ----

                            <div class="shippingAddress">

                                <h5 class="p-0 m-0 text-center font-weight-bold">
                                    Shipping Address
                                </h5>

                                <div class="form-group">
                                    <label class="normal" for="shipping_full_name">Full Name:</label>
                                    <input
                                        type="text"
                                        name="shipping_full_name"
                                        id="shipping_full_name"
                                        value="{{ $user->getFullName() }}"
                                        class="form-control hasMaxLength"
                                        placeholder="Eg. Hojn Doe"
                                        required="required"
                                        autocomplete="nope"
                                        maxlength="200"
                                    />
                                </div>

                                <div class="form-group">
                                    <label class="normal" for="shipping_address_line_1">Address Line 1:</label>
                                    <input
                                        type="text"
                                        name="shipping_address_line_1"
                                        id="shipping_address_line_1"
                                        value="{{ $billingAddress != null ? $billingAddress->address_line_1 : '' }}"
                                        class="form-control hasMaxLength"
                                        placeholder="Eg. Flat No., Building Name"
                                        required="required"
                                        autocomplete="nope"
                                        maxlength="100"
                                    />
                                </div>

                                <div class="form-group">
                                    <label class="normal" for="shipping_address_line_2">Address Line 2: (Optional)</label>
                                    <input
                                        type="text"
                                        name="shipping_address_line_2"
                                        id="shipping_address_line_2"
                                        value="{{ $billingAddress != null ? $billingAddress->address_line_2 : '' }}"
                                        class="form-control hasMaxLength"
                                        placeholder="Eg. Street Name"
                                        autocomplete="nope"
                                        maxlength="100"
                                    />
                                </div>

                                <div class="form-group">
                                    <label class="normal" for="shipping_area">Area:</label>
                                    <input
                                        type="text"
                                        name="shipping_area"
                                        id="shipping_area"
                                        value="{{ $billingAddress != null ? $billingAddress->area : '' }}"
                                        class="form-control hasMaxLength"
                                        placeholder="Eg. Station Name"
                                        required="required"
                                        autocomplete="nope"
                                        maxlength="100"
                                    />
                                </div>

                                <div class="form-group">
                                    <label class="normal" for="shipping_landmark">Landmark (Optional):</label>
                                    <input
                                        type="text"
                                        name="shipping_landmark"
                                        id="shipping_landmark"
                                        value="{{ $billingAddress != null ? $billingAddress->landmark : '' }}"
                                        class="form-control hasMaxLength"
                                        placeholder="Eg. Some Famous Restaurant"
                                        autocomplete="nope"
                                        maxlength="100"
                                    />
                                </div>

                                <div class="form-group">
                                    <label class="normal" for="shipping_city">City:</label>
                                    <input
                                        type="text"
                                        name="shipping_city"
                                        id="shipping_city"
                                        value="{{ $billingAddress != null ? $billingAddress->city : '' }}"
                                        class="form-control hasMaxLength"
                                        placeholder="Eg. Mumbai"
                                        required="required"
                                        autocomplete="nope"
                                        maxlength="100"
                                    />
                                </div>

                                <div class="form-group">
                                    <label class="normal" for="shipping_pin_code">Pin / Zip Code:</label>
                                    <input
                                        type="text"
                                        name="shipping_pin_code"
                                        id="shipping_pin_code"
                                        value="{{ $billingAddress != null ? $billingAddress->pin_code : '' }}"
                                        class="form-control hasMaxLength"
                                        placeholder="Eg. 400034"
                                        required="required"
                                        autocomplete="nope"
                                        maxlength="100"
                                    />
                                </div>

                                <div class="form-group">
                                    <label class="normal" for="shipping_state">State:</label>
                                    <input
                                        type="text"
                                        name="shipping_state"
                                        id="shipping_state"
                                        value="{{ $billingAddress != null ? $billingAddress->state : '' }}"
                                        class="form-control hasMaxLength"
                                        placeholder="Eg. Maharashtra"
                                        required="required"
                                        autocomplete="nope"
                                        maxlength="100"
                                    />
                                </div>

                                <div class="form-group">
                                    <label class="normal" for="shipping_country">Country:</label>
                                    <input
                                        type="text"
                                        name="shipping_country"
                                        id="shipping_country"
                                        value="{{ $billingAddress != null ? $billingAddress->country : '' }}"
                                        class="form-control hasMaxLength"
                                        placeholder="Eg. India"
                                        required="required"
                                        autocomplete="nope"
                                        maxlength="100"
                                    />
                                </div>

                                <div class="form-group">
                                    <label class="normal" for="shipping_contact_number">Contact Number:</label>
                                    <input
                                        type="text"
                                        name="shipping_contact_number"
                                        id="shipping_contact_number"
                                        value="{{ $user->contact_number }}"
                                        class="form-control hasMaxLength"
                                        placeholder="Eg. India"
                                        required="required"
                                        autocomplete="nope"
                                        maxlength="100"
                                    />
                                </div>
                            </div>

                            <div class="errorsInLoggingUser"></div>

                            <button class="btn submitButton btnLoginUser">Submit</button>
                        </form>
                    </div>
                </div>
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

    $('.billingAddress #city').keyup(function () {
        $('.shippingAddress #shipping_city').val($(this).val());
    });

    $('.billingAddress #pin_code').keyup(function () {
        $('.shippingAddress #shipping_pin_code').val($(this).val());
    });

    $('.billingAddress #state').keyup(function () {
        $('.shippingAddress #shipping_state').val($(this).val());
    });

    $('.billingAddress #country').keyup(function () {
        $('.shippingAddress #shipping_country').val($(this).val());
    });

     $('.billingAddress #billing_contact_number').keyup(function () {
        $('.shippingAddress #shipping_contact_number').val($(this).val());
    });

    $('#sameAsBillingAddress').change(function () {
        $('.shippingAddress input[type="text"]').prop('readonly', false);

        if ($(this).is(':checked')) {
            $('.shippingAddress input[type="text"]').prop('readonly', true);
        }
    });
</script>
@endsection
