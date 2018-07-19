<div class="card">
    <div class="card-header">
        <h3 class="header p-0 m-0">
            Edit General Details of Product: {{ $product->name }}

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
            action="{{ route('admin.products.updateGeneral', $product->id) }}"
            method="POST"
            id="formUpdateGeneralDetails"
        >
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="normal" for="code">Product Code:</label>
                        <input
                            type="text"
                            name="code"
                            id="code"
                            value="{{ $product->code }}"
                            class="form-control hasMaxLength"
                            placeholder="Eg. PRD-1"
                            required="required"
                            data-toggle="tooltip"
                            title="It should contain only alphabets, numbers, dashes (-) and underscores ( _ )"
                            maxlength="100"
                        />
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="normal" for="code">Product Name:</label>
                        <input
                            type="text"
                            name="name"
                            id="name"
                            value="{{ $product->name }}"
                            class="form-control hasMaxLength"
                            placeholder="Eg. Product 1"
                            required="required"
                            maxlength="100"
                        />
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label
                            class="normal"
                            for="number_of_options"
                        >
                        Number of Options:
                        <span
                            data-html="true"
                            data-toggle="tooltip"
                            title="Option 0: No Options at all<br />
                                   Option 1: Example: Food with option of weight<br />
                                   Option 2: Example: Apparel with option of Color and Size<br />"
                        ><i class="fas fa-question-circle"></i></span>
                    </label>
                        <select name="number_of_options" id="number_of_options" class="singleSelectize">
                            <option value="0" @if ($product->number_of_options == 0) selected="selected" @endif>
                                0 Options
                            </option>
                            <option value="1" @if ($product->number_of_options == 1) selected="selected" @endif>
                                1 Option
                            </option>
                            <option value="2" @if ($product->number_of_options == 2) selected="selected" @endif>
                                2 Options
                            </option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="normal" for="display">Display:</label>
                        <select name="display" id="display" class="singleSelectize">
                            <option value="Enabled" @if ($product->display == 'Enabled') selected="selected" @endif>
                                Enabled
                            </option>
                            <option value="Disabled" @if ($product->display == 'Disabled') selected="selected" @endif>
                                Disabled
                            </option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="normal" for="sort_number">Sort Number:</label>
                        <input
                            type="text"
                            name="sort_number"
                            id="sort_number"
                            value="{{ $product->sort_number != 0 ? $product->sort_number : 0 }}"
                            class="form-control"
                            placeholder="Eg. 10"
                        />
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="normal" for="gst_percent">GST Percent (Optional):</label>
                        <input
                            type="text"
                            name="gst_percent"
                            id="gst_percent"
                            value="{{ $product->gst_percent != 0 ? $product->gst_percent : '' }}"
                            class="form-control"
                            placeholder="Eg. 18"
                        />
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="normal" for="category_id">Select categories:</label>
                <select name="category_id[]" id="category_id" class="multipleSelect" multiple="multiple">
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->display_text }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="normal" for="tag_id">Select Tags (Optional):</label>
                <select name="tag_id[]" id="tag_id" class="multipleSelect" multiple="multiple">
                    @foreach ($tags as $tag)
                        <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="errorsInUpdatingGeneralDetails"></div>

            <button class="btn submitButton btnUpdateGeneralDetails mt-3">Submit</button>
        </form>
    </div>
</div>
