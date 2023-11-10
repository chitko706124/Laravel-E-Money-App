@extends('frontend.layouts.app')

@section('title')
    Transaction
@endsection


@section('content')
    <div class="transaction">
        <div class=" card mb-3">
            <div class=" card-body">
                <div class=" row">
                    <div class=" col-6">
                        <div class="input-group ">
                            <label class="input-group-text">Date</label>
                            <input type="text" class=" form-control date" value="{{ request()->date }}">
                        </div>
                    </div>
                    <div class=" col-6">
                        <div class="input-group ">
                            <label class="input-group-text" for="inputGroupSelect01">Type</label>
                            <select class="form-select type">
                                <option value="">All</option>
                                <option value="1" @if (request()->type == 1) selected @endif>Income</option>
                                <option value="2" @if (request()->type == 2) selected @endif>Expense</option>
                            </select>
                        </div>
                    </div>
                    <div class=" col-12 {{ request()->date !== null || request()->type ? '' : 'd-none' }}">
                        <div class="d-grid mt-3">
                            <button id="reset" class="btn btn-violet  ">Reset</button>
                        </div>
                    </div>

                </div>

            </div>

        </div>
        <div class="infinite-scroll">
            @foreach ($transactions as $transaction)
                <a href="{{ route('transaction.detail', $transaction->trx_no) }}">
                    <div class=" card mb-2">
                        <div class=" card-body">
                            <div class=" d-flex justify-content-between">
                                <h6>TRX NO:{{ $transaction->trx_no }}</h6>
                                <p class=" mb-1 {{ $transaction->type == 1 ? 'text-success' : 'text-danger' }}">
                                    {{ $transaction->amount }} <small>MMK</small></p>
                            </div>
                            <p class=" mb-1 text-muted">
                                @if ($transaction->type == 1)
                                    From
                                @elseif ($transaction->type == 2)
                                    To
                                @endif
                                {{ $transaction->source ? $transaction->source->name : '-' }}
                            </p>
                            <small class=" text-end d-block mb-1">
                                @if (request()->date !== null)
                                    {{ $transaction->created_at->format('Y-m-d') }}
                                @else
                                    {{ $transaction->created_at->diffForHumans() }}
                                @endif
                            </small>
                        </div>

                    </div>
                </a>
            @endforeach
            <div class=" d-none">
                {{ $transactions->links() }}
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

            var formattedDate = "{{ $formattedDate }}";

            $('.date').daterangepicker({
                "singleDatePicker": true,
                "autoApply": true,
                "maxDate": formattedDate,
                "locale": {
                    "format": "YYYY-MM-DD",
                },
            });

            $('.date,.type').on('change', function() {
                var urlParams = new URLSearchParams(window.location.search);
                var dateParam = urlParams.get('date');

                var date = $('.date').val() == formattedDate && dateParam == null ? '' : $('.date')
                    .val();
                var type = $('.type').val();
                var queryParams = {};

                if (date !== null && date !== '') {
                    queryParams.date = date;
                }

                if (type !== null && type !== '') {
                    queryParams.type = type;
                }

                var queryString = $.param(queryParams);

                history.pushState(null, '', '?' + queryString);
                window.location.reload();
            });

            $('#reset').click(function() {
                $.ajax({
                    url: "{{ route('transaction') }}",
                    type: 'GET',
                    success: function(data) {
                        // This code will execute when the AJAX request is successful.
                        // You can add any additional logic here if needed.

                        // Reload the page
                        history.pushState(null, '', "{{ route('transaction') }}");

                        window.location.reload();
                    },
                })
            })

            // $('.date').change(function() {
            //     var date = $('.date').val();
            //     var type = $('.type').val();

            //     if (date === null || date === '') {
            //         history.pushState(null, '', window.location.pathname);
            //     } else if (type === null || type === '') {
            //         history.pushState(null, '', `?date=${date}`);
            //     } else {
            //         history.pushState(null, '', `?date=${date}&type=${type}`);
            //     }

            //     window.location.reload();
            // });

            // $('.type').change(function() {
            //     var date = $('.date').val();
            //     var type = $('.type').val();

            //     $('.date').change(function() {

            //     })

            //     if (type === null || type === '') {
            //         history.pushState(null, '', window.location.pathname);
            //     } else {
            //         history.pushState(null, '', `?date=${date}&type=${type}`);
            //     }

            //     window.location.reload();
            // });
        });
    </script>
@endsection
