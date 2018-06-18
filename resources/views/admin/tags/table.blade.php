<table class="table table-striped table-bordered allTagsTable" data-page-length="100">
    <thead>
        <th>Sr. No</th>
        <th>Name</th>
        <th>Slug</th>
        <th>Added On</th>
        <th>Action</th>
    </thead>

    <tbody>
        @foreach($tags as $key => $tag)
            <tr @if ($tag->deleted_at != null) class="deletedRow" @endif>
                <td>{{ ++$key }}</td>
                <td>{{ $tag->name }}</td>
                <td>{{ $tag->slug }}</td>
                <td>{{ $tag->formatsCreatedAt() }}</td>
                <td>
                    @if ($tag->deleted_at != null)
                        <a
                            href="{{ route('admin.tags.restore', $tag->id) }}"
                            class="btn btn-sm btn-success text-white ajaxBtnOnTable"
                            title="Restore this tag details"
                            data-toggle="tooltip"
                            data-placement="top"
                        >
                            <i class="fas fa-sync-alt"></i>
                            Restore
                        </a>

                        <a
                            href="{{ route('admin.tags.destroy', $tag->id) }}"
                            class="btn btn-sm btn-outline-warning text-white ajaxBtnOnTable"
                            title="Permanently delete this tag details"
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
                            title="Edit this tag details"
                            data-toggle="modal"
                            data-target="#editTagModal"
                            data-id="{{ $tag->id }}"
                            data-tag="{{ $tag }}"
                        >
                            <i class="fas fa-pencil-alt"></i>
                            Edit
                        </a>

                        <a
                            href="{{ route('admin.tags.delete', $tag->id) }}"
                            class="btn btn-outline-warning btn-sm ajaxBtnOnTable"
                            title="Temporarily delete this tag details"
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
