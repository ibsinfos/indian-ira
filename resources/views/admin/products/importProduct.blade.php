<div
    class="modal fade"
    id="importProductsModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Upload Products</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <p class="text-danger" style="font-size: 14px">
                    If the data is found in the records, it will be updated.<br />
                    If the data is not found in the records, it will be created.
                </p>

                <p class="text-danger" style="font-size: 14px">
                    The file should be in .xls or .xlsx format only.
                </p>

                <p class="text-danger" style="font-size: 14px">
                    Only Three levels of categories can be added.
                </p>

                <form
                    action="{{ route('admin.products.upload') }}"
                    method="POST"
                    id="formImportProduct"
                    enctype="multipart/form-data"
                >
                    @csrf

                    <div class="form-group">
                        <label for="excel_file">Upload file:</label>
                        <input
                            type="file"
                            name="excel_file"
                            id="excel_file"
                            class="form-control"
                            required="required"
                        >
                    </div>

                    <div class="errorsInImportingProducts"></div>

                    <button type="submit" class="btn submitButton btnImportProducts">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
