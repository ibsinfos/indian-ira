@extends('users.partials._layout')

@section('title_and_meta_info')
    <title>Edit General Details | {{ $user->getFullName() }}</title>
@endsection

@section('content')
    <div class="container-fluid">
        <nav class="mb-4" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('users.dashboard') }}" class="mainSiteLink">
                        Dashboard
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Settings - General Details</li>
            </ol>
        </nav>

        <div class="row">
            <div class="mb-xl-5 col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="header p-0 m-0">
                            Edit General Details

                            <div class="float-right">
                                <a
                                    href="{{ route('users.dashboard') }}"
                                    class="btn btn-outline-light btn-sm font-weight-bold shadow-none mt-md-0 mt-lg-0 mt-xl-0 mt-sm-4"
                                >Go to Dashboard</a>
                            </div>
                        </h3>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-6">
                                <div class="form-group">
                                    <label class="normal" for="username">Username:</label>
                                    <input
                                        type="text"
                                        name="username"
                                        id="username"
                                        value="{{ $user->username }}"
                                        class="form-control"
                                        placeholder="Eg. johnDoe"
                                        readonly="readonly"
                                        data-toggle="tooltip"
                                        title="Not allowed to edit"
                                    />
                                </div>
                            </div>

                            <div class="col-xl-4 col-lg-4 col-md-6">
                                <div class="form-group">
                                    <label class="normal" for="email">E-Mail Address:</label>
                                    <input
                                        type="text"
                                        id="email"
                                        value="{{ $user->email }}"
                                        class="form-control"
                                        placeholder="Eg. johnDoe&#64;example.com"
                                        readonly="readonly"
                                        data-toggle="tooltip"
                                        title="Not allowed to edit"
                                    />
                                </div>
                            </div>
                        </div>

                        <hr />

                        <form
                            action="{{ route('users.settings.general.update') }}"
                            method="POST"
                            id="formUpdateGeneralDetails"
                            autocomplete="notWanted"
                        >
                            @csrf

                            <div class="row">
                                <div class="col-xl-4 col-lg-4 col-md-6">
                                    <div class="form-group">
                                        <label class="normal" for="first_name">First Name:</label>
                                        <input
                                            type="text"
                                            name="first_name"
                                            id="first_name"
                                            value="{{ $user->first_name }}"
                                            class="form-control hasMaxLength"
                                            placeholder="Eg. John Doe"
                                            required="required"
                                            autocomplete="nope"
                                            maxlength="100"
                                        />
                                    </div>
                                </div>

                                <div class="col-xl-4 col-lg-4 col-md-6">
                                    <div class="form-group">
                                        <label class="normal" for="last_name">Last Name:</label>
                                        <input
                                            type="text"
                                            name="last_name"
                                            id="last_name"
                                            value="{{ $user->last_name }}"
                                            class="form-control hasMaxLength"
                                            required="required"
                                            placeholder="Eg. Doe"
                                            autocomplete="nope"
                                            maxlength="100"
                                        />
                                    </div>
                                </div>

                                <div class="col-xl-4 col-lg-4 col-md-6">
                                    <div class="form-group">
                                        <label class="normal" for="contact_number">Contact Number (Optional):</label>
                                        <input
                                            type="text"
                                            name="contact_number"
                                            id="contact_number"
                                            value="{{ $user->contact_number }}"
                                            class="form-control hasMaxLength"
                                            placeholder="Eg. 9876543210"
                                            required="required"
                                            autocomplete="nope"
                                            maxlength="100"
                                        />
                                    </div>
                                </div>
                            </div>

                            <div class="errorsInUpdatingGeneralDetails"></div>

                            <button class="btn submitButton btnGeneralDetails mt-3">Submit</button>
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
        $('.btnGeneralDetails').click(function (e) {
            e.preventDefault();

            var form = $("#formUpdateGeneralDetails"),
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
                        displayAlertNotification(err, 'errorsInUpdatingGeneralDetails');
                    } else {
                        alert('Something went wrong. Please try again later.');
                    }
                },
            });

            return false;
        });
    </script>
@endsection
