<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    @yield('title_and_meta_info')

    <meta name="robots" content="NOINDEX, NOFOLLOW">

    <link rel="icon" href="{{ url('/images/favicon.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ url('/images/favicon.ico') }}" type="image/x-icon">

    <link rel="stylesheet" href="{{ url('/css/app.css') }}" />

    <style>
        .topBorder {
            height: 5px;
            background: #cb5e00;
        }
    </style>

    @yield('pageStyles')
</head>
<body class="bg-light">
    <div class="position-fixed w-100 topBar" style="z-index: 1000">
        <div class="topBorder"></div>

        <section class="bg-white">
            <div class="container">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-8 d-none d-sm-block">
                        <ul class="list-inline p-0 my-1">
                            <li class="list-inline-item">
                                <i class="fas fa-phone align-middle"></i>
                                +91 9876543210
                            </li>

                            <li class="list-inline-item">
                                <i class="fas fa-envelope align-middle"></i>
                                support&#64;example.com
                            </li>
                        </ul>
                    </div>

                    <div class="col-xl-6 col-lg-6 col-md-4">
                        <ul class="list-inline p-0 my-1 float-md-right">
                            <li class="list-inline-item">
                                <i class="fas fa-sign-in-alt align-middle"></i>
                                Login
                            </li>

                            <li class="list-inline-item">
                                <i class="fas fa-user-plus align-middle"></i>
                                Register
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <section class="contactOnTopBar" style="background: #b9a693 !important; ">
            <div class="container">
                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3 mb-md-0">
                            <img
                                src="{{ url('/images/Indian-Ira-Logo-1.png') }}"
                                alt="{{ config('app.name') }} - Logo"
                                class="bg-dark img-fluid"
                            >
                        </div>
                    </div>

                    <div class="col-md-9">
                        <form action="#" class="form-inline float-right">
                            <div class="input-group mb-3 mt-0 mt-md-3">
                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Search Products"
                                >

                                <div class="input-group-append">
                                    <button class="btn btn-warning" type="button">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        @include('partials._navigation')
    </div>

    <div class="allContents">
        @yield('content')
    </div>

    <script src="{{ url('/js/app.js') }}"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>

    <script>
        var outerHeight = $(".topBar").outerHeight(true);
        $('.allContents').css({
            'padding-top': outerHeight + 'px'
        });

        $(window).resize(function (e) {
            var outerHeight = $(".topBar").outerHeight(true);

            $('.allContents').css({
                'padding-top': outerHeight + 'px'
            });
        });

        $('[data-toggle="tooltip"]').tooltip();

        $('.hasMaxLength').maxlength({
            alwaysShow: true,
            placement: 'bottom',
            message: 'You have used %charsTyped% of %charsTotal% characters.',
            warningClass: "mxlSuccess"
        });

        $('.singleSelectize').selectize({
            hideSelected: true,
            closeAfterSelect: true
        });

        $('.multipleSelect').selectize({
            hideSelected: true,
            closeAfterSelect: true
        });

        function displayGrowlNotification(status, title, message, delay)
        {
            $.iGrowl({
                type: status == 'success' ? 'success' : 'error',
                title: title,
                message: message,
                icon: status == 'success' ? 'feather-check' : 'feather-cross',
                delay: delay || 0,
                animShow: 'bounceInRight',
                animHide: 'bounceOutRight'
            });
        }

        function displayAlertNotification(err, errClass) {
            var json       = $.parseJSON(err.responseText),
                errorsHtml = '<div class="alert alert-danger"><ul>';

            errorsHtml += '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times</button>';

            if (err.constructor !== String) {
                $.each( json.errors, function( key, value ) {
                    errorsHtml += '<li>' + value + '</li>';
                });
            } else {
                errorsHtml += err;
            }

            errorsHtml += '</ul></div>';

            $('.' + errClass).html(errorsHtml);
        }
    </script>

    @yield('pageScripts')
</body>
</html>
