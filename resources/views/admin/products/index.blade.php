@extends('admin.partials._layout')

@section('title_and_meta_info')
    <title>List of All Products</title>
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
            <li class="breadcrumb-item active" aria-current="page">Products</li>
        </ol>
    </nav>

    <div class="row">
        <div class="mb-xl-5 col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="header p-0 m-0">
                        List of All Products (Total: {{ $products->count() }})

                        <div class="float-right">
                            <a
                                href="#"
                                class="btn btn-light btn-sm font-weight-bold shadow-none mt-md-0 mt-lg-0 mt-xl-0 mt-sm-4"
                                data-toggle="modal"
                                data-target="#addProductModal"
                            >Add</a>
                            <a
                                href="{{ route('admin.dashboard') }}"
                                class="btn btn-outline-light text-black btn-sm font-weight-bold shadow-none mt-md-0 mt-lg-0 mt-xl-0 mt-sm-4"
                            >Go to Dashboard</a>
                        </div>
                    </h3>
                </div>

                <div class="card-body">
                    <p class="text-danger text-justify mb-5" style="font-size: 14px;">
                        The light red background color (if any) indicates, that particular product has been temporarily deleted and is not accessible.<br /><br />
                        You can click on the column header if you want to sort that column either in Ascending order or in Descending order.<br />Default: Descending Order.<br /><br />
                        When permanently deleting a product, the respective images will also get deleted and the categories associated with the product shall get dissociated.
                    </p>

                    <div class="table-responsive">
                        @include('admin.products.table')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.products.addProduct')
{{-- @include('admin.products.editProduct')
@include('admin.products.importProduct') --}}

<div class="mb-5"></div>
@endsection

@section('pageScripts')
    <script>
        var table = $('.allProductsTable').dataTable({
            "aaSorting": [],
            "order": [[0, "asc"]]
        });

        $('.btnAddProduct').click(function (e) {
            e.preventDefault();

            var form = $("#formAddProduct"),
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
                        }, res.delay + 1000);
                    }
                },
                error: function (err) {
                    self.prop('disabled', false);
                    self.html('Submit');

                    if ( err.status == 422) {
                        displayAlertNotification(err, 'errorsInAddingProduct');
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

                        $('.allProductsTable').html(res.htmlResult);

                        $('.allProductsTable').dataTable({
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

        $("form[id='formImportProduct']").submit(function(e) {
            e.preventDefault();

            var inputData = new FormData($(this)[0]),
                button    = $('.btnImportProducts');

            button.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Submitting...');

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: inputData,
                async: true,
                success: function( res ) {
                    button.prop('disabled', false).html('Submit');

                    displayGrowlNotification(res.status, res.title, res.message, res.delay);

                    $('#importProductsModal').modal('hide');

                    setTimeout(function () {
                        window.location = res.location;
                    }, res.delay + 1000);
                },
                error: function( data ) {
                    button.prop('disabled', false).html('Submit');

                    if ( data.status == 422) {
                        displayAlertNotification(data, 'errorsInImportingProducts');
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
