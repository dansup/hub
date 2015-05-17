<!doctype html>
<html>
<head>
  <meta charset='utf-8'>
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width">
  @yield('extra_meta')
  <title>Hub Docs</title>
  @yield('extra_css')
  <script src="/assets/js/jquery.min.js"></script>
  <script src='/assets/js/legacy.js'></script>
  <script src='/assets/js/flatdoc.js'></script>
  <link  href='/assets/css/flatdoc-style.css' rel='stylesheet'>
  <script src='/assets/js/flatdoc-script.js'></script>

  <meta content="Your Project" property="og:title">
  <meta content="Your Project description goes here." name="description">
  @yield('extra_js')

</head>
<body class='big-h3' role='flatdoc'>

  <div class='header'>
    <div class='left'>
      <h1>Hub Docs</h1>
      <ul>
        <li><a href="/">Main Site</a></li>
        <li><a href="/docs">Docs Home</a></li>
    </ul>
    </div>
    <div class='right'>
    </div>
  </div>

  <div class='content-root'>
    <div class='menubar'>
      <div class='menu section' role='flatdoc-menu'></div>
    </div>
    <div role='flatdoc-content' class='content'></div>
  </div>

</body>
</html>