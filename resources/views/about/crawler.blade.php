@extends('layouts.app')

@section('content')
<div class="container">
<div class="row">
<div class="page-header">
  <h1>Hub Crawler</h1>
</div>
<ol class="breadcrumb">
  <li><a href="{{env('APP_URL')}}">Home</a></li>
  <li><a href="{{env('APP_URL')}}/about">About</a></li>
  <li><a href="{{env('APP_URL')}}/about/projects">Projects</a></li>
  <li class="active">Crawler</li>
</ol>
<div class="col-xs-12 col-md-8">
  <div class="page-header">
    <h3>the nodeinfo.json + wot.json crawler</h3>
  </div>
  <p class="lead">We periodically crawl the network for <code>nodeinfo.json</code> and <code>wot.json</code> files.</p>
  <p>More information will be made available soon.</p>
</div>
<div class="col-xs-12 col-md-4">
  <div class="list-group">
    <a href="#" class="list-group-item active">
      <h4 class="list-group-item-heading">Crawler Info</h4>
      <p class="list-group-item-text">ipv6 address: <br>[fc00...]</p>
      <p class="list-group-item-text">user agent: <br>HubCrawler - http://hub.hyperboria.net/crawler</p>
    </a>
    <a href="#" class="list-group-item">
      <h4 class="list-group-item-heading">nodeinfo.json instructions</h4>
      <p class="list-group-item-text">
      1. Read docs and create valid <code>nodeinfo.json</code> file.<br>
      2. Save <code>nodeinfo.json</code> to the root of your webserver<br>
      3. Wait for crawlers!
      </p>
    </a>
    <a href="#" class="list-group-item">
      <h4 class="list-group-item-heading">wot.json instructions</h4>
      <p class="list-group-item-text">
      1. Read docs and create valid <code>wot.json</code> file.<br>
      2. Save <code>wot.json</code> to the root of your webserver<br>
      3. Wait for crawlers!
      </p>
    </a>
  </div>
</div>
</div>
</div>
@endsection