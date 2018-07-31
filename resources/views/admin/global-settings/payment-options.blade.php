@extends('admin.partials._layout')

@section('title_and_meta_info')
    <title>Global Settings | Payment Options</title>
@endsection

@section('pageStyles')
    <style>
        textarea {
            resize: none !important;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <nav class="mb-4" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard') }}" class="mainSiteLink">
                        Dashboard
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Global Settings - Payment Option</li>
            </ol>
        </nav>

        <div class="row">
            <div class="mb-xl-5 col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="header p-0 m-0">
                            Global Settings | Payment Options

                            <div class="float-right">
                                <a
                                    href="{{ route('admin.dashboard') }}"
                                    class="btn btn-outline-light btn-sm font-weight-bold shadow-none mt-md-0 mt-lg-0 mt-xl-0 mt-sm-4"
                                >Go to Dashboard</a>
                            </div>
                        </h3>
                    </div>

                    <div class="card-body">
                        <p class="text-danger text-justify mb-5">
                            Choose any or all of them. This will help the buyer to choose their preferred payment type while checking out.
                        </p>

                        <form
                            action="{{ route('admin.globalSettings.paymentOptions.update') }}"
                            method="POST"
                            id="formUpdatePaymentOptions"
                            autocomplete="notWanted"
                        >
                            @csrf

                            <div class="form-group">
                                <div class="form-check">
                                    <label class="form-check-label" for="chosenOnline">
                                        <input
                                            type="checkbox"
                                            name="chosen[]"
                                            id="chosenOnline"
                                            value="online"
                                            class="form-check-input"
                                            @if (in_array('online', $alreadyChosen))
                                                checked="checked"
                                            @endif
                                        >
                                        Online
                                        <small class="form-text text-muted">
                                            (For making payments via Credit Card / Debit Card / Net Banking, etc)
                                        </small>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-check">
                                    <label class="form-check-label" for="chosenOffline">
                                        <input
                                            type="checkbox"
                                            name="chosen[]"
                                            id="chosenOnline"
                                            value="offline"
                                            class="form-check-input"
                                            @if (in_array('offline', $alreadyChosen))
                                                checked="checked"
                                            @endif
                                        >
                                        Offline
                                        <small class="form-text text-muted">
                                            (For making payments via Cheque / Demand Draft (DD) / Direct Bank Transfer, etc)
                                        </small>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-check">
                                    <label class="form-check-label" for="chosenCod">
                                        <input
                                            type="checkbox"
                                            name="chosen[]"
                                            id="chosenCod"
                                            value="cod"
                                            class="form-check-input"
                                            @if (in_array('cod', $alreadyChosen))
                                                checked="checked"
                                            @endif
                                        >
                                        COD (Cash On Delivery)
                                        <small class="form-text text-muted">
                                            (The buyer will have to pay extra charges if they choose COD as their preferred payment type.)
                                        </small>
                                    </label>
                                </div>
                            </div>

                            <div class="mb-4"></div>

                            <div class="form-group">
                                <label class="normal" for="other_payment_options">
                                    Other Payment Options (Optional):
                                </label>
                                <textarea
                                    name="other_payment_options"
                                    id="other_payment_options"
                                    class="form-control hasMaxlength"
                                    maxlength="200"
                                    rows="5"
                                    cols="8"
                                >{{ $paymentOptions != null ? $paymentOptions->other_payment_options : '' }}</textarea>
                            </div>

                            <div class="errorsInUpdatingPaymentOptions"></div>

                            <button class="btn submitButton btnPaymentOptions mt-3">Submit</button>
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
        $('.btnPaymentOptions').click(function (e) {
            e.preventDefault();

            var form = $("#formUpdatePaymentOptions"),
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
                    }
                },
                error: function (err) {
                    self.prop('disabled', false);
                    self.html('Submit');

                    if ( err.status == 422) {
                        displayAlertNotification(err, 'errorsInUpdatingPaymentOptions');
                    } else {
                        alert('Something went wrong. Please try again later.');
                    }
                },
            });

            return false;
        });
    </script>
@endsection
