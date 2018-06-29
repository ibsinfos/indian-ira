@extends('users.partials._layout')

@section('title_and_meta_info')
    <title>Change Password | {{ $user->getFullName() }}</title>
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
                <li class="breadcrumb-item active" aria-current="page">Settings - Change Password</li>
            </ol>
        </nav>

        <div class="row">
            <div class="mb-xl-5 col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="header p-0 m-0">
                            Change Password

                            <div class="float-right">
                                <a
                                    href="{{ route('users.dashboard') }}"
                                    class="btn btn-outline-light btn-sm font-weight-bold shadow-none mt-md-0 mt-lg-0 mt-xl-0 mt-sm-4"
                                >Go to Dashboard</a>
                            </div>
                        </h3>
                    </div>

                    <div class="card-body">
                        <form
                            action="{{ route('users.settings.password.update') }}"
                            method="POST"
                            id="formUpdateChangePassword"
                            autocomplete="notWanted"
                        >
                            @csrf

                            <div class="row">
                                <div class="col-xl-4 col-lg-4 col-md-6">
                                    <div class="form-group">
                                        <label class="normal" for="current_password">Current Password:</label>
                                        <input
                                            type="password"
                                            name="current_password"
                                            id="current_password"
                                            class="form-control"
                                            placeholder="Eg. Secret"
                                            required="required"
                                        />
                                    </div>
                                </div>

                                <div class="col-xl-4 col-lg-4 col-md-6">
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
                                </div>

                                <div class="col-xl-4 col-lg-4 col-md-6">
                                    <div class="form-group">
                                        <label class="normal" for="repeat_new_password">Repeat New Password:</label>
                                        <input
                                            type="password"
                                            name="repeat_new_password"
                                            id="repeat_new_password"
                                            class="form-control"
                                            placeholder="Eg. Password"
                                            required="required"
                                        />
                                    </div>
                                </div>
                            </div>

                            <div class="errorsInUpdatingChangePassword"></div>

                            <button class="btn submitButton btnChangePassword mt-3">Submit</button>
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
        $('.btnChangePassword').click(function (e) {
            e.preventDefault();

            var form = $("#formUpdateChangePassword"),
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
                        displayAlertNotification(err, 'errorsInUpdatingChangePassword');
                    } else {
                        alert('Something went wrong. Please try again later.');
                    }
                },
            });

            return false;
        });
    </script>
@endsection
