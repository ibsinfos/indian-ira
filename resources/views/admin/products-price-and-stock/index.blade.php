@extends('admin.partials._layout')

@section('title_and_meta_info')
    <title>List of All Products With Price And Stock</title>
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
            <li class="breadcrumb-item active" aria-current="page">
                List of All Products With Price And Stock
            </li>
        </ol>
    </nav>

    <div class="row">
        <div class="mb-xl-5 col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="header p-0 m-0">
                        List of All Products With Price And Stock

                        <div class="float-right">
                            <a
                                href="{{ route('admin.products') }}"
                                class="btn btn-light text-black btn-sm font-weight-bold shadow-none mt-md-0 mt-lg-0 mt-xl-0 mt-sm-4"
                                title="Go to All Products page"
                                data-toggle="tooltip"
                            >Go to Products</a>
                            <a
                                href="{{ route('admin.dashboard') }}"
                                class="btn btn-outline-light text-black btn-sm font-weight-bold shadow-none mt-md-0 mt-lg-0 mt-xl-0 mt-sm-4"
                            >Go to Dashboard</a>
                        </div>
                    </h3>
                </div>

                <div class="card-body">
                    <p class="text-danger text-justify mb-5" style="font-size: 14px;">
                        You can click on the column header if you want to sort that column either in Ascending order or in Descending order.<br />Default: Descending Order.<br /><br />
                    </p>

                    <div class="table-responsive">
                        @include('admin.products-price-and-stock.table')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.products-price-and-stock.editPriceAndStock')

<div class="mb-5"></div>
@endsection

@section('pageScripts')
    <script>
        var table = $('.allProductPricesAndStockTable').dataTable({
            "aaSorting": [],
            "order": [[0, "asc"]]
        });

        var actionLink;

        $('#editPriceAndStockModal').on('show.bs.modal', function (e) {
            var link       = $(e.relatedTarget),
                option     = link.data('option'),
                postAction = '/admin/products-price-and-stock/' + option.product.code + '/' + option.option_code,
                modal      = $(this);

            actionLink = postAction;

            modal.find('.modal-title').html('Edit Price and Stock of: ' + option.product.name);
            modal.find('.modal-body #selling_price').val(option.selling_price);
            modal.find('.modal-body #discount_price').val(option.discount_price);
            modal.find('.modal-body #stock').val(option.stock);
            modal.find('.modal-body #gst_percent').val(option.product.gst_percent);
        });

        $('.btnEditPriceAndStock').click(function (e) {
            e.preventDefault();

            var form = $("#formEditPriceAndStock"),
                self = $(this);

            self.prop('disabled', true);
            self.html('<i class="fas fa-spinner fa-spin"></i> Submitting...');

            $.ajax({
                url: actionLink,
                type: 'POST',
                data: form.serialize(),
                success: function (res) {
                    self.prop('disabled', false);
                    self.html('Submit');

                    displayGrowlNotification(res.status, res.title, res.message, res.delay);

                    table.DataTable().destroy();

                    $('.allProductPricesAndStockTable').html(res.htmlResult);

                    $('.allProductPricesAndStockTable').dataTable({
                        "aaSorting": [],
                        "order": [[0, "asc"]]
                    });

                    $('#editPriceAndStockModal').modal('hide');
                },
                error: function (err) {
                    self.prop('disabled', false);
                    self.html('Submit');

                    if ( err.status == 422) {
                        displayAlertNotification(err, 'errorsInEditingPriceAndStock');
                    } else {
                        alert('Something went wrong. Please try again later.');
                    }
                },
            });

            return false;
        });
    </script>
@endsection
