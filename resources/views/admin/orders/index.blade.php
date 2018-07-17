@extends('admin.partials._layout')

@section('title_and_meta_info')
    <title>List of All Orders</title>
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
            <li class="breadcrumb-item active" aria-current="page">Orders</li>
        </ol>
    </nav>

    <div class="row">
        <div class="mb-xl-5 col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="header p-0 m-0">
                        List of All Orders (Total: {{ $allOrders->count() }})

                        <div class="float-right">
                            <a
                                href="{{ route('admin.dashboard') }}"
                                class="btn btn-outline-light text-black btn-sm font-weight-bold shadow-none mt-md-0 mt-lg-0 mt-xl-0 mt-sm-4"
                            >Go to Dashboard</a>
                        </div>
                    </h3>
                </div>

                <div class="card-body">
                    <p class="text-danger text-justify mb-5" style="font-size: 14px;">
                        The light red background color (if any) indicates, that particular order has been temporarily deleted and is not accessible.<br /><br />
                        You can click on the column header if you want to sort that column either in Ascending order or in Descending order.<br />Default: Descending Order.
                    </p>

                    <div class="table-responsive">
                        @include('admin.orders.table')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mb-5"></div>
@endsection

@section('pageScripts')
    <script>
        var table = $('.allOrdersTable').dataTable({
            "aaSorting": [],
            "order": [[0, "asc"]]
        });

        $('.btnEditOrder').click(function (e) {
            e.preventDefault();

            var form = $("#formEditOrder"),
                self = $(this);

            self.prop('disabled', true);
            self.html('<i class="fas fa-spinner fa-spin"></i> Submitting...');

            $.ajax({
                url: "{{ route('admin.orders') }}" +"/"+ couponId +"/update",
                type: 'POST',
                data: form.serialize(),
                success: function (res) {
                    self.prop('disabled', false);
                    self.html('Submit');

                    displayGrowlNotification(res.status, res.title, res.message, res.delay);

                    table.DataTable().destroy();

                    $('.allOrdersTable').html(res.htmlResult);

                    $('.allOrdersTable').dataTable({
                        "aaSorting": [],
                        "order": [[0, "asc"]]
                    });

                    $('#editOrderModal').modal('hide');
                },
                error: function (err) {
                    self.prop('disabled', false);
                    self.html('Submit');

                    if ( err.status == 422) {
                        displayAlertNotification(err, 'errorsInEditingOrder');
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

            if (confirm('Are you sure that you want to proceed? This cannot be undone.')) {
                $.ajax({
                    url: self.attr('href'),
                    type: 'GET',
                    success: function (res) {
                        $('[data-toggle="tooltip"]').tooltip('hide');

                        displayGrowlNotification(res.status, res.title, res.message, res.delay);

                        table.DataTable().destroy();

                        $('.allOrdersTable').html(res.htmlResult);

                        $('.allOrdersTable').dataTable({
                            "aaSorting": [],
                            "order": [[0, "asc"]]
                        });
                    },
                    error: function (err) {
                        self.prop('disabled', false);
                        self.html('Submit');

                        if ( err.status == 422) {
                            displayAlertNotification(err, 'errorsInEditingOrder');
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
