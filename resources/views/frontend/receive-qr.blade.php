@extends('frontend.layouts.app')

@section('title')
    QR
@endsection
@section('content')
    <div class="receive_qr">
        <div class=" card">
            <div class=" card-body">
                <p class=" text-center mb-0">QR scan to pay me</p>
                <div class=" text-center m-4">
                    <img src="data:image/png;base64,
                {!! base64_encode(
                    QrCode::format('png')->size(200)->generate($authUser->phone),
                ) !!} ">
                </div>

                <p class=" text-center mb-1"><strong>{{ $authUser->name }}</strong></p>
                <p class=" text-center mb-1">{{ $authUser->phone }}</p>
            </div>
        </div>

    </div>
@endsection
