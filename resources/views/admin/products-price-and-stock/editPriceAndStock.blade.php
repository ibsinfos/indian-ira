<div
    class="modal fade"
    id="editPriceAndStockModal"
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
                <form
                    action=""
                    method="POST"
                    id="formEditPriceAndStock"
                >
                    @csrf

                    <div class="row">
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
                                    title="It should contain only numbers with optional decimals upto 2 digits only."
                                    data-placement="right"
                                />
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-6">
                            <div class="form-group">
                                <label class="normal" for="discount_price">Discount Price (Stockal):</label>
                                <input
                                    type="text"
                                    name="discount_price"
                                    id="discount_price"
                                    class="form-control"
                                    placeholder="150.00"
                                    data-toggle="tooltip"
                                    title="It should contain only numbers with optional decimals upto 2 digits only."
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
                                <label class="normal" for="gst_percent">GST Percent (Optional):</label>
                                <input
                                    type="text"
                                    name="gst_percent"
                                    id="gst_percent"
                                    class="form-control"
                                    placeholder="10"
                                    data-toggle="tooltip"
                                    title="It should contain only numbers with optional decimal upto 2 digits only."
                                    data-placement="right"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="errorsInEditingPriceAndStock"></div>

                    <button class="btn submitButton btnEditPriceAndStock mt-3">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
