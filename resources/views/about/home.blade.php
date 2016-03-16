@extends('layouts.app')

@section('content')
<div class="container">
<div class="row">
<div class="jumbotron">
  <h1>About Hub</h1>
</div>
<ol class="breadcrumb">
  <li><a href="{{env('APP_URL')}}">Home</a></li>
  <li class="active">About</li>
</ol>
<div class="col-xs-12 col-md-8">
  <div class="page-header">
    <h1>Network insight for everyone</h1>
  </div>
  <p class="lead">Hub was originally created to collect network data and analyse it.</p>
  <p>Information is power, and we believe that creating an open network data aggregation API will empower others to use it or something... CHANGEME</p>
</div>
<div class="col-xs-12 col-md-4">
  <div class="list-group">
    <a href="#" class="list-group-item active">
      <h4 class="list-group-item-heading">Est. 2013</h4>
      <p class="list-group-item-text">It was a little more than a month after development began that a small feature nodeinfo.json took on a life of its own. </p>
    </a>
    <a href="#" class="list-group-item">
      <h4 class="list-group-item-heading">Open Source</h4>
      <p class="list-group-item-text">Written using the Laravel 5.2 framework, hub is a modern PHP web application that is easily extendable.</p>
    </a>
    <a href="#" class="list-group-item">
      <h4 class="list-group-item-heading">REST API</h4>
      <p class="list-group-item-text">An evolving versioned API is the core feature of Hub.</p>
    </a>
  </div>
</div>
</div>
</div>
@endsection