@extends('admin.partials._layout')

@section('title_and_meta_info')
    <title>Global Settings | Company Address</title>
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
                <li class="breadcrumb-item active" aria-current="page">Global Settings - Company Address</li>
            </ol>
        </nav>

        <div class="row">
            <div class="mb-xl-5 col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="header p-0 m-0">
                            Global Settings | Company Address

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
                            The company address is the location where the buyers should send / deliver the Cheque / Demand Draft (D.D) if they chose 'Offline' Payment Option at the time of check out.

                            <br /><br />

                            All the fields below may contain only alphabets, numbers, hyphens (-), underscores (_), period (.), comma (,), apostrophe (') and forward slash (/)
                        </p>

                        <form
                            action="{{ route('admin.globalSettings.companyAddress.update') }}"
                            method="POST"
                            id="formUpdateCompanyAddress"
                            autocomplete="notWanted"
                        >
                            @csrf

                            <div class="row">
                                <div class="col-xl-4 col-lg-4 col-md-6">
                                    <div class="form-group">
                                        <label class="normal" for="address_line_1">Address Line 1:</label>
                                        <input
                                            type="text"
                                            name="address_line_1"
                                            id="address_line_1"
                                            value="{{ $companyAddress != null ? $companyAddress->address_line_1 : '' }}"
                                            class="form-control hasMaxLength"
                                            placeholder="Eg. Flat No., Building Name"
                                            required="required"
                                            autocomplete="nope"
                                            maxlength="100"
                                        />
                                    </div>
                                </div>

                                <div class="col-xl-4 col-lg-4 col-md-6">
                                    <div class="form-group">
                                        <label class="normal" for="address_line_2">Address Line 2: (Optional)</label>
                                        <input
                                            type="text"
                                            name="address_line_2"
                                            id="address_line_2"
                                            value="{{ $companyAddress != null ? $companyAddress->address_line_2 : '' }}"
                                            class="form-control hasMaxLength"
                                            placeholder="Eg. Street Name"
                                            autocomplete="nope"
                                            maxlength="100"
                                        />
                                    </div>
                                </div>

                                <div class="col-xl-4 col-lg-4 col-md-6">
                                    <div class="form-group">
                                        <label class="normal" for="area">Area:</label>
                                        <input
                                            type="text"
                                            name="area"
                                            id="area"
                                            value="{{ $companyAddress != null ? $companyAddress->area : '' }}"
                                            class="form-control hasMaxLength"
                                            placeholder="Eg. Station Name"
                                            required="required"
                                            autocomplete="nope"
                                            maxlength="100"
                                        />
                                    </div>
                                </div>

                                <div class="col-xl-4 col-lg-4 col-md-6">
                                    <div class="form-group">
                                        <label class="normal" for="landmark">Landmark (Optional):</label>
                                        <input
                                            type="text"
                                            name="landmark"
                                            id="landmark"
                                            value="{{ $companyAddress != null ? $companyAddress->landmark : '' }}"
                                            class="form-control hasMaxLength"
                                            placeholder="Eg. Some Famous Restaurant"
                                            autocomplete="nope"
                                            maxlength="100"
                                        />
                                    </div>
                                </div>

                                <div class="col-xl-4 col-lg-4 col-md-6">
                                    <div class="form-group">
                                        <label class="normal" for="city">City:</label>
                                        <input
                                            type="text"
                                            name="city"
                                            id="city"
                                            value="{{ $companyAddress != null ? $companyAddress->city : '' }}"
                                            class="form-control hasMaxLength"
                                            placeholder="Eg. Mumbai"
                                            required="required"
                                            autocomplete="nope"
                                            maxlength="100"
                                        />
                                    </div>
                                </div>

                                <div class="col-xl-4 col-lg-4 col-md-6">
                                    <div class="form-group">
                                        <label class="normal" for="pin_code">Pin / Zip Code:</label>
                                        <input
                                            type="text"
                                            name="pin_code"
                                            id="pin_code"
                                            value="{{ $companyAddress != null ? $companyAddress->pin_code : '' }}"
                                            class="form-control hasMaxLength"
                                            placeholder="Eg. 400034"
                                            required="required"
                                            autocomplete="nope"
                                            maxlength="100"
                                        />
                                    </div>
                                </div>

                                <div class="col-xl-4 col-lg-4 col-md-6">
                                    <div class="form-group">
                                        <label class="normal" for="state">State:</label>
                                        <input
                                            type="text"
                                            name="state"
                                            id="state"
                                            value="{{ $companyAddress != null ? $companyAddress->state : '' }}"
                                            class="form-control hasMaxLength"
                                            placeholder="Eg. Maharashtra"
                                            required="required"
                                            autocomplete="nope"
                                            maxlength="100"
                                        />
                                    </div>
                                </div>

                                <div class="col-xl-4 col-lg-4 col-md-6">
                                    <div class="form-group">
                                        <label class="normal" for="country">Country:</label>
                                        <input
                                            type="text"
                                            name="country"
                                            id="country"
                                            value="{{ $companyAddress != null ? $companyAddress->country : '' }}"
                                            class="form-control hasMaxLength"
                                            placeholder="Eg. India"
                                            required="required"
                                            autocomplete="nope"
                                            maxlength="100"
                                        />
                                    </div>
                                </div>
                            </div>

                            <div class="errorsInUpdatingCompanyAddress"></div>

                            <button class="btn submitButton btnCompanyAddress mt-3">Submit</button>
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
        $('.btnCompanyAddress').click(function (e) {
            e.preventDefault();

            var form = $("#formUpdateCompanyAddress"),
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
                        }, 3000);
                    }
                },
                error: function (err) {
                    self.prop('disabled', false);
                    self.html('Submit');

                    if ( err.status == 422) {
                        displayAlertNotification(err, 'errorsInUpdatingCompanyAddress');
                    } else {
                        alert('Something went wrong. Please try again later.');
                    }
                },
            });

            return false;
        });
    </script>
@endsection
