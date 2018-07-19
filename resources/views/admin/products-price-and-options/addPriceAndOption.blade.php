<div
    class="modal fade"
    id="addPriceAndOptionModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add New Price And Option</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form
                    action="{{ route('admin.products.priceAndOptions.store', $product->id) }}"
                    method="POST"
                    id="formAddPriceAndOption"
                    enctype="multipart/form-data"
                >
                    @csrf

                    <div class="row">
                        <div class="col-md-6 col-sm-6 option1Heading">
                            <div class="form-group">
                                <label class="normal" for="option_1_heading">Option 1 Heading:</label>
                                <input
                                    type="text"
                                    name="option_1_heading"
                                    id="option_1_heading"
                                    class="form-control hasMaxLength"
                                    placeholder="Eg. Size"
                                    maxlength="100"
                                />
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-6 option1Heading">
                            <div class="form-group">
                                <label class="normal" for="option_1_value">Option 1 Value:</label>
                                <input
                                    type="text"
                                    name="option_1_value"
                                    id="option_1_value"
                                    class="form-control hasMaxLength"
                                    placeholder="Eg. XL"
                                    maxlength="100"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-sm-6 option2Heading">
                            <div class="form-group">
                                <label class="normal" for="option_2_heading">Option 2 Heading:</label>
                                <input
                                    type="text"
                                    name="option_2_heading"
                                    id="option_2_heading"
                                    class="form-control hasMaxLength"
                                    placeholder="Eg. Color"
                                    maxlength="100"
                                />
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-6 option2Heading">
                            <div class="form-group">
                                <label class="normal" for="option_2_value">Option 2 Value:</label>
                                <input
                                    type="text"
                                    name="option_2_value"
                                    id="option_2_value"
                                    class="form-control hasMaxLength"
                                    placeholder="Eg. Blue"
                                    maxlength="100"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-sm-6">
                            <div class="form-group">
                                <label class="normal" for="option_code">Product Option Code:</label>
                                <input
                                    type="text"
                                    name="option_code"
                                    id="option_code"
                                    class="form-control hasMaxLength"
                                    required="required"
                                    placeholder="Eg. Size"
                                    maxlength="100"
                                    data-toggle="tooltip"
                                    title="It should contain only alphabets / numbers / dashes (-) / underscores ( _ )"
                                    data-placement="right"
                                />
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-6">
                            <div class="form-group">
                                <label class="normal" for="display">Display:</label>
                                <select name="display" id="display" class="form-control">
                                    <option value="Enabled" selected="selected">Enabled</option>
                                    <option value="Disabled">Disabled</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-6">
                            <div class="form-group">
                                <label class="normal" for="selling_price">Selling Price:</label>
                                <input
                                    type="text"
                                    name="selling_price"
                                    id="selling_price"
                                    class="form-control"
                                    placeholder="200.00"
                                    data-toggle="tooltip"
                                    title="It should contain only numbers with optional decimals upto 2 precisions."
                                    data-placement="right"
                                />
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-6">
                            <div class="form-group">
                                <label class="normal" for="discount_price">Discount Price (Optional):</label>
                                <input
                                    type="text"
                                    name="discount_price"
                                    id="discount_price"
                                    class="form-control"
                                    placeholder="150.00"
                                    data-toggle="tooltip"
                                    title="It should contain only numbers with optional decimals upto 2 precisions."
                                    data-placement="right"
                                />
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-6">
                            <div class="form-group">
                                <label class="normal" for="stock">Stock:</label>
                                <input
                                    type="text"
                                    name="stock"
                                    id="stock"
                                    class="form-control"
                                    placeholder="10"
                                    data-toggle="tooltip"
                                    title="It should contain only numbers."
                                    data-placement="right"
                                />
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-6">
                            <div class="form-group">
                                <label class="normal" for="sort_number">Sort Number:</label>
                                <input
                                    type="text"
                                    name="sort_number"
                                    id="sort_number"
                                    class="form-control"
                                    placeholder="10"
                                    data-toggle="tooltip"
                                    title="It should contain only numbers."
                                    data-placement="right"
                                />
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-6">
                            <div class="form-group">
                                <label class="normal" for="weight">Weight (in grams):</label>
                                <input
                                    type="text"
                                    name="weight"
                                    id="weight"
                                    class="form-control"
                                    placeholder="500"
                                    data-toggle="tooltip"
                                    title="It should contain only numbers with optional decimals upto 2 precisions."
                                    data-placement="right"
                                />
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-6">
                            <div class="form-group">
                                <label class="normal" for="image_file">Image file (Optional):</label>
                                <input
                                    type="file"
                                    name="image_file"
                                    id="image_file"
                                    class="form-control"
                                    title="This image will replace the image that you added while editing the products.<br /><br />
                                           It should be in .jpg or .png file format only.<br /><br />
                                           It should be between 500px to 1280px in width and 500px to 1280px in height.<br /><br />
                                           Ideal size: 1280px x 1280px.<br /><br />
                                           It should be less than 600kB.<br />"
                                    data-toggle="tooltip"
                                    data-placement="right"
                                    data-html="true"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="normal" for="gallery_image_file_1">Gallery Image file 1 (Optional):</label>
                        <input
                            type="file"
                            name="gallery_image_file_1"
                            id="gallery_image_file_1"
                            class="form-control"
                        />
                    </div>

                    <div class="form-group">
                        <label class="normal" for="gallery_image_file_2">Gallery Image file 2 (Optional):</label>
                        <input
                            type="file"
                            name="gallery_image_file_2"
                            id="gallery_image_file_2"
                            class="form-control"
                        />
                    </div>

                    <div class="form-group">
                        <label class="normal" for="gallery_image_file_3">Gallery Image file 3 (Optional):</label>
                        <input
                            type="file"
                            name="gallery_image_file_3"
                            id="gallery_image_file_3"
                            class="form-control"
                        />
                    </div>

                    <div class="errorsInAddingPriceAndOption"></div>

                    <button class="btn submitButton btnAddPriceAndOption mt-3">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
