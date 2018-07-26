<div
    class="modal fade"
    id="editProductSortNumberModal"
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
                    id="formEditProductSortNumber"
                >
                    @csrf

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

                    <div class="errorsInEditingProductSortNumber"></div>

                    <button class="btn submitButton btnEditProductSortNumber mt-3">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
