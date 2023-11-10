@extends('frontend.layouts.app')

@section('title')
    Transfer Confirmation
@endsection
@section('content')
    <div class=" transfer">
        <div class=" card">
            <div class=" card-body ">
                @include('frontend.layouts.flash')
                <form id="myForm" action="{{ route('transfer.complete') }}" method="POST">
                    @csrf
                    <input type="hidden" name="hashData" value="{{ $hashData }}">
                    <input type="hidden" name="phone" value="{{ $to_user->phone }}">
                    <input type="hidden" name="description" value="{{ $description }}">
                    <input type="hidden" name="amount" id="" value="{{ $amount }}">


                    <div class=" form-group mb-4">
                        <label class=" form-label mb-0"><strong>From</strong></label>
                        <p class=" mb-0 text-muted">
                            {{ $authUser->name }}
                        </p>
                        <p class=" mb-0 text-muted">
                            {{ $authUser->phone }}
                        </p>
                    </div>

                    <div class=" form-group mb-3">
                        <label class=" form-label mb-0"><strong>To</strong></label>
                        <p class=" mb-0 text-muted">
                            {{ $to_user->name }}
                        </p>
                        <p class=" mb-0 text-muted">
                            {{ $to_user->phone }}
                        </p>
                    </div>

                    <div class=" form-group mb-3">
                        <label class=" form-label mb-0"><strong>Amount (MMK)</strong></label>
                        <p class=" text-muted mb-0">{{ number_format($amount) }}</p>
                    </div>


                    <div class=" form-group mb-3">
                        <label class=" form-label mb-0"><strong>Description</strong></label>
                        <p class=" text-muted mb-0">{{ $description }}</p>
                    </div>

                    <div class=" d-grid mt-5">
                        <button class="btn btn-violet submitForm">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@section('js')
    <script>
        $(document).ready(function() {
            $('.submitForm').on('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Please fill your password!',
                    icon: 'info',
                    html: '<input type="password" name="" class=" text-center password form-control">',
                    // showCloseButton: true,
                    showCancelButton: true,
                    focusConfirm: false,
                    confirmButtonText: 'Confirm',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        var password = $('.password').val();
                        $.ajax({
                            url: '/password-check?password=' + password,
                            type: 'GET',
                            success: function(res) {
                                if (res.status == 'success') {
                                    $("#myForm").submit();
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Oops...',
                                        text: res.message,
                                    })
                                }
                            }
                        });
                    }
                })


            })
        });
    </script>
@endsection
