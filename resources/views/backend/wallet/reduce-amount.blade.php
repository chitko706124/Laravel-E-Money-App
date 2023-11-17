@extends('backend.layouts.app')
@section('title')
    Reduce Amount
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
            Reduce Amount
        </h3>
    </div>



    <div class=" content">
        <div class=" card border-0 shadow">
            <div class=" card-body">
                <form action="{{ route('waller.reduceAmountStore') }}" method="POST">
                    @csrf

                    <div class=" form-group">
                        <label for="" class=" form-label">User</label>
                        <select name="user_id" class=" form-control" id="select2">
                            <option value="">--Please Choose --</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" @if (old('user_id') == $user->id) selected @endif>
                                    {{ $user->name }} ({{ $user->phone }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class=" form-group">
                        <label for="" class=" form-label">Amount</label>
                        <input type="number" name="amount" class=" form-control  @error('amount') is-invalid @enderror"
                            value="{{ old('amount') }}">

                        @error('amount')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class=" form-group">
                        <label for="" class=" form-label">Description</label>
                        <textarea name="description" id="" class=" form-control"></textarea>
                    </div>

                    <div class=" d-flex justify-content-center mt-3">
                        <button class=" btn btn-secondary back-btn">Cancle</button>
                        <button type="submit" class=" btn btn-primary ms-2">Confirm</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    {{-- {{ $dataTable->scripts() }} --}}
    <script type="text/javascript">
        $(document).ready(function() {
            $('#select2').select2({
                placeholder: "-- Please Choose --",
                allowClear: true,
                theme: 'bootstrap-5'
            });



        })
    </script>
@endsection
