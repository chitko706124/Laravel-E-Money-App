@extends('backend.layouts.app')
@section('title')
    Wallet
@endsection

@section('wallet-active')
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
        <a href="{{ route('wallet.addAmount') }}" class=" shadow btn btn-success fw-bold"><i
                class="bi bi-plus-circle-fill me-1"></i>Add
            Amount</a>

        <a href="{{ route('wallet.reduceAmount') }}" class=" shadow btn btn-danger fw-bold"><i
                class="bi bi-dash-circle-fill me-1"></i>Reduce
            Amount</a>
    </div>

    <div class=" content">
        <div class=" card border-0 shadow">
            <div class=" card-body">
                <table id="example" class=" table table-bordered">
                    <thead>
                        <tr>
                            <th>Account Number</th>
                            <th>Account Person</th>
                            <th>Amount</th>
                            <th>Created At</th>
                            <th>Updated At</th>
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
                    url: "{{ route('wallet.index') }}"
                },
                columns: [{
                        data: 'account_number',
                        name: 'account_number',
                    },
                    {
                        data: 'account_person',
                        name: 'account_person'
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                    },
                    {
                        data: 'updated_at',
                        name: 'updated_at',
                    },

                ],
                order: [
                    [4, 'desc']
                ],
                columnDefs: [{
                    targets: "no-sort",
                    sortable: false,

                }]
            });



        })
    </script>
@endsection
