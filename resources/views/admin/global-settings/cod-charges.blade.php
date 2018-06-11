@extends('admin.partials._layout')

@section('title_and_meta_info')
    <title>Global Settings | COD Charges</title>
@endsection

@section('pageStyles')
    <style>
        @media screen and (min-width: 768px) {
            .submitButton {
                margin-top: 32px !important;
            }
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
                <li class="breadcrumb-item active" aria-current="page">Global Settings - COD Charge</li>
            </ol>
        </nav>

        <div class="row">
            <div class="mb-xl-5 col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="header p-0 m-0">
                            Global Settings | COD Charges

                            <div class="float-right">
                                <a
                                    href="{{ route('admin.dashboard') }}"
                                    class="btn btn-light btn-sm font-weight-bold shadow-none mt-md-0 mt-lg-0 mt-xl-0 mt-sm-4"
                                >Go to Dashboard</a>
                            </div>
                        </h3>
                    </div>

                    <div class="card-body">
                        <p class="text-danger font-weight-bold text-justify mb-5">
                            The below amount will be paid by the buyer to the courier delivery person as COD Charges. This amount will get added in the cart if the buyer chooses COD as their preferred payment option while checking out.
                        </p>

                        <form
                            action="{{ route('admin.globalSettings.codCharges.update') }}"
                            method="POST"
                            id="formUpdateCodCharges"
                            autocomplete="notWanted"
                        >
                            @csrf

                            <div class="errorsInUpdatingCodCharges"></div>

                            <div class="row">
                                <div class="col-xl-6 col-lg-6 col-md-6">
                                    <div class="form-group">
                                        <label class="normal" for="amount">Cod Charges Amount:</label>
                                        <input
                                            type="text"
                                            name="amount"
                                            id="amount"
                                            value="{{ $codCharges != null ? $codCharges->amount : '' }}"
                                            class="form-control"
                                            placeholder="Eg. 50.00"
                                            required="required"
                                            autocomplete="nope"
                                        />
                                    </div>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6">
                                    <button class="btn submitButton btnCodCharges mt-4">Submit</button>
                                </div>
                            </div>
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
        $('.btnCodCharges').click(function (e) {
            e.preventDefault();

            var form = $("#formUpdateCodCharges"),
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
                        displayAlertNotification(err, 'errorsInUpdatingCodCharges');
                    } else {
                        alert('Something went wrong. Please try again later.');
                    }
                },
            });

            return false;
        });
    </script>
@endsection
