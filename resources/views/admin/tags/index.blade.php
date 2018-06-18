@extends('admin.partials._layout')

@section('title_and_meta_info')
    <title>Tags</title>
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
            <li class="breadcrumb-item active" aria-current="page">Tags</li>
        </ol>
    </nav>

    <div class="row">
        <div class="mb-xl-5 col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="header p-0 m-0">
                        List of All Tags

                        <div class="float-right">
                            <a
                                href="#"
                                class="btn btn-light btn-sm font-weight-bold shadow-none mt-md-0 mt-lg-0 mt-xl-0 mt-sm-4"
                                data-toggle="modal"
                                data-target="#addTagModal"
                            >Add Tag</a>
                            <a
                                href="{{ route('admin.dashboard') }}"
                                class="btn btn-outline-light text-black btn-sm font-weight-bold shadow-none mt-md-0 mt-lg-0 mt-xl-0 mt-sm-4"
                            >Go to Dashboard</a>
                        </div>
                    </h3>
                </div>

                <div class="card-body">
                    <p class="text-danger text-justify mb-5">
                        {{-- The shipping amount that will get applied based on the cart weight as and when the buyer adds the product(s) into the cart.<br /><br /> --}}
                        The light red background color indicates, that particular tag details has been temporarily deleted and is not accessible.<br /><br />
                        You can click on the column header if you want to sort that column either in Ascending order or in Descending order.<br />Default: Descending Order.
                    </p>

                    <div class="table-responsive">
                        @include('admin.tags.table')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.tags.addTag')
@include('admin.tags.editTag')

<div class="mb-5"></div>
@endsection

@section('pageScripts')
    <script>
        var table = $('.allTagsTable').dataTable({
            "aaSorting": [],
            "order": [[0, "asc"]]
        });

        $('.btnAddTag').click(function (e) {
            e.preventDefault();

            var form = $("#formAddTag"),
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

                    $('.allTagsTable').html(res.htmlResult);

                    $('.allTagsTable').dataTable({
                        "aaSorting": [],
                        "order": [[0, "asc"]]
                    });

                    $('#addTagModal').modal('hide');
                },
                error: function (err) {
                    self.prop('disabled', false);
                    self.html('Submit');

                    if ( err.status == 422) {
                        displayAlertNotification(err, 'errorsInAddingTag');
                    } else {
                        alert('Something went wrong. Please try again later.');
                    }
                },
            });

            return false;
        });

        var tagId;

        $('#editTagModal').on('show.bs.modal', function (e) {
            var link       = $(e.relatedTarget),
                id = tagId = link.data('id'),
                tag        = link.data('tag'),
                modal      = $(this);

            modal.find('.modal-title').html('Edit Tag: ' + tag.name);
            modal.find('.modal-body #name').val(tag.name);
            modal.find('.modal-body #short_description').val(tag.short_description);
            modal.find('.modal-body #meta_title').val(tag.meta_title);
            modal.find('.modal-body #meta_description').val(tag.meta_description);
            modal.find('.modal-body #meta_keywords').val(tag.meta_keywords);
        });

        $('.btnEditTag').click(function (e) {
            e.preventDefault();

            var form = $("#formEditTag"),
                self = $(this);

            self.prop('disabled', true);
            self.html('<i class="fas fa-spinner fa-spin"></i> Submitting...');

            $.ajax({
                url: "{{ route('admin.tags') }}" +"/"+ tagId +"/update",
                type: 'POST',
                data: form.serialize(),
                success: function (res) {
                    self.prop('disabled', false);
                    self.html('Submit');

                    displayGrowlNotification(res.status, res.title, res.message, res.delay);

                    table.DataTable().destroy();

                    $('.allTagsTable').html(res.htmlResult);

                    $('.allTagsTable').dataTable({
                        "aaSorting": [],
                        "order": [[0, "asc"]]
                    });

                    $('#editTagModal').modal('hide');
                },
                error: function (err) {
                    self.prop('disabled', false);
                    self.html('Submit');

                    if ( err.status == 422) {
                        displayAlertNotification(err, 'errorsInEditingTag');
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

                        $('.allTagsTable').html(res.htmlResult);

                        $('.allTagsTable').dataTable({
                            "aaSorting": [],
                            "order": [[0, "asc"]]
                        });
                    },
                    error: function (err) {
                        self.prop('disabled', false);
                        self.html('Submit');

                        if ( err.status == 422) {
                            displayAlertNotification(err, 'errorsInEditingTag');
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
