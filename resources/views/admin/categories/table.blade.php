<table class="table table-striped table-bordered allCategoriesTable" data-page-length="100">
    <thead>
        <th style="width: 5%">Sr. No</th>
        <th>Category Name</th>
        <th>Display Text</th>
        <th>Display</th>
        <th>Display In Menu</th>
        <th>Added On</th>
        <th>Action</th>
    </thead>

    <tbody>
        @forelse($categories as $key => $category)
            <tr @if ($category->deleted_at != null) class="deletedRow" @endif>
                <td>{{ ++$key }}</td>
                <td>{{ $category->name }}</td>
                <td>{{ $category->display_text }}</td>
                <td>{{ $category->display }}</td>
                <td>{{ $category->display_in_menu == true ? 'Yes' : 'No' }}</td>
                <td>{{ $category->formatsCreatedAt() }}</td>
                <td>
                    <a
                        href="{{ route('admin.categories.products', $category->id) }}"
                        class="btn btn-sm btn-outline-dark mb-sm-2 mb-md-2 mb-lg-2"
                        target="_blank"
                        title="View the products that are listed in this category"
                        data-toggle="tooltip"
                    >View Products</a>

                    @if ($category->deleted_at != null)
                        <a
                            href="{{ route('admin.categories.restore', $category->id) }}"
                            class="btn btn-sm btn-success text-white ajaxBtnOnTable mb-sm-2 mb-md-2 mb-lg-2"
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
                            class="btn btn-sm btn-info mb-sm-2 mb-md-2 mb-lg-2"
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
