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
        .dropdown-menu .show > .dropdown-toggle::after{
            transform: rotate(-90deg);
        }

        .dropdown-menu .nav-link.dropdown-toggle {
            font-weight: normal !important;
            padding-left: 25px;
        }

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
                            @if (auth()->guest())
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
                            @else
                                <li class="list-inline-item">
                                    <a href="{{ route('users.dashboard') }}" class="mainSiteLink">
                                        <i class="fab fa-dashcube align-middle"></i>
                                        My Account
                                    </a>
                                </li>
                            @endif

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
                            <select name="search" id="search" class="searchProducts"></select>
                            {{-- <input
                                type="text"
                                class="singleSelectize w-50 float-none float-sm-right"
                                placeholder="Search Products"
                            > --}}
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

    @include('partials._product_enquiry_modal')

    @include('partials._footer')

    <script src="{{ url('/js/app.js') }}"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>

    <script>
        $( '.dropdown-menu a.dropdown-toggle' ).on( 'click', function ( e ) {
                var $el = $( this );
                var $parent = $( this ).offsetParent( ".dropdown-menu" );
                if ( !$( this ).next().hasClass( 'show' ) ) {
                    $( this ).parents( '.dropdown-menu' ).first().find( '.show' ).removeClass( "show" );
                }
                var $subMenu = $( this ).next( ".dropdown-menu" );
                $subMenu.toggleClass( 'show' );

                $( this ).parent( "li" ).toggleClass( 'show' );

                $( this ).parents( 'li.nav-item.dropdown.show' ).on( 'hidden.bs.dropdown', function ( e ) {
                    $( '.dropdown-menu .show' ).removeClass( "show" );
                } );

                 if ( !$parent.parent().hasClass( 'navbar-nav' ) ) {
                    $el.next().css( { "top": $el[0].offsetTop, "left": $parent.outerWidth() - 4 } );
                }

                return false;
            } );

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

        $('.searchProducts').selectize({
            valueField: 'url',
            labelField: 'name',
            searchField: ['name'],
            closeAfterSelect: true,
            placeholder: 'Search All Products',
            load: function(query, callback) {
                if (! query.length || query.length <= 2) {
                    this.clearCache();
                    this.clearOptions();
                    this.close();

                    return callback();
                } else if (query.length < 2) {
                    this.clearCache();
                    this.clearOptions();
                    this.close();

                    return callback();
                }

                $.ajax({
                    url: '{{ route('searchProducts') }}',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        q: query
                    },
                    success: function(res) {
                        if (res.status == 'failed') {
                            alert('Something went wrong. Please try again later');
                        }

                        callback(res.data);
                    },
                    error: function(err) {
                        callback();
                    },
                });
            },
            //sortField: [{field: '$score'}],
            onChange: function(query) {
                window.location = this.items[0];
            }
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

        var prd = opt = null;

        $('#enquireProductModal').on('show.bs.modal', function (e) {
            var link          = $(e.relatedTarget),
                product = prd = link.data('product'),
                option  = opt = link.data('option'),
                modal         = $(this);

            modal.find('.modal-title').html('Product Enquiry for: ' + product.name);
            modal.find('.modal-body form').attr('action', '/products-enquiry/' + product.code + '/' + option.option_code);
        });

        $('.btnEnquireProduct').on('click', function (e) {
            e.preventDefault();

            var form = $("#formEnquireProduct"),
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

                    $('#enquireProductModal').modal('hide');
                },
                error: function (err) {
                    self.prop('disabled', false);
                    self.html('Submit');

                    if ( err.status == 422) {
                        displayAlertNotification(err, 'errorsInEnquiringProduct');
                    } else {
                        alert('Something went wrong. Please try again later.');
                    }
                },
            });

            return false;
        });
    </script>

    @yield('pageScripts')
</body>
</html>
