<table class="table table-striped table-bordered allUsersTable" data-page-length="100">
    <thead>
        <th>Sr. No</th>
        <th>User Details</th>
        <th>Is Verified</th>
        <th>Added On</th>
        <th>Action</th>
    </thead>

    <tbody>
        @forelse($users as $key => $user)
            <tr @if ($user->deleted_at != null) class="deletedRow" @endif>
                <td>{{ ++$key }}</td>
                <td>
                    {{ $user->getFullName() }}<br />
                    {{ $user->username }} / {{ $user->email }}<br />
                    {{ $user->contact_number }}<br />
                </td>
                <td>
                    @if ($user->isVerified())
                        Yes
                    @else
                        No
                        <a
                            href="{{ route('admin.users.verify', $user->id) }}"
                            class="btn btn-sm btn-outline-dark ajaxBtnOnTable"
                            data-inaction="Verifying"
                            data-comp="Verify"
                        >Verify</a>
                    @endif
                </td>
                <td>{{ $user->formatsCreatedAt() }}</td>
                <td>
                    @if ($user->deleted_at != null)
                        <a
                            href="{{ route('admin.users.restore', $user->id) }}"
                            class="btn btn-sm btn-success text-white ajaxBtnOnTable"
                            title="Restore this user details"
                            data-toggle="tooltip"
                            data-placement="top"
                            data-inaction="Restoring"
                            data-comp="Restore"
                        >
                            <i class="fas fa-sync-alt"></i>
                            Restore
                        </a>

                        <a
                            href="{{ route('admin.users.destroy', $user->id) }}"
                            class="btn btn-sm btn-outline-warning text-white ajaxBtnOnTable"
                            title="Permanently delete this user details"
                            data-toggle="tooltip"
                            data-placement="top"
                            data-inaction="Destroying"
                            data-comp="Destroy"
                        >
                            <i class="fas fa-trash"></i>
                            Destroy
                        </a>
                    @else
                        <a
                            href="{{ route('admin.users.edit', $user->id) }}?general-details"
                            class="btn btn-sm btn-info mb-sm-2 mb-md-2 mb-lg-0"
                            title="Edit this user details"
                        >
                            <i class="fas fa-pencil-alt"></i>
                            Edit
                        </a>

                        <a
                            href="{{ route('admin.users.delete', $user->id) }}"
                            class="btn btn-outline-warning btn-sm ajaxBtnOnTable"
                            title="Temporarily delete this user details"
                            data-toggle="tooltip"
                            data-placement="top"
                            data-inaction="Deleting"
                            data-comp="Delete"
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
