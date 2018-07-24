<div class="card">
    <div class="card-header">
        <h3 class="header p-0 m-0">
            Edit Inter-related Product: {{ $product->name }}

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
            The products that may match with this product shall be added here.<br /><br />
            For example, if you are selling Bread, you can select Butter and/or Jam in the field below.<br /><br />
            The products that you will select will be shown in the Product's page in a Carousel format.
        </div>

        <form
            action="{{ route('admin.products.updateInterRelatedProducts', $product->id) }}"
            method="POST"
            id="formUpdateInterRelatedProducts"
            autocomplete="notWanted"
            enctype="multipart/form-data"
        >
            @csrf

            <div class="form-group">
                <label class="normal" for="product_id">Select Related Products:</label>
                <select name="product_id[]" id="product_id" class="multipleSelect" multiple="multiple">
                    @foreach ($allProducts as $product)
                        <option value="{{ $product->id }}">
                            {{ title_case($product->name) }} - {{ $product->code }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="errorsInUpdatingInterRelatedProducts"></div>

            <button class="btn submitButton btnUpdateInterRelatedProducts mt-3">Submit</button>
        </form>
    </div>
</div>
