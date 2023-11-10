@extends('frontend.layouts.app')

@section('title')
    Transaction Detail
@endsection
@section('content')
    <div class=" transaction_detail">
        <div class=" card">
            <div class=" card-body position-relative">

                <p
                    class=" mb-0 me-2 mt-2 position-absolute top-0 end-0 badge {{ $transaction->type == 1 ? 'bg-success' : 'bg-danger' }}">
                    {{ $transaction->type == 1 ? 'Income' : 'Expense' }}
                </p>

                <div class=" text-center">
                    <img class=" mb-3 " style="width: 70px" src="{{ asset('img/checked.png') }}" alt="">

                    @if (session('transfer_success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('transfer_success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <p class=" mb-3 {{ $transaction->type == 1 ? 'text-success' : 'text-danger' }} ">
                        {{ number_format($transaction->amount, 2) }} MMK</p>
                </div>

                <div class=" d-flex justify-content-between">
                    <p class=" mb-0">TRX ID :</p>
                    <p class=" mb-0">{{ $transaction->trx_no }}</p>
                </div>
                <hr>

                <div class=" d-flex justify-content-between">
                    <p class=" mb-0">Reference Number :</p>
                    <p class=" mb-0">{{ $transaction->ref_no }}</p>
                </div>
                <hr>
                <div class=" d-flex justify-content-between">
                    <p class=" mb-0">Amount :</p>
                    <p class=" mb-0 ">{{ number_format($transaction->amount) }} MMK</p>
                </div>
                <hr>

                <div class=" d-flex justify-content-between">
                    <p class=" mb-0">{{ $transaction->type == 1 ? 'From' : 'To' }}</p>
                    <p class=" mb-0">{{ $transaction->source->name }}</p>
                </div>
                <hr>

                <div class=" d-flex justify-content-between">
                    <p class=" mb-0">Description</p>
                    <p class=" mb-0">{{ $transaction->description }}</p>
                </div>
                <hr>

                <div class=" d-flex justify-content-between">
                    <p class=" mb-0">Date and time</p>
                    <p class=" mb-0">{{ $transaction->created_at->format('Y-m-j') }}</p>
                </div>
            </div>

        </div>
    </div>
@endsection
