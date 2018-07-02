<div
    class="modal fade"
    id="addCarouselModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add New Carousel</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form
                    action="{{ route('admin.carousels.store') }}"
                    method="POST"
                    id="formAddCarousel"
                >
                    @csrf

                    <div class="form-group">
                        <label class="normal" for="name">Carousel Name:</label>
                        <input
                            type="text"
                            name="name"
                            id="name"
                            value="{{ old('name') }}"
                            class="form-control hasMaxLength"
                            placeholder="Eg. Recommended"
                            required="required"
                            autocomplete="sc-notWanted"
                            maxlength="100"
                        />
                    </div>

                    <div class="form-group">
                        <label class="normal" for="display">Display:</label>
                        <select name="display" id="display" class="form-control singleSelectize">
                            <option value="Enabled" selected="selected">Enabled</option>
                            <option value="Disabled">Disabled</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="normal" for="short_description">Short Desription (Optional):</label>
                        <input
                            type="text"
                            name="short_description"
                            id="short_description"
                            value="{{ old('short_description') }}"
                            class="form-control hasMaxLength"
                            placeholder="Eg. One line description about the carousel"
                            autocomplete="sc-notWanted"
                            maxlength="250"
                        />
                    </div>

                    <div class="form-group">
                        <label class="normal" for="product_id">Select Products (Multiple allowed):</label>
                        <select
                            name="product_id[]"
                            id="product_id"
                            class="form-control multipleSelect"
                            multiple="multiple"
                        >
                            @foreach($products->sortBy('name') as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="errorsInAddingCarousel"></div>

                    <button class="btn submitButton btnAddCarousel mt-3">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
