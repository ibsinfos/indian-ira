<div
    class="modal fade"
    id="editPriceAndOptionModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="help-block text-danger mb-5" style="font-size: 13px;">
                    The image should be in .jpg or .png file format only.<br />
                    The image should be between 500px to 1280px in width and 500px to 1280px in height.<br />
                    Ideal size: 1280px x 1280px.<br />
                    The image file size should be less than 600kB.<br /><br />
                </div>

                <form
                    action=""
                    method="POST"
                    id="formEditPriceAndOption"
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
                    </div>

                    <div class="form-group">
                        <label class="normal" for="image_file">Image file (Optional):</label>
                        <input
                            type="file"
                            name="image_file"
                            id="image_file"
                            class="form-control"
                            title="This image will replace the image that you added while editing the products."
                            data-toggle="tooltip"
                            data-placement="right"
                        />
                        <span class="viewImageFile"></span>
                    </div>

                    <div class="errorsInEditingPriceAndOption"></div>

                    <button class="btn submitButton btnEditPriceAndOption mt-3">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
