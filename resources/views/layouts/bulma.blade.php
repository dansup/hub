<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{env('APP_NAME')}}</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/css?family=Lato:200,300,400,700" rel='stylesheet' type='text/css'>
    <link href="/assets/css/bulma.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('subcss')
</head>
<body id="app-layout">
<header class="header">
  <div class="container">
    <!-- Left side -->
    <div class="header-left">
      <a class="header-item" href="{{ url('/') }}">
        {{ env('APP_NAME') }}
      </a>
      <a class="header-tab{{request()->is('/')?' is-active':null}}" href="/">
        Home
      </a>
      <a class="header-tab{{request()->is('about*')?' is-active':null}}" href="/about">
        About
      </a>
      <a class="header-tab{{request()->is('node*')?' is-active':null}}" href="/nodes/browse">
        Nodes
      </a>
      <a class="header-tab{{request()->is('service*')?' is-active':null}}" href="/service">
        Services
      </a>
    </div>

    <span class="header-toggle">
      <span></span>
      <span></span>
      <span></span>
    </span>

    <!-- Right side -->
    <div class="header-right header-menu">
      @if (Auth::check() == false)
      <span class="header-item">
        <a class="button" href="/login">Login</a>
      </span>
      <span class="header-item">
        <a class="button" href="/register">Register</a>
      </span>
      @else
      <span class="header-item">
        <a class="" href="/account">Account</a>
      </span>
      <span class="header-item">
        <a class="button" href="/auth/logout">Logout</a>
      </span>
      @endif
    </div>
  </div>
</header>

    @yield('content')
<footer class="footer">
  <div class="container">
    <div class="content is-centered">
      <p>
        <strong>Hub</strong> by <a href="https://github.com/dansup">Daniel Supernault</a> and contributors. The source code is licensed
        <a href="http://opensource.org/licenses/mit-license.php">MIT</a>.
      </p>
      <p>
        <a class="icon" href="https://github.com/dansup/hub">
          <i class="fa fa-github"></i>
        </a>
      </p>
    </div>
  </div>
</footer>
    <!-- JavaScripts -->
    <script src="/assets/js/jquery-2.2.0.min.js"></script>
    @yield('subjs')
</body>
</html>
