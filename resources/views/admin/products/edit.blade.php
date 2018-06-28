@extends('admin.partials._layout')

@section('title_and_meta_info')
    <title>Edit Product: {{ $product->name }}</title>
@endsection

@section('content')
<div class="container-fluid">
    <nav class="mb-3" aria-label="breadcrumb">
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
            <li class="breadcrumb-item active" aria-current="page">Edit {{ $product->name }}</li>
        </ol>
    </nav>

    @include('admin.products._editing_links')

    <div class="row">
        <div class="mb-xl-5 col-xl-12 col-lg-12">
            @if (request()->exists('general'))
                @include('admin.products._general')
            @endif

            @if (request()->exists('detailed-information'))
                @include('admin.products._detailed_information')
            @endif

            @if (request()->exists('meta-information'))
                @include('admin.products._meta_information')
            @endif

            @if (request()->exists('image'))
                @include('admin.products._image')
            @endif
        </div>
    </div>
</div>

@include('admin.products.addProduct')

<div class="mb-5"></div>
@endsection

@section('pageScripts')
    @if (request()->exists('detailed-information'))
        <script src="{{ url('/ckeditor/ckeditor.js') }}"></script>
    @endif

    <script>
        @if (request()->exists('detailed-information'))
            CKEDITOR.replace('description');
            CKEDITOR.replace('additional_notes');
            CKEDITOR.replace('terms');

            CKEDITOR.config.wordcount = {
                showCharCount: true,
                maxCharCount: 3000,
                countSpacesAsChars: true,
            };
        @endif

        @if (request()->exists('general'))
            var $selectize = $('#category_id').selectize();
            var $tagsSelectize = $('#tag_id').selectize();

            $selectize[0].selectize.setValue([{{ $selectedCategories }}]);
            $tagsSelectize[0].selectize.setValue([{{ $selectedTags }}]);
        @endif

        $('.btnUpdateGeneralDetails').click(function (e) {
            e.preventDefault();

            var form = $("#formUpdateGeneralDetails"),
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

                    if (res.status == 'success') {}
                },
                error: function (err) {
                    self.prop('disabled', false);
                    self.html('Submit');

                    if ( err.status == 422) {
                        displayAlertNotification(err, 'errorsInUpdatingGeneralDetails');
                    } else {
                        alert('Something went wrong. Please try again later.');
                    }
                },
            });

            return false;
        });

        $('.btnUpdateDetailedInformation').click(function (e) {
            e.preventDefault();

            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }

            var form = $("#formUpdateDetailedInformation"),
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

                    if (res.status == 'success') {}
                },
                error: function (err) {
                    self.prop('disabled', false);
                    self.html('Submit');

                    if ( err.status == 422) {
                        displayAlertNotification(err, 'errorsInUpdatingDetailedInformation');
                    } else {
                        alert('Something went wrong. Please try again later.');
                    }
                },
            });

            return false;
        });

        $('.btnUpdateMetaInformation').click(function (e) {
            e.preventDefault();

            var form = $("#formUpdateMetaInformation"),
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

                    if (res.status == 'success') {}
                },
                error: function (err) {
                    self.prop('disabled', false);
                    self.html('Submit');

                    if ( err.status == 422) {
                        displayAlertNotification(err, 'errorsInUpdatingMetaInformation');
                    } else {
                        alert('Something went wrong. Please try again later.');
                    }
                },
            });

            return false;
        });

        $("form[id='formUpdateImage']").submit(function(e) {
            e.preventDefault();

            var inputData = new FormData($(this)[0]),
                button    = $('.btnUpdateImage');

            button.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Submitting...');

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: inputData,
                async: true,
                success: function( res ) {
                    button.prop('disabled', false).html('Submit');

                    displayGrowlNotification(res.status, res.title, res.message, res.delay);

                    if (res.status == 'success') {
                        setTimeout(function () {
                            window.location = "{{ route('admin.products') }}";
                        }, res.delay + 1000);
                    }
                },
                error: function( data ) {
                    button.prop('disabled', false).html('Submit');

                    if ( data.status == 422) {
                        displayAlertNotification(data, 'errorsInUpdatingImage');
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
