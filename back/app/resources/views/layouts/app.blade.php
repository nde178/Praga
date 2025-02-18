<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Panel')</title>
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <style>
        .layout-expanded .content-wrapper {
            margin-left: 0 !important;
            width: 100% !important; /* Контент занимает всю ширину */
        }

        .layout-expanded .main-footer {
            margin-left: 0 !important; /* Чтобы футер тоже не имел отступа */
        }
    </style>
</head>
<body class="hold-transition layout-fixed @if (!Request::is('/')) sidebar-mini @else layout-expanded @endif">

<div class="wrapper">
    @if (!Request::is('/') )
        @include('layouts.navbar')
        @include('layouts.sidebar')
    @endif
        @if (Request::is('/') && Route::has('login'))
            <div class="card mt-3">
                <div class="card-body text-right">
                    @auth
                        <h5 class="card-title">Welcome!</h5>
                        <a href="{{ url('/home') }}" class="btn btn-primary">
                            Панель управления
                        </a>
                    @else
                        <h5 class="card-title">Welcome, Guest!</h5>
                        <a href="{{ route('login') }}" class="btn btn-primary">
                            Login
                        </a>
                    @endauth
                </div>
            </div>
        @endif

    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </section>
    </div>

    @include('layouts.footer')
</div>


<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('dist/js/adminlte.js') }}"></script>
<script>
    $(document).ready(function () {
        $('.nav-item.has-treeview > a').on('click', function (e) {
            e.preventDefault();
            let $parent = $(this).parent();
            $parent.toggleClass('menu-open');
            $parent.find('.nav-treeview').slideToggle();
        });
    });
</script>

</body>
</html>

