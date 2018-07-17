<div
    class="modal fade"
    id="addOrderHistoryModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add New Order History</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form
                    action="{{ route('admin.orders.storeHistory', $orders->first()->order_code) }}"
                    method="POST"
                    id="formAddOrderHistory"
                >
                    @csrf

                    <div class="form-group">
                        <label class="normal" for="select_products">Select Products (Multiple allowed):</label>
                        <select
                            name="select_products[]"
                            id="select_products"
                            class="form-control multipleSelect"
                            multiple="multiple"
                        >
                            @foreach ($historyProducts as $historyId => $product)
                                <option value="{{ $historyId }}">{{ $product }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="normal" for="status">Status:</label>
                        <select name="status" id="status" class="form-control">
                            <option value="Processing" selected="selected">Processing</option>
                            <option value="Shipped">Shipped</option>
                            <option value="Completed">Completed</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="normal" for="notes">Notes (Optional):</label>
                        <input
                            type="text"
                            name="notes"
                            id="notes"
                            value="{{ old('notes') }}"
                            class="form-control hasMaxLength"
                            placeholder="Eg. One line description about the order history"
                            autocomplete="sc-notWanted"
                            maxlength="250"
                        />
                    </div>

                    <div class="errorsInAddingOrderHistory"></div>

                    <button class="btn submitButton btnAddOrderHistory mt-3">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
