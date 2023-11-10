@extends('frontend.layouts.app_plain')

@section('title')
    Register
@endsection

@section('content')
    <div class="container">
        <div style="height: 100vh" class="row justify-content-center align-items-center">
            <div class="col-md-6">
                <div class="card auth-form">
                    <div class="card-body">


                        <div class=" mt-3">
                            <h3 class=" text-center fw-bold mb-1">Register</h3>
                            <p class=" text-center text-muted">Fill the form to register</p>
                        </div>

                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class=" form-group mb-3">
                                <label for="" class=" form-label">Name</label>
                                <input type="name" value="{{ old('name') }}"
                                    class="form-control
                                @error('name')
                                    is-invalid
                                @enderror"
                                    name="name">

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class=" form-group mb-3">
                                <label for="" class=" form-label">Email</label>
                                <input type="email" value="{{ old('email') }}"
                                    class="form-control
                                @error('email')
                                    is-invalid
                                @enderror"
                                    name="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class=" form-group mb-3">
                                <label for="" class=" form-label">Phone</label>
                                <input type="phone" value="{{ old('phone') }}"
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


                            <div class=" form-group mb-3">
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

                            <div class=" form-group mb-5">
                                <label for="" class=" form-label">Confirm Password</label>
                                <input type="password"
                                    class="form-control
                                    @error('password_confirmation')
                                        is-invalid
                                    @enderror"
                                    name="password_confirmation" value="{{ old('password_confirmation') }}">

                                @error('password_confirmation')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="d-grid gap-2 mb-3">
                                <button class="btn btn-violet" type="submit">Register</button>
                            </div>

                            <div class=" mt-4 mb-3">
                                <a href="{{ route('login') }}">Already have an account?</a>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
