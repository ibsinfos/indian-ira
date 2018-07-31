@extends('admin.partials._layout')

@section('title_and_meta_info')
    <title>Login Super Adminstrator</title>
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
            <div class="col-xl-6 offset-xl-3 col-lg-6 offset-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h1 class="header p-0 m-0">
                            Login Super Administrator
                        </h1>
                    </div>

                    <div class="card-body">
                        <form
                            action="{{ route('admin.postLogin') }}"
                            method="POST"
                            id="formLoginAdministrator"
                            autocomplete="notWanted"
                        >
                            {!! csrf_field() !!}

                            <div class="form-group">
                                <label class="normal" for="usernameOrEmail">Username or E-Mail:</label>
                                <input
                                    type="text"
                                    name="usernameOrEmail"
                                    id="usernameOrEmail"
                                    class="form-control hasMaxLength"
                                    placeholder="Eg. johnDoe / johndoe&#64;example.com"
                                    required="required"
                                    autocomplete="nope"
                                    autofocus="autofocus"
                                    data-toggle="tooltip"
                                    title="It should contain only alphabets, numbers, dashes (-) and underscores ( _ ) or a valid E-Mail address"
                                    data-placement="right"
                                    maxlength="100"
                                />
                            </div>

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

                            <div class="errorsInLoggingAdministrator"></div>

                            <button class="btn submitButton btnLoginAdministrator">Submit</button>
                        </form>
                    </div>

                    <div class="card-footer">
                        <a href="{{ route('homePage') }}" class="mainSiteLink">
                            Click here to go to Home Page
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
        $('.btnLoginAdministrator').click(function (e) {
            e.preventDefault();

            var form = $("#formLoginAdministrator"),
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
                        displayAlertNotification(err, 'errorsInLoggingAdministrator');
                    } else {
                        alert('Something went wrong. Please try again later.');
                    }
                },
            });

            return false;
        });
    </script>
@endsection
