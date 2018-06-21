@extends('admin.partials._layout')

@section('title_and_meta_info')
    <title>List of All Categories</title>
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
            <li class="breadcrumb-item active" aria-current="page">Categories</li>
        </ol>
    </nav>

    <div class="row">
        <div class="mb-xl-5 col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="header p-0 m-0">
                        List of All Categories (Total: {{ $categories->count() }})

                        <div class="float-right">
                            <a
                                href="#"
                                class="btn btn-light btn-sm font-weight-bold shadow-none mt-md-0 mt-lg-0 mt-xl-0 mt-sm-4"
                                data-toggle="modal"
                                data-target="#addCategoryModal"
                            >Add</a>
                            <a
                                href="#"
                                class="btn btn-light btn-sm font-weight-bold shadow-none mt-md-0 mt-lg-0 mt-xl-0 mt-sm-4"
                                data-toggle="modal"
                                data-target="#importCategoryModal"
                            >Upload</a>
                            <a
                                href="{{ route('admin.categories.export') }}"
                                class="btn btn-light btn-sm font-weight-bold shadow-none mt-md-0 mt-lg-0 mt-xl-0 mt-sm-4"
                            >Download</a>
                            <a
                                href="{{ route('admin.dashboard') }}"
                                class="btn btn-outline-light text-black btn-sm font-weight-bold shadow-none mt-md-0 mt-lg-0 mt-xl-0 mt-sm-4"
                            >Go to Dashboard</a>
                        </div>
                    </h3>
                </div>

                <div class="card-body">
                    <p class="text-danger text-justify mb-5" style="font-size: 14px;">
                        Only 3 levels of category can be added. It should be like <span class="font-weight-bold">Category > Sub Category > Sub Sub Category</span>.<br /><br />
                        The light red background color (if any) indicates, that particular category has been temporarily deleted and is not accessible.<br /><br />
                        You can click on the column header if you want to sort that column either in Ascending order or in Descending order.<br />Default: Descending Order.
                    </p>

                    <div class="table-responsive">
                        @include('admin.categories.table')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.categories.addCategory')
@include('admin.categories.editCategory')
@include('admin.categories.importCategory')

<div class="mb-5"></div>
@endsection

@section('pageScripts')
    <script>
        var table = $('.allCategoriesTable').dataTable({
            "aaSorting": [],
            "order": [[0, "asc"]]
        });

        $('.btnAddCategory').click(function (e) {
            e.preventDefault();

            var form = $("#formAddCategory"),
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

                        $('.allCategoriesTable').html(res.htmlResult);

                        $('.allCategoriesTable').dataTable({
                            "aaSorting": [],
                            "order": [[0, "asc"]]
                        });

                        $('#addCategoryModal').modal('hide');

                        form[0].reset();
                    }
                },
                error: function (err) {
                    self.prop('disabled', false);
                    self.html('Submit');

                    if ( err.status == 422) {
                        displayAlertNotification(err, 'errorsInAddingCategory');
                    } else {
                        alert('Something went wrong. Please try again later.');
                    }
                },
            });

            return false;
        });

        var categoryId;

        $('#editCategoryModal').on('show.bs.modal', function (e) {
            var link            = $(e.relatedTarget),
                id = categoryId = link.data('id'),
                category        = link.data('category'),
                modal           = $(this);

            modal.find('.modal-title').html('Edit Category: ' + category.name);
            modal.find('.modal-body #name').val(category.name);
            modal.find('.modal-body #parent_id').val(category.parent_id);
            modal.find('.modal-body #display').val(category.display);
            modal.find('.modal-body #short_description').val(category.short_description);
            modal.find('.modal-body #meta_title').val(category.meta_title);
            modal.find('.modal-body #meta_description').val(category.meta_description);
            modal.find('.modal-body #meta_keywords').val(category.meta_keywords);
        });

        $('.btnEditCategory').click(function (e) {
            e.preventDefault();

            var form = $("#formEditCategory"),
                self = $(this);

            self.prop('disabled', true);
            self.html('<i class="fas fa-spinner fa-spin"></i> Submitting...');

            $.ajax({
                url: "{{ route('admin.categories') }}" +"/"+ categoryId +"/update",
                type: 'POST',
                data: form.serialize(),
                success: function (res) {
                    self.prop('disabled', false);
                    self.html('Submit');

                    displayGrowlNotification(res.status, res.title, res.message, res.delay);

                    table.DataTable().destroy();

                    $('.allCategoriesTable').html(res.htmlResult);

                    $('.allCategoriesTable').dataTable({
                        "aaSorting": [],
                        "order": [[0, "asc"]]
                    });

                    $('#editCategoryModal').modal('hide');
                },
                error: function (err) {
                    self.prop('disabled', false);
                    self.html('Submit');

                    if ( err.status == 422) {
                        displayAlertNotification(err, 'errorsInEditingCategory');
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

                        $('.allCategoriesTable').html(res.htmlResult);

                        $('.allCategoriesTable').dataTable({
                            "aaSorting": [],
                            "order": [[0, "asc"]]
                        });
                    },
                    error: function (err) {
                        self.prop('disabled', false);
                        self.html('Submit');

                        if ( err.status == 422) {
                            displayAlertNotification(err, 'errorsInEditingCategory');
                        } else {
                            alert('Something went wrong. Please try again later.');
                        }
                    },
                });
            }

            return false;
        });

        $("form[id='formImportCategory']").submit(function(e) {
            e.preventDefault();

            var inputData = new FormData($(this)[0]),
                button    = $('.btnImportCategories');

            button.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Submitting...');

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: inputData,
                async: true,
                success: function( res ) {
                    button.prop('disabled', false).html('Submit');

                    displayGrowlNotification(res.status, res.title, res.message, res.delay);

                    $('#importCategoriesModal').modal('hide');

                    setTimeout(function () {
                        window.location = res.location;
                    }, res.delay + 1000);
                },
                error: function( data ) {
                    button.prop('disabled', false).html('Submit');

                    if ( data.status == 422) {
                        displayAlertNotification(data, 'errorsInImportingCategories');
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
