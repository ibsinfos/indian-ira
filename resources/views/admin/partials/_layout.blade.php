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
    <link rel="stylesheet" href="{{ url('/css/navbar-fixed-left.min.css') }}" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css">

    <style>
        body {
            margin-top: 20px;
        }

        body .container-fluid {
            width: 100%;
        }

        @media screen and (max-width: 767px) {
            body {
                padding-top: 60px;
            }
        }
    </style>

    @yield('pageStyles')
</head>
<body>
    @if (auth()->check() && auth()->id() == 1)
        @include('admin.partials._navigation')
    @endif

    @yield('content')

    <script src="{{ url('/js/app.js') }}"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $('[data-toggle="tooltip"]').tooltip();

        $('.hasMaxLength').maxlength({
            alwaysShow: true,
            placement: 'bottom',
            message: 'You have used %charsTyped% of %charsTotal% characters.',
            warningClass: "mxlSuccess"
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
