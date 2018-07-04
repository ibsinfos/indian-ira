@extends('admin.partials._layout')

@section('title_and_meta_info')
    <title>Edit General Details | {{ $user->getFullName() }}</title>
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
            <li class="breadcrumb-item">
                <a href="{{ route('admin.users') }}" class="mainSiteLink">
                    All Users
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                Edit: {{ $user->getFullName() }}
            </li>
        </ol>
    </nav>

    @include('admin.users._editing_links')

    <div class="row">
        <div class="mb-xl-5 col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="header p-0 m-0">
                        @if (request()->exists('general-details'))
                            Edit General Details: {{ $user->getFullName() }}
                        @elseif (request()->exists('billing-address'))
                            Edit Billing Address: {{ $user->getFullName() }}
                        @elseif (request()->exists('change-password'))
                            Change Password: {{ $user->getFullName() }}
                        @endif

                        <div class="float-right">
                            <a
                                href="{{ route('admin.users') }}"
                                class="btn btn-light text-black btn-sm font-weight-bold shadow-none mt-md-0 mt-lg-0 mt-xl-0 mt-sm-4"
                            >All Users</a>
                            <a
                                href="{{ route('admin.dashboard') }}"
                                class="btn btn-outline-light text-black btn-sm font-weight-bold shadow-none mt-md-0 mt-lg-0 mt-xl-0 mt-sm-4"
                            >Go to Dashboard</a>
                        </div>
                    </h3>
                </div>

                <div class="card-body">
                    @if (request()->exists('general-details'))
                        @include('admin.users._general')
                    @elseif (request()->exists('billing-address'))
                        @include('admin.users._billing')
                    @elseif (request()->exists('change-password'))
                        @include('admin.users._password')
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mb-5"></div>
@endsection

@section('pageScripts')
    <script>
        var table = $('.allUsersTable').dataTable({
            "aaSorting": [],
            "order": [[0, "asc"]]
        });

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

        $('.btnBillingAddress').click(function (e) {
            e.preventDefault();

            var form = $("#formUpdateBillingAddress"),
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
                        displayAlertNotification(err, 'errorsInUpdatingBillingAddress');
                    } else {
                        alert('Something went wrong. Please try again later.');
                    }
                },
            });

            return false;
        });

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

        $('body').on('click', '.ajaxBtnOnTable', function (e) {
            e.preventDefault();

            var self = $(this);

            self.prop('disabled', true);
            self.html('<i class="fas fa-spinner fa-spin"></i> ' + self.data('inaction'))

            if (confirm('Are you sure that you want to proceed? This cannot be undone.')) {
                $.ajax({
                    url: self.attr('href'),
                    type: 'GET',
                    success: function (res) {
                        self.prop('disabled', false);
                        self.html(self.data('comp'));

                        $('[data-toggle="tooltip"]').tooltip('hide');

                        displayGrowlNotification(res.status, res.title, res.message, res.delay);

                        table.DataTable().destroy();

                        $('.allUsersTable').html(res.htmlResult);

                        $('.allUsersTable').dataTable({
                            "aaSorting": [],
                            "order": [[0, "asc"]]
                        });
                    },
                    error: function (err) {
                        self.prop('disabled', false);
                        self.html('Submit');

                        if ( err.status == 422) {
                            displayAlertNotification(err, 'errorsInEditingUser');
                        } else {
                            alert('Something went wrong. Please try again later.');
                        }
                    },
                });
            }

            return false;
        });
    </script>
@endsection
