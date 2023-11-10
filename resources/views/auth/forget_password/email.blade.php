@extends('frontend.layouts.app_plain')

@section('title')
    Forget Password
@endsection

@section('content')
    <div class="container">
        <div style="height: 100vh" class="row justify-content-center align-items-center">
            <div class="col-md-6">
                <div class="card auth-form">
                    <div class="card-body">

                        <div class=" mt-3">
                            <h3 class=" text-center fw-bold mb-1"> Forget Password </h3>
                            {{-- <p class=" text-center text-muted">Enter your email</p> --}}
                        </div>

                        <form method="POST" action="{{ route('otp.page') }}">
                            @csrf

                            <div class=" form-group mb-3">
                                <label for="" class=" form-label">Enter Your Email</label>
                                <input type="email"
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

                            <div class=" mt-5 d-flex justify-content-between align-items-center">
                                <a href="{{ route('send.email') }}">Back to login</a>

                                <button style="padding: 7px 20px !important" class="btn btn-sm btn-violet "
                                    type="submit">Send code</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
