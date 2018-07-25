<div
    class="modal fade"
    id="enquireProductModal"
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
                    method="POST"
                    id="formEnquireProduct"
                    autocomplete="notWanted"
                >
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="normal" for="user_full_name">User Full Name:</label>
                                <input
                                    type="text"
                                    name="user_full_name"
                                    id="user_full_name"
                                    class="form-control hasMaxLength"
                                    value="{{ old('user_full_name') }}"
                                    placeholder="Eg. John Doe"
                                    maxlength="200"
                                >
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="normal" for="user_email">User E-Mail:</label>
                                <input
                                    type="email"
                                    name="user_email"
                                    id="user_email"
                                    class="form-control"
                                    value="{{ old('user_email') }}"
                                    placeholder="Eg. johndoe&#64;example.com"
                                >
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="normal" for="user_contact_number">User Contact Number:</label>
                        <input
                            type="text"
                            name="user_contact_number"
                            id="user_contact_number"
                            class="form-control"
                            value="{{ old('user_contact_number') }}"
                            placeholder="Eg. 9876543210"
                        >
                    </div>

                    <div class="form-group">
                        <label class="normal" for="message_body">Message Body:</label>
                        <textarea
                            name="message_body"
                            id="message_body"
                            class="form-control hasMaxLength"
                            placeholder="Eg. Your message here..."
                            rows="8"
                            maxlength="1000"
                            style="resize: none;"
                        >{{ old('message_body') }}</textarea>
                    </div>

                    <div class="errorsInEnquiringProduct"></div>

                    <button class="btn submitButton btnEnquireProduct mt-3">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
