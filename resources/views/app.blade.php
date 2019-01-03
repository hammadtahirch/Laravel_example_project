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
        <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAb5QA9bGFyk-PP0wo3f7V9OYNqvr-EQVc&libraries=places"></script>


    </head>
    <body>
        <div id="root"></div>

    </body>

    <script src="{{secure_asset('js/jquery/jquery-2.2.4.min.js')}}"></script>
    <script src="{{secure_asset('js/plugins.js')}}"></script>
    <!-- Classy Nav js -->
    <script src="{{secure_asset('js/classy-nav.min.js')}}"></script>
    <!-- Active js -->
    <script src="{{secure_asset('js/active.js')}}"></script>
    <script src="{{secure_asset('app/js/app.js')}}?version=1" ></script>
</html>