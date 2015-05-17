<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	@yield('extra_meta')
	<title>Hub</title>
	<link href="/assets/css/fonts.css" rel="stylesheet">
	<link href="/assets/css/font-awesome.css" rel="stylesheet">
	<link href="/assets/css/bootstrap.css" rel="stylesheet">
	<link href="/assets/css/app.css" rel="stylesheet">
	<meta name="csrf-token" content="{{ csrf_token() }}" />
</head>
<body>
    @include('partials.nav')
	<div class="container main">
		@yield('content')
	</div>
	@include('partials.footer')
	<script src="/assets/js/jquery.min.js"></script>
	<script src="/assets/js/bootstrap.min.js"></script>
	<script src="/assets/js/jquery.autocomplete.min.js"></script>
	<script src="/assets/js/jquery.timeago.min.js"></script>
	<script src="/assets/js/app.js"></script>
	@yield('subjs')
</body>
</html>
