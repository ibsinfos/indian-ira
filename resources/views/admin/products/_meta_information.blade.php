<div class="card">
    <div class="card-header">
        <h3 class="header p-0 m-0">
            Edit Meta Information of Product: {{ $product->name }}

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
        <div class="mb-5 text-danger" style="font-size: 14px;">
            Search Engines like Google, Bing, Yahoo, etc. look for this data to index your Product Page in their search results.
        </div>

        <form
            action="{{ route('admin.products.updateMetaInformation', $product->id) }}"
            method="POST"
            id="formUpdateMetaInformation"
        >
            @csrf

            <div class="form-group">
                <label class="normal" for="meta_title">Meta Title:</label>
                <input
                    type="text"
                    name="meta_title"
                    id="meta_title"
                    value="{{ $product->meta_title }}"
                    class="form-control hasMaxLength"
                    placeholder="Eg. Lorem ipsum dolor sit amet, consectetur adipisicing elit."
                    required="required"
                    maxlength="60"
                />
            </div>

            <div class="form-group">
                <label class="normal" for="meta_description">Meta Description:</label>
                <textarea
                    name="meta_description"
                    id="meta_description"
                    class="form-control hasMaxLength"
                    placeholder="Eg. Lorem ipsum dolor sit amet, consectetur adipisicing elit."
                    required="required"
                    maxlength="160"
                >{{ $product->meta_description }}</textarea>
            </div>

            <div class="form-group">
                <label class="normal" for="meta_keywords">Meta Keywords (Optional):</label>
                <textarea
                    name="meta_keywords"
                    id="meta_keywords"
                    class="form-control hasMaxLength"
                    placeholder="Eg. Lorem ipsum, dolor sit amet, consectetur, adipisicing, elit."
                    required="required"
                    maxlength="150"
                >{{ $product->meta_keywords }}</textarea>
            </div>

            <div class="errorsInUpdatingMetaInformation"></div>

            <button class="btn submitButton btnUpdateMetaInformation mt-3">Submit</button>
        </form>
    </div>
</div>
