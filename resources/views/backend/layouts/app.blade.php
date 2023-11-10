<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    {{-- Data table --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">


</head>

<body>
    <div class="container-fluid px-0">

        {{-- header --}}
        @include('backend.layouts.header')

        {{-- header end --}}

        <div class="row flex-nowrap ">

            {{-- sidebar --}}
            @include('backend.layouts.sidebar')
            {{-- sidebar end --}}


            <main class="col ps-4 pt-4 ">

                {{-- content --}}
                @yield('content')
                {{-- content end --}}

            </main>


            {{-- @include('backend.layouts.footer') --}}

        </div>
    </div>

    {{-- Jquery --}}
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>


    {{-- Sweet Alert 2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    {{-- data table --}}
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            let token = document.head.querySelector('meta[name="csrf-token"]');
            if (token) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF_TOKEN': token.content
                    }
                })
            }
            $('.back-btn').on('click', function() {
                window.history.go(-1);
                return false;
            })

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




        });
    </script>

    {{-- <script src="{{ asset('vendor/jsvalidation/js/jsvalidation.js') }}"></script> --}}

    @yield('scripts')
</body>

</html>
