<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title inertia>{{ config('app.name') }}</title>

    <!-- favicon -->
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}">

    <!-- stylesheets -->


    @vite('resources/css/app.css')
    <link rel="stylesheet" href="{{ asset('assets/css/dashlite.css?ver=3.2.3') }}">
    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" /> --}}
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/theme.css') }}">


    <!-- scripts -->
    @routes
    @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead

</head>

<body class="nk-body bg-white npc-default has-aside">
    @inertia

    <!-- javascript -->
    {{-- <script src="{{ asset('assets/js/bundle.js?ver=3.2.3') }}"></script> --}}
    {{-- <script src="{{ asset('assets/js/scripts.js') }}"></script> --}}
    {{-- <script src="{{ asset('assets/js/libs/datatable-btns.js?ver=3.2.3') }}"></script> --}}
</body>

</html>
