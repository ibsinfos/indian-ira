@extends('admin.partials._layout')

@section('title_and_meta_info')
    <title>Shipping Rates</title>
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
            <li class="breadcrumb-item active" aria-current="page">Shipping Rates</li>
        </ol>
    </nav>

    <div class="row">
        <div class="mb-xl-5 col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="header p-0 m-0">
                        Shipping Rates

                        <div class="float-right">
                            <a
                                href="#"
                                class="btn btn-light btn-sm font-weight-bold shadow-none mt-md-0 mt-lg-0 mt-xl-0 mt-sm-4"
                                data-toggle="modal"
                                data-target="#addShippingRateModal"
                            >Add Shipping Rate</a>
                            <a
                                href="{{ route('admin.dashboard') }}"
                                class="btn btn-outline-light text-black btn-sm font-weight-bold shadow-none mt-md-0 mt-lg-0 mt-xl-0 mt-sm-4"
                            >Go to Dashboard</a>
                        </div>
                    </h3>
                </div>

                <div class="card-body">
                    <p class="text-danger text-justify mb-5">
                        The shipping amount that will get applied based on the cart weight as and when the buyer adds the product(s) into the cart.<br /><br />
                        The light red background color indicates, that particular shipping details has been temporarily deleted and is not accessible.<br /><br />
                        You can click on the column header if you want to sort that column either in Ascending order or in Descending order.<br />Default: Descending Order.
                    </p>

                    <div class="table-responsive">
                        @include('admin.shipping-rates.table')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.shipping-rates.addShippingRate')
@include('admin.shipping-rates.editShippingRate')

<div class="mb-5"></div>
@endsection

@section('pageScripts')
    <script>
        var table = $('.allShippingRatesTable').dataTable({
            "aaSorting": [],
            "order": [[0, "asc"]]
        });

        $('.btnAddShippingRate').click(function (e) {
            e.preventDefault();

            var form = $("#formAddShippingRate"),
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

                    table.DataTable().destroy();

                    $('.allShippingRatesTable').html(res.htmlResult);

                    $('.allShippingRatesTable').dataTable({
                        "aaSorting": [],
                        "order": [[0, "asc"]]
                    });

                    $('#addShippingRateModal').modal('hide');

                    // if (res.status == 'success' && (res.location != '' || res.location != null)) {
                    //     setTimeout(function () {
                    //         window.location = res.location;
                    //     }, 4000);
                    // }
                },
                error: function (err) {
                    self.prop('disabled', false);
                    self.html('Submit');

                    if ( err.status == 422) {
                        displayAlertNotification(err, 'errorsInAddingShippingRate');
                    } else {
                        alert('Something went wrong. Please try again later.');
                    }
                },
            });

            return false;
        });

        var shippingRateId;

        $('#editShippingRateModal').on('show.bs.modal', function (e) {
            var link                = $(e.relatedTarget),
                id = shippingRateId = link.data('id'),
                shipping            = link.data('shipping'),
                modal               = $(this);

            modal.find('.modal-title').html('Edit Shipping Rate of Id: ' + id);
            modal.find('.modal-body #shipping_company_name').val(shipping.shipping_company_name);
            modal.find('.modal-body #shipping_company_tracking_url').val(shipping.shipping_company_tracking_url);
            modal.find('.modal-body #location_type').val(shipping.location_type);
            modal.find('.modal-body #location_name').val(shipping.location_name);
            modal.find('.modal-body #weight_from').val(shipping.weight_from);
            modal.find('.modal-body #weight_to').val(shipping.weight_to);
            modal.find('.modal-body #amount').val(shipping.amount);
        });

        $('.btnEditShippingRate').click(function (e) {
            e.preventDefault();

            var form = $("#formEditShippingRate"),
                self = $(this);

            self.prop('disabled', true);
            self.html('<i class="fas fa-spinner fa-spin"></i> Submitting...');

            $.ajax({
                url: "{{ route('admin.shippingRates') }}" +"/"+ shippingRateId +"/update",
                type: 'POST',
                data: form.serialize(),
                success: function (res) {
                    self.prop('disabled', false);
                    self.html('Submit');

                    displayGrowlNotification(res.status, res.title, res.message, res.delay);

                    table.DataTable().destroy();

                    $('.allShippingRatesTable').html(res.htmlResult);

                    $('.allShippingRatesTable').dataTable({
                        "aaSorting": [],
                        "order": [[0, "asc"]]
                    });

                    $('#editShippingRateModal').modal('hide');

                    // if (res.status == 'success' && (res.location != '' || res.location != null)) {
                    //     setTimeout(function () {
                    //         window.location = res.location;
                    //     }, 4000);
                    // }
                },
                error: function (err) {
                    self.prop('disabled', false);
                    self.html('Submit');

                    if ( err.status == 422) {
                        displayAlertNotification(err, 'errorsInEditingShippingRate');
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

                        $('.allShippingRatesTable').html(res.htmlResult);

                        $('.allShippingRatesTable').dataTable({
                            "aaSorting": [],
                            "order": [[0, "asc"]]
                        });
                    },
                    error: function (err) {
                        self.prop('disabled', false);
                        self.html('Submit');

                        if ( err.status == 422) {
                            displayAlertNotification(err, 'errorsInEditingShippingRate');
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
