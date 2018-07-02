<table class="table table-striped table-bordered allCarouselsTable" data-page-length="100">
    <thead>
        <th>Sr. No</th>
        <th>Carousel Name</th>
        <th>Display</th>
        <th>Added On</th>
        <th>Action</th>
    </thead>

    <tbody>
        @forelse($carousels as $key => $carousel)
            <tr @if ($carousel->deleted_at != null) class="deletedRow" @endif>
                <td>{{ ++$key }}</td>
                <td>{{ $carousel->name }}</td>
                <td>{{ $carousel->display }}</td>
                <td>{{ $carousel->formatsCreatedAt() }}</td>
                <td>
                    @if ($carousel->deleted_at != null)
                        <a
                            href="{{ route('admin.carousels.restore', $carousel->id) }}"
                            class="btn btn-sm btn-success text-white ajaxBtnOnTable"
                            title="Restore this carousel details"
                            data-toggle="tooltip"
                            data-placement="top"
                        >
                            <i class="fas fa-sync-alt"></i>
                            Restore
                        </a>

                        <a
                            href="{{ route('admin.carousels.destroy', $carousel->id) }}"
                            class="btn btn-sm btn-outline-warning text-white ajaxBtnOnTable"
                            title="Permanently delete this carousel details"
                            data-toggle="tooltip"
                            data-placement="top"
                        >
                            <i class="fas fa-trash"></i>
                            Destroy
                        </a>
                    @else
                        <a
                            href="#"
                            class="btn btn-sm btn-info mb-sm-2 mb-md-2 mb-lg-0"
                            title="Edit this carousel details"
                            data-toggle="modal"
                            data-target="#editCarouselModal"
                            data-id="{{ $carousel->id }}"
                            data-carousel="{{ $carousel }}"
                            data-products="{{ json_encode($carousel->products->pluck('id')->toArray()) }}"
                        >
                            <i class="fas fa-pencil-alt"></i>
                            Edit
                        </a>

                        <a
                            href="{{ route('admin.carousels.delete', $carousel->id) }}"
                            class="btn btn-outline-warning btn-sm ajaxBtnOnTable"
                            title="Temporarily delete this carousel details"
                            data-toggle="tooltip"
                            data-placement="top"
                        >
                            <i class="fas fa-trash"></i>
                            Delete
                        </a>
                    @endif
                </td>
            </tr>
        @empty
        @endforelse
    </tbody>
</table>
