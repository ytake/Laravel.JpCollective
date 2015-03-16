<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no"/>
    <title>Laravel Collective</title>
    <meta name="keywords" content="laravel collective,laravel,laravel php,php,framework,laravel 日本語"/>
    <meta name="description" content="We maintain Laravel components that have been removed from the core framework, so you can continue to use the amazing Laravel features that you love."/>

    <link href="/css/app.css" type="text/css" rel="stylesheet" media="screen,projection"/>
    @yield('styles')
  </head>
  <body>
    @include('partials.nav')
    @yield('content')
    <script src="/js/app.js"></script>
    @yield('scripts')
  </body>
</html>
