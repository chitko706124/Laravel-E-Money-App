@extends('backend.layouts.app')
@section('title')
    User Management
@endsection

@section('user-mana-active')
    text-white
    active
@endsection


@section('content')
    <div class=" mb-3">
        <h3 class=" fw-bolder">
            <button class=" btn btn-lg shadow"> <i class="bi bi-people"></i>
            </button>
            User Management
        </h3>
    </div>

    <div class=" mb-3">
        <a href="{{ route('user.create') }}" class=" shadow btn btn-primary fw-bold"><i
                class="bi bi-plus-circle-fill me-1"></i>Create
            User</a>
    </div>

    <div class=" content">
        <div class=" card border-0 shadow">
            <div class=" card-body">
                <table id="example" class=" table table-bordered">
                    <thead>
                        <tr>
                            <th>id</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th class=" no-sort">IP</th>
                            <th class=" no-sort">User Agent</th>
                            <th>Login At</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th class=" no-sort">Action</th>

                        </tr>
                    </thead>

                    <tbody></tbody>

                </table>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    {{-- {{ $dataTable->scripts() }} --}}
    <script type="text/javascript">
        $(document).ready(function() {
            var table = $('#example').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('user.index') }}"
                },
                columns: [{
                        data: 'id',
                        name: 'id',
                    },
                    {
                        data: 'name',
                        name: 'name',
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'ip',
                        name: 'ip',
                    },
                    {
                        data: 'user_agent',
                        name: 'user_agent',
                    },
                    {
                        data: 'login_at',
                        name: 'login_at',
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                    },
                    {
                        data: 'updated_at',
                        name: 'updated_at',
                    },
                    {
                        data: 'action',
                        name: 'action',
                    },
                ],
                order: [
                    [7, 'desc']
                ],
                columnDefs: [{
                    targets: "no-sort",
                    sortable: false,

                }]
            });

            //delete
            $(document).on('click', '.delete', function(e) {
                e.preventDefault();
                var id = $(this).data('id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            // url: "{{ route('admin-user.destroy', ':id') }}".replace(':id',
                            //     id),
                            url: '/admin/user/' + id,
                            type: 'DELETE',
                            success: function() {
                                table.ajax.reload();
                                const Toast = Swal.mixin({
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                    didOpen: (toast) => {
                                        toast.addEventListener('mouseenter',
                                            Swal.stopTimer)
                                        toast.addEventListener('mouseleave',
                                            Swal.resumeTimer)
                                    }
                                })
                                Toast.fire({
                                    icon: 'success',
                                    title: "Successfully Deleted"
                                });
                            }
                        })
                    }
                })
            })

        })
    </script>
@endsection
