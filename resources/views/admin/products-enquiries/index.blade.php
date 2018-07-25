@extends('admin.partials._layout')

@section('title_and_meta_info')
    <title>List of Products Enquiries</title>
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
                Product Enquiries
            </li>
        </ol>
    </nav>

    <div class="row">
        <div class="mb-xl-5 col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="header p-0 m-0">
                        List of Products Enquiries

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
                        @include('admin.products-enquiries.table')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.products-enquiries.viewEnquiryDetails')

<div class="mb-5"></div>
@endsection

@section('pageScripts')
    <script>
        var table = $('.allProductsEnquiriesTable').dataTable({
            "aaSorting": [],
            "order": [[0, "asc"]]
        });

        $('#viewEnquiryDetails').on('show.bs.modal', function (e) {
            var link            = $(e.relatedTarget),
                id = carouselId = link.data('id'),
                enquiry         = link.data('enquiry'),
                modal           = $(this);

            modal.find('.modal-title').html('View Product Enquiry: ' + enquiry.code);
            modal.find('.modal-body .message').html(enquiry.message_body);
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

                        $('.allProductsEnquiriesTable').html(res.htmlResult);

                        $('.allProductsEnquiriesTable').dataTable({
                            "aaSorting": [],
                            "order": [[0, "asc"]]
                        });
                    },
                    error: function (err) {
                        self.prop('disabled', false);
                        self.html('Submit');

                        alert('Something went wrong. Please try again later.');
                    },
                });
            }

            return false;
        });
    </script>
@endsection
