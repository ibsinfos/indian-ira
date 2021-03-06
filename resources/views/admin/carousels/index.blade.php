@extends('admin.partials._layout')

@section('title_and_meta_info')
    <title>List of All Carousels</title>
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
            <li class="breadcrumb-item active" aria-current="page">Carousels</li>
        </ol>
    </nav>

    <div class="row">
        <div class="mb-xl-5 col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="header p-0 m-0">
                        List of All Carousels (Total: {{ $carousels->count() }})

                        <div class="float-right">
                            <a
                                href="#"
                                class="btn btn-light btn-sm font-weight-bold shadow-none mt-md-0 mt-lg-0 mt-xl-0 mt-sm-4"
                                data-toggle="modal"
                                data-target="#addCarouselModal"
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
                        The carousels are a special way of saying that these products are of best quality. A section on a page which acts as a Merry-Go Round.<br /><br />
                        The light red background color (if any) indicates, that particular carousel has been temporarily deleted and is not accessible.<br /><br />
                        You can click on the column header if you want to sort that column either in Ascending order or in Descending order.<br />Default: Descending Order.
                    </p>

                    <div class="table-responsive">
                        @include('admin.carousels.table')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.carousels.addCarousel')
@include('admin.carousels.editCarousel')

<div class="mb-5"></div>
@endsection

@section('pageScripts')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        var table = $('.allCarouselsTable').dataTable({
            "aaSorting": [],
            "order": [[0, "asc"]]
        });

        $('.btnAddCarousel').click(function (e) {
            e.preventDefault();

            var form = $("#formAddCarousel"),
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
                        table.DataTable().destroy();

                        $('.allCarouselsTable').html(res.htmlResult);

                        $('.allCarouselsTable').dataTable({
                            "aaSorting": [],
                            "order": [[0, "asc"]]
                        });

                        $('#addCarouselModal').modal('hide');

                        form[0].reset();
                    }
                },
                error: function (err) {
                    self.prop('disabled', false);
                    self.html('Submit');

                    if ( err.status == 422) {
                        displayAlertNotification(err, 'errorsInAddingCarousel');
                    } else {
                        alert('Something went wrong. Please try again later.');
                    }
                },
            });

            return false;
        });

        var carouselId;

        $('#editCarouselModal').on('show.bs.modal', function (e) {
            var link            = $(e.relatedTarget),
                id = carouselId = link.data('id'),
                carousel        = link.data('carousel'),
                products        = link.data('products'),
                modal           = $(this);

            modal.find('.modal-title').html('Edit Carousel: ' + carousel.name);
            modal.find('.modal-body #name').val(carousel.name);
            modal.find('.modal-body #display').val(carousel.display);
            modal.find('.modal-body #short_description').val(carousel.short_description);

            var $selectize = modal.find('.modal-body #product_id').selectize();
            $selectize[0].selectize.setValue(products);
        });

        $('#editCarouselModal').on('hide.bs.modal', function (e) {
            var $selectize = $(this).find('.modal-body #product_id').selectize();

            $selectize[0].selectize.destroy();
        });

        $('.btnEditCarousel').click(function (e) {
            e.preventDefault();

            var form = $("#formEditCarousel"),
                self = $(this);

            self.prop('disabled', true);
            self.html('<i class="fas fa-spinner fa-spin"></i> Submitting...');

            $.ajax({
                url: "{{ route('admin.carousels') }}" +"/"+ carouselId +"/update",
                type: 'POST',
                data: form.serialize(),
                success: function (res) {
                    self.prop('disabled', false);
                    self.html('Submit');

                    displayGrowlNotification(res.status, res.title, res.message, res.delay);

                    table.DataTable().destroy();

                    $('.allCarouselsTable').html(res.htmlResult);

                    $('.allCarouselsTable').dataTable({
                        "aaSorting": [],
                        "order": [[0, "asc"]]
                    });

                    $('#editCarouselModal').modal('hide');
                },
                error: function (err) {
                    self.prop('disabled', false);
                    self.html('Submit');

                    if ( err.status == 422) {
                        displayAlertNotification(err, 'errorsInEditingCarousel');
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

                        $('.allCarouselsTable').html(res.htmlResult);

                        $('.allCarouselsTable').dataTable({
                            "aaSorting": [],
                            "order": [[0, "asc"]]
                        });
                    },
                    error: function (err) {
                        self.prop('disabled', false);
                        self.html('Submit');

                        if ( err.status == 422) {
                            displayAlertNotification(err, 'errorsInEditingCarousel');
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
