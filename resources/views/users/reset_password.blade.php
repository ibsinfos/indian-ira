@extends('users.partials._layout')

@section('title_and_meta_info')
    <title>Reset Password</title>
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
            <div class="col-xl-6 offset-xl-3 col-lg-6 offset-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h1 class="header p-0 m-0">
                            Reset Password
                        </h1>
                    </div>

                    <div class="card-body">
                        <form
                            action="{{ route('users.resetPassword.update', $token) }}"
                            method="POST"
                            id="formResetPassword"
                        >
                            {!! csrf_field() !!}

                            <div class="form-group">
                                <label class="normal" for="new_password">New Password:</label>
                                <input
                                    type="password"
                                    name="new_password"
                                    id="new_password"
                                    class="form-control"
                                    placeholder="Eg. Password"
                                    required="required"
                                />
                            </div>

                            <div class="form-group">
                                <label class="normal" for="repeat_new_password">Repeat New Password:</label>
                                <input
                                    type="password"
                                    name="repeat_new_password"
                                    id="repeat_new_password"
                                    class="form-control"
                                    placeholder="Eg. Password"
                                    required="required"
                                    data-toggle="tooltip"
                                    title="It should match New Password"
                                />
                            </div>

                            <div class="errorsInResettingPassword"></div>

                            <button class="btn submitButton btnResetPassword">Submit</button>
                        </form>
                    </div>

                    <div class="card-footer">
                        <a href="{{ route('users.login') }}" class="mainSiteLink">
                            Click here to Login
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
        $('.btnResetPassword').click(function (e) {
            e.preventDefault();

            var form = $("#formResetPassword"),
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
                        $('#email').focus();
                    }
                },
                error: function (err) {
                    self.prop('disabled', false);
                    self.html('Submit');

                    if ( err.status == 422) {
                        displayAlertNotification(err, 'errorsInResettingPassword');
                    } else {
                        alert('Something went wrong. Please try again later.');
                    }
                },
            });

            return false;
        });
    </script>
@endsection
