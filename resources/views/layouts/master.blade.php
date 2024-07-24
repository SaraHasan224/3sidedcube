<!doctype html>
<html lang="en">

<head>
    @include('layouts.common.meta_tags')

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Analytics Dashboard - This is an example dashboard created using build-in elements and components.</title>
    <meta name="viewport"
          content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no"
    />
    <meta name="description" content="This is an example dashboard created using build-in elements and components.">

    <!-- Disable tap highlight on IE -->
    <meta name="msapplication-tap-highlight" content="no">

    <link href="{{ mix('css/main.css') }}" rel="stylesheet">
    <link href="{{ mix('css/init.css') }}" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.0.0/sweetalert.min.js"></script>
    <script src="{{ mix('js/admin.js') }}"></script>
{{--    <script src="{{ mix('js/main.js') }}"></script>--}}
    <style>
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
    @yield('css')
</head>
<body>
<div class="loading">
    <div class="spinner"></div>
</div>

<div class="app-container app-theme-white body-tabs-shadow @hasSection('auth_layout') @else fixed-header fixed-sidebar @endif">
    @hasSection('auth_layout')
        @yield('content')
    @else
        @include('layouts.common.header_navigation')
        <div class="app-main">
            @include('layouts.common.sidebar_navigation')
            <div class="app-main__outer">
                <div class="app-main__inner">
                    @include('layouts.common.breadcrumb')
                    @include('layouts.common.alert')
                    @yield('content')
                </div>
                @include('layouts.common.footer')
            </div>
        </div>
    @endif
</div>
@include('layouts.common.config_mapping')
@yield('scripts')
{{--<script type="text/javascript" src="./assets/scripts/main.87c0748b313a1dda75f5.js"></script>--}}
</body>
</html>
