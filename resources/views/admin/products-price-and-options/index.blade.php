@extends('admin.partials._layout')

@section('title_and_meta_info')
    <title>{{ $product->name }} | List of All Prices and Options</title>
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
                <a href="{{ route('admin.products') }}" class="mainSiteLink">
                    Products
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }} - Prices and Options</li>
            <li class="breadcrumb-item">
                <a href="{{ url($product->canonicalPageUrl()) }}" class="mainSiteLink" target="_blank">
                    View this product
                </a>
            </li>
        </ol>
    </nav>

    <div class="row">
        <div class="mb-xl-5 col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="header p-0 m-0">
                        {{ $product->name }} - Prices and Options

                        <div class="float-right">
                            <a
                                href="#"
                                class="btn btn-light btn-sm font-weight-bold shadow-none mt-md-0 mt-lg-0 mt-xl-0 mt-sm-4"
                                data-toggle="modal"
                                data-target="#addPriceAndOptionModal"
                                title="Add New Product"
                            >Add</a>
                            <a
                                href="{{ route('admin.products') }}"
                                class="btn btn-light text-black btn-sm font-weight-bold shadow-none mt-md-0 mt-lg-0 mt-xl-0 mt-sm-4"
                                title="Go to All Products page"
                                data-toggle="tooltip"
                            >Go to Products</a>
                            <a
                                href="{{ route('admin.products.edit', $product->id) }}?general"
                                class="btn btn-light text-black btn-sm font-weight-bold shadow-none mt-md-0 mt-lg-0 mt-xl-0 mt-sm-4"
                                title="Edit the details of this product"
                                data-toggle="tooltip"
                            >Edit this product</a>
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
                        @include('admin.products-price-and-options.table')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.products-price-and-options.addPriceAndOption')
@include('admin.products-price-and-options.editPriceAndOption')

<div class="mb-5"></div>
@endsection

@section('pageScripts')
    <script>
        var priceOption = {{ $product->number_of_options }};
        if (priceOption == 0) {
            $('.option1Heading, .option2Heading').hide();
        } else if (priceOption == 1) {
            $('.option2Heading').hide();
        } else if (priceOption == 2) {
            $('.option1Heading, .option2Heading').slideDown();
        }

        var table = $('.allProductPricesAndOptionsTable').dataTable({
            "aaSorting": [],
            "order": [[0, "asc"]]
        });

        var optionId;

        $('#editPriceAndOptionModal').on('show.bs.modal', function (e) {
            var link            = $(e.relatedTarget),
                id = optionId = link.data('id'),
                option        = link.data('option'),
                cartimg        = link.data('cartimg'),
                modal           = $(this);

            modal.find('.modal-title').html('Edit Option: ' + option.option_code);
            modal.find('.modal-body #option_1_heading').val(option.option_1_heading);
            modal.find('.modal-body #option_1_value').val(option.option_1_value);
            modal.find('.modal-body #option_2_heading').val(option.option_2_heading);
            modal.find('.modal-body #option_2_value').val(option.option_2_value);
            modal.find('.modal-body #option_code').val(option.option_code);
            modal.find('.modal-body #display').val(option.display);
            modal.find('.modal-body #selling_price').val(option.selling_price);
            modal.find('.modal-body #discount_price').val(option.discount_price);
            modal.find('.modal-body #stock').val(option.stock);
            modal.find('.modal-body #sort_number').val(option.sort_number);
            modal.find('.modal-body #weight').val(option.weight);
            modal.find('.modal-body span.viewImageFile').html('<a href="'+cartimg+'" target="_blank" class="mainSiteLink">View Image</a>');
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

                        $('.allProductPricesAndOptionsTable').html(res.htmlResult);

                        $('.allProductPricesAndOptionsTable').dataTable({
                            "aaSorting": [],
                            "order": [[0, "asc"]]
                        });
                    },
                    error: function (err) {
                        self.prop('disabled', false);
                        self.html('Submit');

                        if ( err.status == 422) {
                            displayAlertNotification(err, 'errorsInEditingProduct');
                        } else {
                            alert('Something went wrong. Please try again later.');
                        }
                    },
                });
            }

            return false;
        });

        $("form[id='formAddPriceAndOption']").submit(function(e) {
            e.preventDefault();

            var inputData = new FormData($(this)[0]),
                button    = $('.btnAddPriceAndOption');

            button.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Submitting...');

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: inputData,
                async: true,
                success: function( res ) {
                    button.prop('disabled', false).html('Submit');

                    displayGrowlNotification(res.status, res.title, res.message, res.delay);

                    $('#addPriceAndOptionModal').modal('hide');

                    table.DataTable().destroy();

                    $('.allProductPricesAndOptionsTable').html(res.htmlResult);

                    $('.allProductPricesAndOptionsTable').dataTable({
                        "aaSorting": [],
                        "order": [[0, "asc"]]
                    });
                },
                error: function( data ) {
                    button.prop('disabled', false).html('Submit');

                    if ( data.status == 422) {
                        displayAlertNotification(data, 'errorsInAddingPriceAndOption');
                    } else {
                        alert('Something went wrong. Please try again later.');
                    }
                },
                cache: false,
                contentType: false,
                processData: false
            });

            return false;
        });

        $("form[id='formEditPriceAndOption']").submit(function(e) {
            e.preventDefault();

            var inputData = new FormData($(this)[0]),
                button    = $('.btnEditPriceAndOption');

            button.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Submitting...');

            $.ajax({
                url: "/admin/products/{{ $product->id }}/price-and-options/" + optionId + '/update',
                type: 'POST',
                data: inputData,
                async: true,
                success: function( res ) {
                    button.prop('disabled', false).html('Submit');

                    displayGrowlNotification(res.status, res.title, res.message, res.delay);

                    $('#editPriceAndOptionModal').modal('hide');

                    table.DataTable().destroy();

                    $('.allProductPricesAndOptionsTable').html(res.htmlResult);

                    $('.allProductPricesAndOptionsTable').dataTable({
                        "aaSorting": [],
                        "order": [[0, "asc"]]
                    });
                },
                error: function( data ) {
                    button.prop('disabled', false).html('Submit');

                    if ( data.status == 422) {
                        displayAlertNotification(data, 'errorsInEditingPriceAndOption');
                    } else {
                        alert('Something went wrong. Please try again later.');
                    }
                },
                cache: false,
                contentType: false,
                processData: false
            });

            return false;
        });
    </script>
@endsection
