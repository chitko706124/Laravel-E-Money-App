@extends('frontend.layouts.app')

@section('title')
    Magic Pay
@endsection
@section('content')
    <div class="home">
        <div class=" row">
            <div class=" col-12">
                <div class=" profile text-center mb-3">
                    <img class=" mb-3"
                        src="https://ui-avatars.com/api/?background=5842E3&color=fff&name={{ $user->name }}">
                    <h6>{{ $user->name }}</h6>
                    <p class=" text-muted">{{ $user->wallet ? number_format($user->wallet->amount) : 0 }} MMK</p>
                </div>
            </div>

            <div class=" col-6 mb-3">
                <div class=" card">
                    <a href="{{ route('scan.pay') }}">
                        <div
                            class=" card-body d-flex align-items-center justify-content-around justify-content-md-start p-md-3 p-2">
                            <i class="bi bi-qr-code-scan me-md-3"></i>
                            <span>Scan & Pay</span>
                        </div>
                    </a>
                </div>
            </div>

            <div class=" col-6 mb-3">
                <div class=" card">
                    <a href="{{ route('receive.qr') }}">
                        <div
                            class=" card-body d-flex align-items-center justify-content-around justify-content-md-start p-md-3 p-2">
                            <i class="bi bi-qr-code me-md-3"></i>
                            <span>Receive QR</span>
                        </div>
                    </a>
                </div>
            </div>

            <div class=" col-12">
                <div class=" card function-box">
                    <div class=" card-body pe-0">
                        <a href="{{ route('transfer') }}" class=" d-flex justify-content-between">
                            <span><img src="{{ asset('img/transfer-money.png') }}" alt="">Transfer</span>
                            <span class=" me-3"><i class=" fas fa-angle-right"></i></span>
                        </a>

                        <hr>

                        <a href="#" class=" d-flex justify-content-between logout">
                            <span><img src="{{ asset('img/wallet.png') }}" alt="">Wallet</span>
                            <span class=" me-3"><i class=" fas fa-angle-right"></i></span>
                        </a>

                        <hr>

                        <a href="{{ route('transaction') }}" class=" d-flex justify-content-between logout">
                            <span><img src="{{ asset('img/transaction (1).png') }}" alt="">Transaction</span>
                            <span class=" me-3"><i class=" fas fa-angle-right"></i></span>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
