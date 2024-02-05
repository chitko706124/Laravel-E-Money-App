@extends('frontend.layouts.app_plain')

@section('title')
    Login
@endsection

@section('content')
    <div class="container">
        <div style="height: 100vh" class="row justify-content-center align-items-center">
            <div class="col-md-6">
                <div class="card auth-form">
                    <div class="card-body">

                        <div class=" mt-3">
                            <h3 class=" text-center fw-bold mb-1">Login</h3>
                            <p class=" text-center text-muted">Fill the form to login</p>
                        </div>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class=" form-group mb-3">
                                <label for="" class=" form-label">Phone or Email</label>
                                <input type="text"
                                    class="form-control
                                @error('phone')
                                    is-invalid
                                @enderror"
                                    name="phone">

                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>


                            <div class=" form-group mb-5">
                                <label for="" class=" form-label">Password</label>
                                <input type="password"
                                    class="form-control
                                    @error('password')
                                        is-invalid
                                    @enderror"
                                    name="password" value="{{ old('password') }}">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            {{-- <button class=" btn btn-primary btn-block w-full">Login</button> --}}
                            <div class="d-grid gap-2 mb-3">
                                <button class="btn btn-violet" type="submit">Login</button>
                            </div>

                            <div class=" d-flex justify-content-between mt-4 mb-3">
                                <a href="{{ route('register') }}" class="">Don't have an account? Register</a>
                                {{-- @if (Route::has('password.request'))
                                    <a href="#!" class="text-body">Forgot password?</a>
                                @endif --}}
                                <a href="#">Forget Password</a>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
