@extends('frontend.layouts.app')

@section('title')
    Friends
@endsection
@section('content')
    <div class="receive_qr">
        <div class=" card">
            <div class=" card-body">
                <div class=" mb-3">
                    <a href="{{ route('friend.add') }}" class=" shadow btn btn-success fw-bold"><i
                            class="bi bi-plus-circle-fill me-1"></i>Add
                        Friend</a>
                </div>

                <div class="infinite-scroll">
                    @foreach ($friends as $friend)
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center showFeature"
                                    data-friend-id="{{ $friend->id }}">
                                    <div class=" d-flex align-items-center">
                                        <img src="https://ui-avatars.com/api/?name={{ $friend->name }}&background=random"
                                            alt="" width="32" height="32" class="rounded-circle me-2">
                                        <p class="mb-0">{{ $friend->name }}</p>

                                    </div>
                                    <p class="mb-0">{{ $friend->phone }}</p>
                                </div>
                                <div id="show{{ $friend->id }}" style="display: none;">
                                    <button id="transfer{{ $friend->id }}" data-friend-phone="{{ $friend->phone }}"
                                        class=" btn btn-primary btn-sm mt-2">Transfer</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class=" d-none">
                        {{ $friends->links() }}
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection


@section('js')
    {{-- jscroll --}}
    <script src="{{ asset('frontend/js/jquery.jscroll.min.js') }}"></script>

    <script>
        // $('ul.pagination').hide();

        $(document).ready(function() {
            $('.infinite-scroll').jscroll({
                autoTrigger: true,
                loadingHtml: '  <div class=" text-center mt-3"><div style="color: #5842E3" class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>',
                padding: 0,
                nextSelector: '.pagination li.active + li a',
                contentSelector: 'div.infinite-scroll',
                callback: function() {
                    $('ul.pagination').remove();
                }
            });

            $('.showFeature').click(function() {
                var friendId = $(this).data('friend-id');

                // Close all other showFeature divs
                $('.showFeature').not(this).each(function() {
                    var otherFriendId = $(this).data('friend-id');
                    // $('#show' + otherFriendId).hide();
                    $('#show' + otherFriendId).slideUp(200);
                });



                $('#transfer' + friendId).click(function() {
                    var friendPhone = $(this).data('friend-phone');
                    console.log(friendPhone);

                    window.location.replace(`scan-pay-transfer?phone=${friendPhone}`)
                })



                // Toggle the visibility of the clicked showFeature div
                // $('#show' + friendId).toggle();
                $('#show' + friendId).slideToggle(200);
            });





        })
    </script>
@endsection
