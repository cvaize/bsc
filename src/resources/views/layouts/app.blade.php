@php
  $config = [
      'appName' => config('app.name'),
      'locale' => $locale = app()->getLocale(),
      'locales' => config('app.locales'),
      'siteUrl'=>env('SITE_URL'),
      'csrf'=>csrf_token(),
  ];
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">

  <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
  <link rel="manifest" href="/favicon/site.webmanifest">
  <link rel="mask-icon" href="/favicon/safari-pinned-tab.svg" color="#000000">
  <link rel="shortcut icon" href="/favicon/favicon.ico">
  <meta name="msapplication-TileColor" content="#da532c">
  <meta name="msapplication-config" content="/favicon/browserconfig.xml">
  <meta name="theme-color" content="#ffffff">

  <meta name="csrf-token" content="{{ csrf_token() }}">
{{--  <link rel="stylesheet" href="{{ mix('/css/app.css') }}">--}}
  @stack('head')
</head>
<body class="{{ $bodyClass ?? null }}">

<div class="page-wrapper">

  <main>
    @yield('content')
  </main>

</div>


{{-- Global configuration object --}}
<script>window.config = @json($config);</script>

@stack('predScripts')

{{--<script src="{{ mix('/js/app.js') }}" async></script>--}}
@stack('scripts')

</body>
</html>
