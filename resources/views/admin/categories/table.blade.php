<table class="table table-striped table-bordered allCategoriesTable" data-page-length="100">
    <thead>
        <th>Sr. No</th>
        <th>Category Name</th>
        {{-- <th>Display Text</th> --}}
        <th>Display</th>
        <th>Added On</th>
        <th>Action</th>
    </thead>

    <tbody>
        @forelse($categories as $key => $category)
            <tr @if ($category->deleted_at != null) class="deletedRow" @endif>
                <td>{{ ++$key }}</td>
                <td>{{ $category->name }}</td>
                {{-- <td>{{ $category->display_text }}</td> --}}
                <td>{{ $category->display }}</td>
                <td>{{ $category->formatsCreatedAt() }}</td>
                <td>
                    @if ($category->deleted_at != null)
                        <a
                            href="{{ route('admin.categories.restore', $category->id) }}"
                            class="btn btn-sm btn-success text-white ajaxBtnOnTable"
                            title="Restore this category details"
                            data-toggle="tooltip"
                            data-placement="top"
                        >
                            <i class="fas fa-sync-alt"></i>
                            Restore
                        </a>

                        <a
                            href="{{ route('admin.categories.destroy', $category->id) }}"
                            class="btn btn-sm btn-outline-warning text-white ajaxBtnOnTable"
                            title="Permanently delete this category details"
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
                            title="Edit this category details"
                            data-toggle="modal"
                            data-target="#editCategoryModal"
                            data-id="{{ $category->id }}"
                            data-category="{{ $category }}"
                        >
                            <i class="fas fa-pencil-alt"></i>
                            Edit
                        </a>

                        <a
                            href="{{ route('admin.categories.delete', $category->id) }}"
                            class="btn btn-outline-warning btn-sm ajaxBtnOnTable"
                            title="Temporarily delete this category details"
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
