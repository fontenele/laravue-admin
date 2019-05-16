<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="manifest" href="/mix-manifest.json">
    <title>{{ config('app.name') }}</title>
    <link href='https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900|Material+Icons' rel="stylesheet">
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
</head>
<body>
<div id="app">
    <v-app id="inspire">
        <app :menus-left="{{ json_encode($menusLeft) }}" :menus-right="{{ json_encode($menusRight) }}"></app>
    </v-app>
</div>
<script src="{{ url (mix('/js/app.js')) }}" type="text/javascript"></script>
</body>
</html>
