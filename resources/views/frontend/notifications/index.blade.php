@extends('frontend.layouts.app')

@section('title')
    Notifications
@endsection

@section('content')
    <div>
        <div class="infinite-scroll">
            @foreach ($notifications as $notification)
                <a href="{{ route('notification.show', $notification->id) }}">
                    <div class=" card mb-2 ">
                        <div class=" card-body">
                            <h6>
                                <i class=" fas fa-bell @if (is_null($notification->read_at)) text-danger @endif"></i>
                                <strong>{{ Str::limit($notification->data['title'], 40) }}</strong>
                            </h6>
                            <p class=" mb-1">{{ Str::limit($notification->data['message'], 100) }}</p>
                            <p class=" mb-1 text-end text-muted ">
                                <small>
                                    {{ $notification->created_at->diffForHumans() }}
                                </small>
                            </p>
                        </div>
                    </div>
                </a>
            @endforeach
            <div class=" d-none">
                {{ $notifications->links() }}
            </div>
        </div>
    </div>
@endsection


@section('js')
    {{-- jscroll --}}
    <script src="{{ asset('frontend/js/jquery.jscroll.min.js') }}"></script>

    <script type="text/javascript">
        // $('ul.pagination').hide();

        $(function() {
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


            // $('.date').daterangepicker({
            //     "singleDatePicker": true,
            //     "autoApply": true,
            //     "maxDate": formattedDate,
            //     "locale": {
            //         "format": "YYYY-MM-DD",
            //     },
            // });

            // $('.date,.type').on('change', function() {
            //     var urlParams = new URLSearchParams(window.location.search);
            //     var dateParam = urlParams.get('date');

            //     var date = $('.date').val() == formattedDate && dateParam == null ? '' : $('.date')
            //         .val();
            //     var type = $('.type').val();
            //     var queryParams = {};

            //     if (date !== null && date !== '') {
            //         queryParams.date = date;
            //     }

            //     if (type !== null && type !== '') {
            //         queryParams.type = type;
            //     }

            //     var queryString = $.param(queryParams);

            //     history.pushState(null, '', '?' + queryString);
            //     window.location.reload();
            // });

            // $('#reset').click(function() {
            //     $.ajax({
            //         url: "{{ route('transaction') }}",
            //         type: 'GET',
            //         success: function(data) {
            //             // This code will execute when the AJAX request is successful.
            //             // You can add any additional logic here if needed.

            //             // Reload the page
            //             history.pushState(null, '', "{{ route('transaction') }}");

            //             window.location.reload();
            //         },
            //     })
            // })


        });
    </script>
@endsection
