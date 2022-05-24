<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="Description" content="Put your description here.">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'My title') }}</title>

  <!-- Scripts -->
  <script src="{{ asset(mix('/js/manifest.js')) }}" defer></script>
  <script src="{{ asset(mix('/js/vendor.js')) }}" defer></script>
  <script src="{{ asset(mix('/js/app.js')) }}" defer></script>

  <!-- Fonts -->
  <!--
  <link rel="dns-prefetch" href="//fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet">
  -->

  <!-- Styles -->
  <link href="{{ asset(mix('/css/app.css')) }}" rel="stylesheet">

  <script>
    window.appname = "{{ config('app.name', 'My title') }}";
    window.baseurl = '{{ url("/") }}';
  </script>
</head>

<body>
<div id="app" />
</body>

</html>
