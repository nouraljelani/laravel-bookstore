@extends('theme.default')
@section('head') <link href="{{ asset('theme/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet"> @endsection
@section('heading') Show Users @endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <table id="books-table" class="table table-striped table-bordered text-right" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>E-Mail</th>
                    <th>Administration Level</th>
                    <th>Options</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->isSuperAdmin() ? 'Super Admin' : ($user->isAdmin() ? 'Admin' : 'User') }}</td>
                        <td>
                            <form class="ml-4 form-inline" method="POST" action="{{ route('users.update', $user) }}" style="display:inline-block">
                                @method('patch')
                                @csrf
                                <select class="form-control form-control-sm" name="administration_level">
                                    <option selected disabled>Chooes The Level</option>
                                    <option value="0">User</option>
                                    <option value="1">Admin</option>
                                    <option value="2">Super Admin</option>
                                </select>
                                <button type="submit" class="btn btn-info btn-sm"><i class="fa fa-edit"></i> Edit</button>
                            </form>

                            <form method="POST" action="{{ route('users.destroy', $user) }}" style="display:inline-block">
                                @method('delete')
                                @csrf
                                @if (auth()->user() != $user)
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure ?')"><i class="fa fa-trash"></i> Delete</button>
                                @else
                                    <div class="btn btn-danger btn-sm disabled"><i class="fa fa-trash"></i> Delete </div>
                                @endif
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
@section('script')
<!-- Page level plugins -->
<script src="{{ asset('theme/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('theme/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#books-table').DataTable({
            // "language": {
                // "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Arabic.json"
            // }
        });
    });
</script>
@endsection
