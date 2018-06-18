<div
    class="modal fade"
    id="addTagModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add New Tag</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form
                    action="{{ route('admin.tags.store') }}"
                    method="POST"
                    id="formAddTag"
                    autocomplete="form-ac-off"
                >
                    @csrf

                    <div class="form-group">
                        <label class="normal" for="name">Tag Name:</label>
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
                        <label class="normal" for="short_description">Short Desription:</label>
                        <input
                            type="text"
                            name="short_description"
                            id="short_description"
                            value="{{ old('short_description') }}"
                            class="form-control hasMaxLength"
                            placeholder="Eg. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                            tempor incididunt ut labore et dolore magna aliqua."
                            required="required"
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

                    <div class="errorsInAddingTag"></div>

                    <button class="btn submitButton btnAddTag mt-3">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
