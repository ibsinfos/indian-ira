<div
    class="modal fade"
    id="addCategoryModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add New Category</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <p class="text-danger text-justify mb-4 form-control-sm p-0">
                    If the <span class="font-weight-bold">Display in Menu</span> field is Yes, then this menu will be shown in the Navigation Menu Bar.
                </p>

                <form
                    action="{{ route('admin.categories.store') }}"
                    method="POST"
                    id="formAddCategory"
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

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="normal" for="display">Display:</label>
                                <select name="display" id="display" class="form-control">
                                    <option value="Enabled" selected="selected">Enabled</option>
                                    <option value="Disabled">Disabled</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="normal" for="display_in_menu">Display In Menu:</label>
                                <select name="display_in_menu" id="display_in_menu" class="form-control">
                                    <option value="0" selected="selected">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>
                        </div>
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

                    <div class="errorsInAddingCategory"></div>

                    <button class="btn submitButton btnAddCategory mt-3">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
