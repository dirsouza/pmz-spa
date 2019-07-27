<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>PMZ - SPA</title>

    <!-- FontAwasome -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome/css/all.css') }}">
    <!-- VueMultiselect -->
    <link rel="stylesheet" href="{{ asset('plugins/vue-multiselect/vue-multiselect.min.css') }}">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <style>
        .multiselect__placeholder {
            margin-bottom: 0 !important;
            padding-top: 0 !important;
        }

        .multiselect__tag {
            margin-bottom: 0 !important;
        }
    </style>
</head>

<body>
    <div id="app">
        <app></app>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>