@extends('backend.layouts.app')
@section('title')
    Edit Admin User
@endsection

@section('admin-user-mana-active')
    text-white
    active
@endsection


@section('content')
    <div class=" mb-3">
        <h3 class=" fw-bolder">
            <button class=" btn btn-lg shadow"> <i class="bi bi-person-add"></i>
            </button>
            Edit Admin User
        </h3>
    </div>


    <div>
        <form style="min-width: 350px" action="{{ route('admin-user.update', $admin->id) }}" method="POST"
            class=" w-25 p-3 d-flex flex-column gap-2 shadow">
            @csrf
            @method('PUT')
            <div>
                <label for="" class=" form-label">Name</label>
                <input type="text" value="{{ old('name', $admin->name) }}"
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

            <div>
                <label for="" class=" form-label">Email</label>
                <input type="email"
                    class="form-control
                    @error('email')
                        is-invalid
                    @enderror"
                    name="email" value="{{ old('email', $admin->email) }}" >

                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div>
                <label for="" class=" form-label">Phone</label>
                <input type="text"
                    class="form-control
                @error('phone')
                    is-invalid
                @enderror"
                    name="phone" value="{{ old('phone', $admin->phone) }}">

                @error('phone')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div>
                <label for="" class=" form-label">Password</label>
                <input type="password" class="form-control " name="password">
            </div>

            <div class=" d-flex justify-content-center mt-3">
                <button class=" btn btn-secondary back-btn">Cancle</button>
                <button type="submit" class=" btn btn-primary ms-2">Confirm</button>
            </div>
        </form>
    </div>
@endsection

@section('js')
    {{-- {!! JsValidator::formRequest('App\Http\Requests\StoreAdminUser') !!} --}}
@endsection
