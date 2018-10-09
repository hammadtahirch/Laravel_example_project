<!-- welcome.blade.php -->

<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Laravel</title>
        <link href="{{secure_asset('app/css/app.css')}}" rel="stylesheet" type="text/css">
        <link rel="icon" href="img/core-img/favicon.ico">

    <!-- Core Style CSS -->
    <link rel="stylesheet" href="{{secure_asset('css/core-style.css')}}">
{{--    <link rel="stylesheet" href="{{secure_asset('css/style.css')}}">--}}
        <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAtbwG3Fsd-qb_h-MiGk1zQaOgZ_rM8Xrs&libraries=places"></script>
    </head>
    <body>
        <div id="root"></div>

        <script src="{{secure_asset('app/js/app.js')}}" ></script>

        <script src="{{secure_asset('js/jquery/jquery-2.2.4.min.js')}}"></script>
        <!-- Popper js -->
        {{-- <script src="{{secure_asset('js/popper.min.js')}}"></script> --}}
        <!-- Bootstrap js -->
        {{-- <script src="{{secure_asset('js/bootstrap.min.js')}}"></script> --}}
        <!-- Plugins js -->
        <script src="{{secure_asset('js/plugins.js')}}"></script>
        <!-- Classy Nav js -->
        <script src="{{secure_asset('js/classy-nav.min.js')}}"></script>
        <!-- Active js -->
        <script src="{{secure_asset('js/active.js')}}"></script>
    </body>
</html>