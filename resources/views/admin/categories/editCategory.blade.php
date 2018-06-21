<div
    class="modal fade"
    id="editCategoryModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form
                    method="POST"
                    id="formEditCategory"
                    autocomplete="notWanted"
                >
                    @csrf

                    <div class="form-group">
                        <label class="normal" for="name">Category Name:</label>
                        <input
                            type="text"
                            name="name"
                            id="name"
                            value="{{ old('name') }}"
                            class="form-control hasMaxLength"
                            placeholder="Eg. My Logistics Co"
                            required="required"
                            autocomplete="sc-notWanted"
                            maxlength="250"
                        />
                    </div>

                    <div class="form-group">
                        <label class="normal" for="parent_id">Select Parent Category:</label>
                        <select name="parent_id" id="parent_id" class="form-control singleSelectize">
                            <option value="0" selected="selected">Parent Category</option>
                            @forelse($categories->sortBy('display_text') as $category)
                                <option value="{{ $category->id }}">{{ $category->display_text }}</option>
                            @empty
                            @endforelse
                        </select>
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
                            placeholder="Eg. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                            tempor incididunt ut labore et dolore magna aliqua."
                            autocomplete="sc-notWanted"
                            maxlength="250"
                        />
                    </div>

                    <div class="form-group">
                        <label class="normal" for="meta_title">Meta Title:</label>
                        <input
                            type="text"
                            name="meta_title"
                            id="meta_title"
                            value="{{ old('meta_title') }}"
                            class="form-control hasMaxLength"
                            placeholder="Eg. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                            tempor incididunt ut labore et dolore magna aliqua."
                            required="required"
                            autocomplete="sc-notWanted"
                            maxlength="60"
                        />
                    </div>

                    <div class="form-group">
                        <label class="normal" for="meta_description">Meta Desription:</label>
                        <input
                            type="text"
                            name="meta_description"
                            id="meta_description"
                            value="{{ old('meta_description') }}"
                            class="form-control hasMaxLength"
                            placeholder="Eg. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                            tempor incididunt ut labore et dolore magna aliqua."
                            required="required"
                            autocomplete="sc-notWanted"
                            maxlength="160"
                        />
                    </div>

                    <div class="form-group">
                        <label class="normal" for="meta_keywords">Meta Keywords (Optional):</label>
                        <input
                            type="text"
                            name="meta_keywords"
                            id="meta_keywords"
                            value="{{ old('meta_keywords') }}"
                            class="form-control hasMaxLength"
                            placeholder="Eg. Lorem ipsum, dolor sit amet, consectetur, adipisicing elit, sed do eiusmod, tempor incididunt, ut labore et, dolore magna aliqua."
                            autocomplete="sc-notWanted"
                            maxlength="250"
                        />
                    </div>

                    <div class="errorsInEditingCategory"></div>

                    <button class="btn submitButton btnEditCategory mt-3">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
