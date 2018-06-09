@extends('admin.partials._layout')

@section('title_and_meta_info')
    <title>Global Settings | Bank Details</title>
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
                <li class="breadcrumb-item active" aria-current="page">Global Settings - Bank Detail</li>
            </ol>
        </nav>

        <div class="row">
            <div class="mb-xl-5 col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="header p-0 m-0">
                            Global Settings | Bank Details

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
                            The bank details is required for the buyers to make payment while checking out if they choose the 'Offline' payment option as their preferred payment type. The buyer will make payment to the below mentioned bank details.
                        </p>

                        <form
                            action="{{ route('admin.globalSettings.bank.update') }}"
                            method="POST"
                            id="formUpdateBankDetails"
                            autocomplete="notWanted"
                        >
                            @csrf

                            <div class="row">
                                <div class="col-xl-4 col-lg-4 col-md-6">
                                    <div class="form-group">
                                        <label class="normal" for="account_holder_name">Account Holder Name:</label>
                                        <input
                                            type="text"
                                            name="account_holder_name"
                                            id="account_holder_name"
                                            value="{{ $bank != null ? $bank->account_holder_name : '' }}"
                                            class="form-control hasMaxLength"
                                            placeholder="Eg. {{ config('app.name') }}"
                                            required="required"
                                            autocomplete="nope"
                                            maxlength="200"
                                        />
                                    </div>
                                </div>

                                <div class="col-xl-4 col-lg-4 col-md-6">
                                    <div class="form-group">
                                        <label class="normal" for="account_type">Account Type:</label>
                                        <input
                                            type="text"
                                            name="account_type"
                                            id="account_type"
                                            value="{{ $bank != null ? $bank->account_type : '' }}"
                                            class="form-control hasMaxLength"
                                            placeholder="Eg. Current"
                                            required="required"
                                            autocomplete="nope"
                                            maxlength="200"
                                        />
                                    </div>
                                </div>

                                <div class="col-xl-4 col-lg-4 col-md-6">
                                    <div class="form-group">
                                        <label class="normal" for="account_number">Account Numer:</label>
                                        <input
                                            type="text"
                                            name="account_number"
                                            id="account_number"
                                            value="{{ $bank != null ? $bank->account_number : '' }}"
                                            class="form-control hasMaxLength"
                                            placeholder="Eg. {{ rand(1000000000, 9999999999) }}"
                                            required="required"
                                            autocomplete="nope"
                                            maxlength="50"
                                        />
                                    </div>
                                </div>

                                <div class="col-xl-4 col-lg-4 col-md-6">
                                    <div class="form-group">
                                        <label class="normal" for="bank_name">Bank Name:</label>
                                        <input
                                            type="text"
                                            name="bank_name"
                                            id="bank_name"
                                            value="{{ $bank != null ? $bank->bank_name : '' }}"
                                            class="form-control hasMaxLength"
                                            placeholder="Eg. Bank of India"
                                            required="required"
                                            autocomplete="nope"
                                            maxlength="200"
                                        />
                                    </div>
                                </div>

                                <div class="col-xl-4 col-lg-4 col-md-6">
                                    <div class="form-group">
                                        <label class="normal" for="bank_branch_and_city">Bank Branch And City:</label>
                                        <input
                                            type="text"
                                            name="bank_branch_and_city"
                                            id="bank_branch_and_city"
                                            value="{{ $bank != null ? $bank->bank_branch_and_city : '' }}"
                                            class="form-control hasMaxLength"
                                            placeholder="Eg. S.V. Road, Kandivali West, Mumbai"
                                            required="required"
                                            autocomplete="nope"
                                            maxlength="200"
                                        />
                                    </div>
                                </div>

                                <div class="col-xl-4 col-lg-4 col-md-6">
                                    <div class="form-group">
                                        <label class="normal" for="bank_ifsc_code">
                                            Bank IFSC Code:

                                            <span
                                                title="Abbreviated as Indian Financial System Code, IFSC is an 11-digit alphanumeric code that is used to identify the bank branches that participate in various electronic monetary transactions. It can be found either in bank passbook or on the chequebook."
                                                data-toggle="tooltip"
                                                data-placement="top"
                                            >
                                                <i class="fas fa-question-circle"></i>
                                            </span>
                                        </label>
                                        <input
                                            type="text"
                                            name="bank_ifsc_code"
                                            id="bank_ifsc_code"
                                            value="{{ $bank != null ? $bank->bank_ifsc_code : '' }}"
                                            class="form-control hasMaxLength"
                                            placeholder="Eg. {{ rand(1000000000, 9999999999) }}"
                                            required="required"
                                            autocomplete="nope"
                                            maxlength="20"
                                        />
                                    </div>
                                </div>
                            </div>

                            <div class="row">

                            </div>

                            <div class="errorsInUpdatingBankDetails"></div>

                            <button class="btn submitButton btnBankDetails mt-3">Submit</button>
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
        $('.btnBankDetails').click(function (e) {
            e.preventDefault();

            var form = $("#formUpdateBankDetails"),
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
                        displayAlertNotification(err, 'errorsInUpdatingBankDetails');
                    } else {
                        alert('Something went wrong. Please try again later.');
                    }
                },
            });

            return false;
        });
    </script>
@endsection
