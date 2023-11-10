@extends('frontend.layouts.app')

@section('title')
    Transfer
@endsection
@section('content')
    <div class=" transfer">
        <div class=" card">
            <div class=" card-body ">
                @include('frontend.layouts.flash')
                <form id="transferForm" action="{{ url('transfer/confirm') }}">

                    <input id="hashData" type="hidden" name="hashData" value="">

                    <div class=" form-group mb-3">
                        <label class=" form-label">From</label>
                        <p class=" mb-0 text-muted">
                            {{ $user->name }}
                        </p>
                        <p class=" mb-0 text-muted">
                            {{ $user->phone }}
                        </p>
                    </div>

                    @if (request()->url() === route('scan.pay.transfer'))
                        <input type="hidden" id="phoneHidden" name="phone" value="{{ old('phone', $toUser->phone) }}">
                    @endif

                    <div class=" form-group">
                        <label class=" form-label ">To <span
                                class=" to_account_info text-success">{{ request()->url() === route('scan.pay.transfer') ? $toUser->name : '' }}</span></label>
                        <div class="input-group mb-3">
                            <input @if (request()->url() === route('scan.pay.transfer')) disabled @endif id="phone" type="text"
                                name="phone" value="{{ old('phone', request()->phone) }}"
                                class=" form-control to_phone
                            @error('phone') is-invalid @enderror">
                            <span class="input-group-text btn border @error('phone') border-red-500 @enderror verify-btn"><i
                                    class=" fas fa-check-circle "></i></span>

                            @error('phone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>


                    <div class=" form-group mb-3">
                        <label class=" form-label">Amount (MMK)</label>
                        <input id="amount" type="number" name="amount" value="{{ old('amount') }}"
                            class=" form-control
                        @error('amount') is-invalid @enderror">

                        @error('amount')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>


                    <div class=" form-group mb-3">
                        <label class=" form-label">Description</label>
                        <textarea id="description" class=" form-control" name="description">{{ old('description') }}</textarea>
                    </div>

                    <div class=" d-grid mt-4">
                        <button class="btn btn-violet submit-btn" type="submit">Continue</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {

            $('.verify-btn').on('click', function() {
                var phone = $('.to_phone').val();
                $.ajax({
                    url: '/to-account-verify?phone=' + phone,
                    type: 'GET',
                    success: function(res) {
                        if (res.status == 'success') {
                            $('.to_account_info').text(" (" + res.data.name + ")")
                        } else {
                            $('.to_account_info').text(" (" + res.message + ")")
                        }
                    }
                });
            })

            $('.submit-btn').click(function(e) {
                e.preventDefault()

                @if (request()->url() === route('scan.pay.transfer'))
                    var phone = $("#phoneHidden").val();
                @else
                    var phone = $('#phone').val();
                @endif

                var amount = $('#amount').val();
                var description = $('#description').val();

                $.ajax({
                    url: `/transfer-hash?phone=${phone}&amount=${amount}&description=${description}`,
                    type: 'GET',
                    success: function(res) {
                        if (res.status == 'success') {
                            $('#hashData').val(res.data);
                            $('#transferForm').submit();
                        }
                    }
                });
            })

        });
        // @if ($errors->has('fail'))
        //     Swal.fire({
        //         icon: 'error',
        //         title: 'Oops...',
        //         text: "{{ $errors->first('fail') }}"
        //     });
        // @endif
    </script>
@endsection
