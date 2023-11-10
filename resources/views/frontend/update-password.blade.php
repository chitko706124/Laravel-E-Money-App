@extends('frontend.layouts.app')

@section('title')
    Update Password
@endsection
@section('content')
    <div class=" update-password">
        <div class=" card ">
            <div class=" card-body">
                <div class=" text-center">
                    <img width="220px" src="{{ asset('img/update-password.png') }}" alt="">

                </div>
                <form action="{{ route('update-password.store') }}" method="POST">
                    @csrf

                    <div class=" form-group mt-3">
                        <label for="" class=" form-label">Current Password</label>
                        <input type="password" name="current_password"
                            class=" form-control
                        @error('current_password') is-invalid @enderror">

                        @error('current_password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class=" form-group mt-3">
                        <label for="" class=" form-label">New Password</label>
                        <input type="password" name="password"
                            class=" form-control
                        @error('password') is-invalid @enderror">

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class=" form-group mt-3">
                        <label for="" class=" form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation"
                            class=" form-control
                        @error('password_confirmation') is-invalid @enderror">

                        @error('password_confirmation')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class=" d-grid mt-4">
                        <button class="btn btn-violet" type="submit">Update Password</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript">
        $(document).ready(function() {

        })
    </script>
@endsection
