<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name') }}</title>

        <!-- favicon -->
        <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}">

        <!-- stylesheets -->
        <link rel="stylesheet" href="{{ asset('assets/css/dashlite.css?ver=3.2.3') }}">
        <link id="skin-default" rel="stylesheet" href="{{ asset('assets/css/theme.css?ver=3.2.3') }}">

        <!-- scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="nk-body bg-white npc-default has-aside">
        @inertia

        <!-- javascript -->
        <script src="{{ asset('assets/js/bundle.js?ver=3.2.3') }}"></script>
        <script src="{{ asset('assets/js/scripts.js?ver=3.2.3') }}"></script>
        <script src="{{ asset('assets/js/libs/datatable-btns.js?ver=3.2.3') }}"></script>
    </body>
</html>
