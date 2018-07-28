<div class="card">
    <div class="card-header">
        <h3 class="header p-0 m-0">
            Edit Image of Product: {{ $product->name }}

            <div class="float-right">
                <a
                    href="#"
                    class="btn btn-light btn-sm font-weight-bold shadow-none mt-md-0 mt-lg-0 mt-xl-0 mt-sm-4"
                    data-toggle="modal"
                    data-target="#addProductModal"
                >Add</a>
                <a
                    href="{{ route('admin.products') }}"
                    class="btn btn-light btn-sm font-weight-bold shadow-none mt-md-0 mt-lg-0 mt-xl-0 mt-sm-4"
                >Go to Products</a>
                <a
                    href="{{ route('admin.dashboard') }}"
                    class="btn btn-outline-light text-black btn-sm font-weight-bold shadow-none mt-md-0 mt-lg-0 mt-xl-0 mt-sm-4"
                >Go to Dashboard</a>
            </div>
        </h3>
    </div>

    <div class="card-body">
        <div class="help-block text-danger mb-5" style="font-size: 13px;">
            The image should be in .jpg or .png file format only.<br />
            The image should be between 500px to 1280px in width and 500px to 1280px in height.<br />
            Ideal size: 1280px x 1280px.<br />
            The image file size should be less than 600kB.<br /><br />

            This image will get replaced by the image that you may upload while adding / editing the <a href="{{ route('admin.products.priceAndOptions', $product->id) }}" class="mainSiteLink font-weight-bold">prices and options</a> related to this product.<br /><br />
            The Gallery Images are optional.<br />
        </div>

        <div class="mb-5 text-center">
            <img
                src="{{ url($product->cartImage()) }}"
                alt="{{ $product->name }}"
                class="rounded-circle"
            />
        </div>

        <form
            action="{{ route('admin.products.updateImage', $product->id) }}"
            method="POST"
            id="formUpdateImage"
            autocomplete="notWanted"
            enctype="multipart/form-data"
        >
            @csrf

            <div class="errorsInUpdatingImage"></div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="normal" for="image">Product Image File:</label>
                        <input
                            type="file"
                            name="image"
                            id="image"
                            class="form-control"
                            required="required"
                        />
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="normal" for="gallery_image_file_1" style="font-weight: 300 !important;">Gallery Image 1 (Optional):</label>
                        <input
                            type="file"
                            name="gallery_image_file_1"
                            id="gallery_image_file_1"
                            class="form-control"
                        />
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="normal" for="gallery_image_file_2" style="font-weight: 300 !important;">Gallery Image 2 (Optional):</label>
                        <input
                            type="file"
                            name="gallery_image_file_2"
                            id="gallery_image_file_2"
                            class="form-control"
                        />
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="normal" for="gallery_image_file_3" style="font-weight: 300 !important;">Gallery Image 3 (Optional):</label>
                        <input
                            type="file"
                            name="gallery_image_file_3"
                            id="gallery_image_file_3"
                            class="form-control"
                        />
                    </div>
                </div>
            </div>

            <button class="btn submitButton btnUpdateImage mt-3">Submit</button>
        </form>
    </div>
</div>
