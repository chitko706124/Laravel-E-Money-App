@extends('frontend.layouts.app')

@section('title')
    Notifications Detail
@endsection

@section('content')
    <div>
        <div class=" card">
            <div class=" card-body text-center">
                <div class=" text-center">
                    <img width="220px" src="{{ asset('img/notifications.png') }}" alt="">
                </div>
                <h5 class=" fw-bold">{{ $notification->data['title'] }}</h5>
                <p class=" mb-1">{{ $notification->data['message'] }}</p>
                <div>
                    <small>{{ $notification->created_at->format('Y-m-d') }}</small>
                </div>
                <div class=" mb-3">
                    <small>{{ $notification->created_at->format('H:i A') }} </small>
                </div>
                <a href="{{ $notification->data['web_link'] }}" class=" btn btn-violet btn-sm">Continue</a>
            </div>

        </div>
    </div>
@endsection
