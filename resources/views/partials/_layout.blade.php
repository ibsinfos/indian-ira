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

        .footer {
            font-size: .85rem;
        }

        .footer .header {
            font-size: 1.5rem;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        @media screen and (max-width: 580px) {
            .imgLogo {
                width: 40% !important;
                display: block;
                margin: 0 auto !important;
            }
        }

        @media screen and (max-width: 400px) {
            .imgLogo {
                width: 60% !important;
                display: block;
                margin: 0 auto !important;
            }
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
                    <div class="col-xl-6 col-lg-6 col-md-8 d-none d-md-block">
                        <ul class="list-inline p-0 my-1">
                            <li class="list-inline-item">
                                <i class="fas fa-phone align-middle"></i>
                                +91 9876543210
                            </li>

                            <li class="list-inline-item">
                                <a href="mailto:support&#64;example.com" class="mainSiteLink">
                                    <i class="fas fa-envelope align-middle"></i>
                                    support&#64;example.com
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="col-xl-6 col-lg-6 col-md-4">
                        <ul class="list-inline p-0 my-1 float-md-right">
                            <li class="list-inline-item">
                                <a href="{{ route('users.login') }}" class="mainSiteLink">
                                    <i class="fas fa-sign-in-alt align-middle"></i>
                                    Login
                                </a>
                            </li>

                            <li class="list-inline-item d-none d-sm-inline">
                                <a href="{{ route('users.register') }}" class="mainSiteLink">
                                    <i class="fas fa-user-plus align-middle"></i>
                                    Register
                                </a>
                            </li>

                            <li class="list-inline-item">
                                <a href="{{ route('cart.show') }}" class="mainSiteLink">
                                    <i class="fas fa-shopping-cart"></i>
                                    Cart <span class="badge badge-success cartBadge">
                                        {{ \IndianIra\Utilities\Cart::totalProducts() }}
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <section class="contactOnTopBar" style="background: #b9a693 !important; ">
            <div class="container">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                        <div class="mb-3 mb-sm-0">
                            <img
                                src="{{ url('/images/Indian-Ira-Logo-1.png') }}"
                                alt="{{ config('app.name') }} - Logo"
                                class="bg-dark img-fluid imgLogo"
                            >
                        </div>
                    </div>

                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                        <div class="float-none float-sm-right mt-0 mt-sm-4 mb-3 mb-sm-0 w-100">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">
                                        <i class="fas fa-search"></i>
                                    </span>
                                </div>

                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Search Products"
                                >
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </section>

        @include('partials._navigation')
    </div>

    <div class="allContents">
        @yield('content')
    </div>

    @include('partials._footer')

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
