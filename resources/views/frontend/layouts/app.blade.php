<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    {{-- Bootstrap --}}
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous"> --}}

    {{-- Date Range Picker --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />


    <!-- Scripts -->
    @vite(['resources/sass/app.scss'])

    <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">

    @yield('style')

</head>

<body>
    <div id="app">

        <div class=" header-menu">
            <div class=" row justify-content-center">
                <div class=" col-md-8">
                    <div class="row ">

                        <div class="col-2 text-lg-center text-start">
                            @if (!request()->is('/'))
                                <a href="#" class=" back-btn d-block"><i class=" fas fa-angle-left"></i></a>
                            @endif
                        </div>

                        <div class="col-8 text-center">
                            <div>
                                <h5 class=" header-title">@yield('title')</h5>
                            </div>
                        </div>

                        <div class="col-2 text-center text-end ">
                            <a href="{{ route('notification.index') }}">
                                <i class=" fas fa-bell position-relative">
                                    @if ($unread_noti_count !== 0)
                                        <span style="font-size: 10px"
                                            class=" badge bg-danger unread_noti_badge position-absolute">{{ $unread_noti_count }}</span>
                                    @endif
                                </i>
                            </a>
                        </div>

                    </div>

                </div>
            </div>

        </div>

        <div class=" content">
            <div class=" row justify-content-center">
                <div class=" col-md-8 ">
                    @yield('content')
                </div>

                {{-- QR Scan Modal --}}
                <div class="modal fade" id="scanModalBtn" tabindex="-1" aria-labelledby="scanModalBtnLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <video id="scannerBtn" width="100%" height="300px"></video>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class=" bottom-menu">
            {{-- QR Scan Btn --}}
            <button data-bs-toggle="modal" data-bs-target="#scanModalBtn"
                class=" scan-tab d-block d-flex justify-content-center align-items-center border-0">
                <div class=" inside d-flex justify-content-center align-items-center">
                    <i class=" fas fa-qrcode mt-0"></i>
                </div>
            </button>


            <div class=" row justify-content-center">
                <div class=" col-md-8">
                    <div class="row d-flex align-items-center">

                        <div class="col-3 text-center">
                            <a href="{{ route('home') }}" class=" d-block ">
                                <i class=" fas fa-home"></i>
                                <p>Home</p>
                            </a>
                        </div>

                        <div class="col-3 text-center">
                            <a href="{{ route('wallet') }}" class=" d-block">
                                <i class=" fas fa-wallet"></i>
                                <p>Wallet</p>
                            </a>
                        </div>

                        <div class="col-3 text-center">
                            <a href="{{ route('transaction') }}" class=" d-block">
                                <i class=" fas fa-exchange-alt"></i>
                                <p>Transtions</p>
                            </a>
                        </div>

                        <div class="col-3 text-center">
                            <a href="{{ route('profile') }}" class=" d-block">
                                <i class=" fas fa-user"></i>
                                <p>Profile</p>
                            </a>
                        </div>

                    </div>

                </div>
            </div>

        </div>
    </div>

    {{-- JQuery --}}
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    {{-- bootstrap --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>


    {{-- Sweet Alert 2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Date Range Picker --}}
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    {{-- Qr Scan --}}
    <script src="{{ asset('frontend/js/qr-scanner/qr-scanner.umd.min.js') }}"></script>




    <script>
        $(document).ready(function() {
            let token = document.head.querySelector('meta[name="csrf-token"]');
            if (token) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF_TOKEN': token.content,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    }
                })
            }

            // QR scan btn
            const modal = new bootstrap.Modal('#scanModalBtn');
            var videoElem = document.getElementById('scannerBtn')
            const qrScaner = new QrScanner(videoElem, function(result) {
                if (result) {
                    qrScaner.stop();
                    modal.hide();

                    var phone = result;
                    window.location.replace(`scan-pay-transfer?phone=${phone}`)
                }
            });

            $('#scanModalBtn').on('shown.bs.modal', function(event) {
                qrScaner.start();
            })

            $('#scanModalBtn').on('hidden.bs.modal', function(event) {
                qrScaner.stop();
            })
            // QR scan btn end


            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })

            @if (session('create'))
                Toast.fire({
                    icon: 'success',
                    title: "{{ session('create') }}"
                });
            @elseif (session('update'))
                Toast.fire({
                    icon: 'success',
                    title: "{{ session('update') }}"
                });
            @endif

            @error('fail')
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "{{ $message }}",
                })
            @enderror

            $('.back-btn').on('click', function(e) {
                e.preventDefault();
                window.history.go(-1);
                return false;
            })
        })
    </script>
    @yield('js')
</body>

</html>
