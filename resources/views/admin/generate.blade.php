@extends('admin.partials._layout')

@section('title_and_meta_info')
    <title>Generate Super Adminstrator</title>
@endsection

@section('pageStyles')
    <style>
        body {
            margin: 0 auto;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <figure class="text-center m-0">
            <img
                src="{{ url('/images/Indian-Ira-Logo.png') }}"
                alt="{{ config('app.name') }} - Logo"
                class="img img-responsive logoImage"
            />
        </figure>

        <div class="row">
            <div class="col-xl-8 offset-xl-2 col-lg-8 offset-lg-2">
                <div class="card">
                    <div class="card-header">
                        <h1 class="header p-0 m-0">
                            Generate Super Administrator
                        </h1>
                    </div>

                    <div class="card-body">
                        <p class="text-danger font-weight-bold mb-4">
                            We will send an E-Mail to the provided E-Mail address on successfully becoming the Super Administrator.
                        </p>

                        <form
                            action="{{ route('admin.generate.store') }}"
                            method="POST"
                            id="formGenerateAdministrator"
                            autocomplete="notWanted"
                        >
                            {!! csrf_field() !!}

                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label class="normal" for="first_name">First Name:</label>
                                        <input
                                            type="text"
                                            name="first_name"
                                            id="first_name"
                                            class="form-control hasMaxLength"
                                            placeholder="Eg. John"
                                            required="required"
                                            autofocus="autofocus"
                                            autocomplete="nope"
                                            maxlength="100"
                                        />
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label class="normal" for="last_name">Last Name:</label>
                                        <input
                                            type="text"
                                            name="last_name"
                                            id="last_name"
                                            class="form-control hasMaxLength"
                                            placeholder="Eg. John"
                                            required="required"
                                            autocomplete="nope"
                                            maxlength="100"
                                        />
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label class="normal" for="username">Username:</label>
                                        <input
                                            type="text"
                                            name="username"
                                            id="username"
                                            class="form-control hasMaxLength"
                                            placeholder="Eg. johnDoe"
                                            required="required"
                                            autocomplete="nope"
                                            data-toggle="tooltip"
                                            title="It should contain only alphabets, numbers, dashes (-) and underscores ( _ )"
                                            maxlength="100"
                                        />
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label class="normal" for="email">Email:</label>
                                        <input
                                            type="email"
                                            name="email"
                                            id="email"
                                            class="form-control"
                                            placeholder="Eg. john&#64;example.com"
                                            data-toggle="tooltip"
                                            title="A valid E-Mail address is required"
                                            required="required"
                                            autocomplete="nope"
                                        />
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label class="normal" for="password">Password:</label>
                                        <input
                                            type="password"
                                            name="password"
                                            id="password"
                                            class="form-control"
                                            placeholder="Eg. Secret"
                                            required="required"
                                        />
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label class="normal" for="confirm_password">Confirm Password:</label>
                                        <input
                                            type="password"
                                            name="confirm_password"
                                            id="confirm_password"
                                            class="form-control"
                                            placeholder="Eg. Secret"
                                            required="required"
                                            data-toggle="tooltip"
                                            title="It should match with Password field"
                                        />
                                    </div>
                                </div>
                            </div>

                            <div class="errorsInGeneratingAdministrator"></div>

                            <button class="btn submitButton btnGenerateAdministrator">Submit</button>
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
        $('.btnGenerateAdministrator').click(function (e) {
            e.preventDefault();

            var form = $("#formGenerateAdministrator"),
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

                    if (res.status == 'success') {
                        displayGrowlNotification(res.status, res.title, res.message, res.delay);

                        setTimeout(function () {
                            window.location = res.location;
                        }, 3500);
                    }
                },
                error: function (err) {
                    self.prop('disabled', false);
                    self.html('Submit');

                    if ( err.status == 422) {
                        displayAlertNotification(err, 'errorsInGeneratingAdministrator');
                    } else {
                        alert('Something went wrong. Please try again later.');
                    }
                },
            });

            return false;
        });
    </script>
@endsection
