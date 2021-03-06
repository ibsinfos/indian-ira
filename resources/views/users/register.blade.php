@extends('admin.partials._layout')

@section('title_and_meta_info')
    <title>New User: Register</title>
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
                src="{{ url('/images/Indian-Ira-Logo-1.png') }}"
                alt="{{ config('app.name') }} - Logo"
                class="img img-responsive logoImage my-4"
            />
        </figure>

        <div class="row">
            <div class="col-xl-8 offset-xl-2 col-lg-8 offset-lg-2">
                <div class="card">
                    <div class="card-header">
                        <h1 class="header p-0 m-0">
                            New User: Register
                        </h1>
                    </div>

                    <div class="card-body">
                        <p class="text-danger mb-4">
                            We will send an E-Mail to the provided E-Mail address on successful submission for confirmation.
                        </p>

                        <form
                            action="{{ route('users.register.store') }}"
                            method="POST"
                            id="formRegisterUser"
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

                            <div class="form-group">
                                <label class="normal" for="contact_number">Contact Number (Optional):</label>
                                <input
                                    type="text"
                                    name="contact_number"
                                    id="contact_number"
                                    class="form-control"
                                    placeholder="Eg. 9876543210"
                                    data-toggle="tooltip"
                                    title="It should contain on numbers"
                                />
                            </div>

                            <div class="errorsInRegisteringUser"></div>

                            <p class="text-justify mb-3">
                                By clicking on Submit, you agree to our
                                <a
                                    href="javascript:void(0)"
                                    target="_blank"
                                    class="mainSiteLink"
                                >
                                    Terms of Service
                                </a> and
                                <a
                                    href="javascript:void(0)"
                                    target="_blank"
                                    class="mainSiteLink"
                                >
                                    Privacy Policy
                                </a>
                            </p>

                            <button class="btn submitButton btnRegisterUser">Submit</button>
                        </form>
                    </div>

                    <div class="card-footer">
                        <a href="{{ route('users.login') }}" class="mainSiteLink">
                            Click here to Login
                        </a> OR
                        <a href="{{ route('homePage') }}" class="mainSiteLink">
                            Click here to Go to Home Page
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-5"></div>
@endsection

@section('pageScripts')
    <script>
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
