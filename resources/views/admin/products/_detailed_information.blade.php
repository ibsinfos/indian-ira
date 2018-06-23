<div class="card">
    <div class="card-header">
        <h3 class="header p-0 m-0">
            Edit Detailed Information of Product: {{ $product->name }}

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
        <form
            action="{{ route('admin.products.updateDetailedInformation', $product->id) }}"
            method="POST"
            id="formUpdateDetailedInformation"
        >
            @csrf

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="normal" for="description">Description:</label>
                        <textarea name="description" id="description">{!! $product->description !!}</textarea>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label class="normal" for="additional_notes">Additional Notes (Optional):</label>
                        <textarea name="additional_notes" id="additional_notes">{!! $product->additional_notes !!}</textarea>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label class="normal" for="terms">Terms (Optional):</label>
                        <textarea name="terms" id="terms">{!! $product->terms !!}</textarea>
                    </div>
                </div>
            </div>

            <div class="errorsInUpdatingDetailedInformation"></div>

            <button class="btn submitButton btnUpdateDetailedInformation mt-3">Submit</button>
        </form>
    </div>
</div>
