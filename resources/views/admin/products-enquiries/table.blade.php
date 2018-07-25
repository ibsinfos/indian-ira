<table
    class="table table-bordered table-striped allProductsEnquiriesTable"
    data-page-length="100"
    style="font-size: 13px"
>
    <thead>
        <th>Sr. No.</th>
        <th>Code</th>
        <th>Product Details</th>
        <th>User Details</th>
        {{-- <th>Message</th> --}}
        <th>Enquiried On</th>
        <th>Action</th>
    </thead>

    <tbody>
        @foreach ($enquiries as $key => $enquiry)
            <tr @if ($enquiry->deleted_at != null) class="deletedRow" @endif>
                <td>{{ ++$key }}</td>
                <td>{{ $enquiry->code }}</td>
                <td>
                    <a href="{{ $enquiry->product_page_url }}" target="_blank">
                        <img
                            src="{{ url($enquiry->product_image) }}"
                            alt="{{ $enquiry->name }}"
                            class="float-left mr-3"
                        />
                    </a>

                    <a href="{{ $enquiry->product_page_url }}" target="_blank" class="mainSiteLink font-weight-bold">
                        {{ title_case($enquiry->product_name) }}
                    </a><br /><br />
                    {{ $enquiry->product_code }} / {{ $enquiry->option_code }}
                </td>
                <td>
                    Name: {{ $enquiry->user_full_name }}<br />
                    E-Mail: {{ $enquiry->user_email }}<br />
                    Contact: {{ $enquiry->user_contact_number }}
                </td>
                {{-- <td>{{ $enquiry->message_body }}</td> --}}
                <td>{{ $enquiry->formatsCreatedAt() }}</td>
                <td>
                    @if ($enquiry->deleted_at != null)
                        <a
                            href="{{ route('admin.products.enquiries.restore', $enquiry->code) }}"
                            class="btn btn-sm btn-success text-white ajaxBtnOnTable"
                            title="Restore this enquiry details"
                            data-toggle="tooltip"
                            data-placement="top"
                        >
                            <i class="fas fa-sync-alt"></i>
                            Restore
                        </a>

                        <a
                            href="{{ route('admin.products.enquiries.destroy', $enquiry->code) }}"
                            class="btn btn-sm btn-outline-warning text-white ajaxBtnOnTable"
                            title="Permanently delete this enquiry details"
                            data-toggle="tooltip"
                            data-placement="top"
                        >
                            <i class="fas fa-trash"></i>
                            Destroy
                        </a>
                    @else
                        <a
                            href="#"
                            class="btn btn-info btn-sm mb-2"
                            title="View the details of this enquiry"
                            data-toggle="modal"
                            data-target="#viewEnquiryDetails"
                            data-enquiry="{{ $enquiry }}"
                        >
                            <i class="fas fa-eye"></i>
                            View
                        </a>

                        <a
                            href="{{ route('admin.products.enquiries.delete', $enquiry->code) }}"
                            class="btn btn-outline-warning btn-sm ajaxBtnOnTable"
                            title="Temporarily delete this enquiry details"
                            data-toggle="tooltip"
                            data-placement="top"
                        >
                            <i class="fas fa-trash"></i>
                            Delete
                        </a>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
