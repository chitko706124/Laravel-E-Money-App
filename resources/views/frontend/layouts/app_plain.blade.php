<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">
    @yield('style')
</head>

<body>
    @yield('content')

    {{-- jquery --}}
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    {{-- sweet alert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @yield('js')
    <script>
        $(document).ready(function() {
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


            // Toast.fire({
            //     icon: 'success',
            //     title: "Ha Ha"
            // });

            @if (session('create'))
                console.log('create');
            @endif

            @if (session('OTPcode'))
                console.log("leepal")
                Toast.fire({
                    icon: 'success',
                    title: "Ha Ha"
                });
            @elseif (session('update'))
                Toast.fire({
                    icon: 'success',
                    title: "{{ session('update') }}"
                });
            @endif
        });
    </script>
</body>

</html>
