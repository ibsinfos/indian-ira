<div
    class="modal fade"
    id="addProductModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add New Product</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form
                    action="{{ route('admin.products.store') }}"
                    method="POST"
                    id="formAddProduct"
                >
                    @csrf

                    <div class="form-group">
                        <label class="normal" for="code">Product Code:</label>
                        <input
                            type="text"
                            name="code"
                            id="code"
                            value="{{ old('code') }}"
                            class="form-control hasMaxLength"
                            placeholder="Eg. PRD-01"
                            required="required"
                            autocomplete="sc-notWanted"
                            data-toggle="tooltip"
                            title="It should contain only alphabets, numbers, dashes (-) and underscores ( _ ) without any space."
                            maxlength="100"
                        />
                    </div>

                    <div class="form-group">
                        <label class="normal" for="name">Product Name:</label>
                        <input
                            type="text"
                            name="name"
                            id="name"
                            value="{{ old('name') }}"
                            class="form-control hasMaxLength"
                            placeholder="Eg. Product 1"
                            required="required"
                            autocomplete="sc-notWanted"
                            maxlength="250"
                        />
                    </div>

                    <div class="form-group">
                        <label class="normal" for="number_of_options">Number of Options:</label>
                        <select name="number_of_options" id="number_of_options" class="form-control singleSelectize">
                            <option value="0" selected="selected">
                                0 Options
                            </option>
                            <option value="1">
                                1 Option - (Eg: Food with option of weight)
                            </option>
                            <option value="2">
                                2 Options - (Eg: Apparel with option of Color and Size)
                            </option>
                        </select>
                    </div>

                    <div class="errorsInAddingProduct"></div>

                    <button class="btn submitButton btnAddProduct mt-3">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
