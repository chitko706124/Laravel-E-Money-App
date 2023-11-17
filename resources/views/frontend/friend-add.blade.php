@extends('frontend.layouts.app')

@section('title')
    Friend Add
@endsection
@section('content')
    <div class="receive_qr">
        <div class=" card">
            <div class=" card-body">
                <div class="input-group mb-3">
                    <input id="phone" type="text" name="phone" placeholder="Search with phone"
                        class=" form-control to_phone
                    @error('phone') is-invalid @enderror">
                    <span class="input-group-text btn border  verify-btn">search</span>

                    @error('phone')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div>
                    <div id="show" style="display: none;">
                        <div class=" d-flex  justify-content-between ">
                            <div class=" d-flex align-items-center">
                                <img src="" class="to_account_info_img rounded-circle me-2" alt=""
                                    width="32" height="32" class="rounded-circle me-2">
                                <p class=" mb-0 to_account_info"></p>
                                <p class=" mb-0 to_account_info_phone"></p>
                            </div>
                            <button class="  border"><i class="bi bi-plus-circle-fill addFriend "></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- <div>
                    <p class=" mb-0 to_account_info_error text-center text-danger"></p>

                </div> --}}
            </div>
        </div>

    </div>
@endsection



@section('js')
    <script>
        // $('.makeHide').hide();

        $(document).ready(function() {
            $('.verify-btn').on('click', function() {
                var phone = $('.to_phone').val();
                $.ajax({
                    url: '/to-account-verify?phone=' + phone,
                    type: 'GET',
                    success: function(res) {
                        if (res.status == 'success') {
                            $('.to_account_info_img')
                                .attr('src', 'https://ui-avatars.com/api/?name=' + res.data
                                    .name);
                            $('.to_account_info').text("" + res.data.name + "");
                            $('.to_account_info_phone').text(" (" + res.data.phone + ")");
                            $('#show').show();

                            $('.addFriend').on('click', function() {
                                $.ajax({
                                    url: '/friend/add/store?name=' + res.data
                                        .name + '&phone=' + res.data.phone +
                                        '',
                                    type: 'GET',

                                    success: function(res) {
                                        if (res.status == 'success') {
                                            window.location.replace(
                                                "{{ route('friend') }}")
                                        } else {
                                            Swal.fire({
                                                icon: 'warning',
                                                title: 'Find another',
                                                text: res.message,
                                                confirmButtonText: "OK"
                                            }).then((result) => {
                                                /* Read more about isConfirmed, isDenied below */
                                                if (result
                                                    .isConfirmed) {
                                                    window.location
                                                        .reload();
                                                }
                                            })
                                        }
                                    }

                                })
                            })


                        } else {
                            // $('.to_account_info_error').text("" + res.message + "");
                            Swal.fire({
                                icon: 'warning',
                                title: 'Find another',
                                text: res.message,
                            })
                        }
                    }
                });
            })
        })
    </script>
@endsection
