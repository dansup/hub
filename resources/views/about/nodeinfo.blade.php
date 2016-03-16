@extends('layouts.app')
@section('subcss')
<link rel="stylesheet" href="/assets/css/railscasts.css">
@endsection
@section('subjs')
<script src="/assets/js/highlight.pack.js"></script>
<script>hljs.initHighlightingOnLoad();</script>
@endsection
@section('content')
<div class="container">
<div class="row">
<div class="page-header">
  <h1>nodeinfo.json - self hosted node information</h1>
</div>
<ol class="breadcrumb">
  <li><a href="{{env('APP_URL')}}">Home</a></li>
  <li><a href="{{env('APP_URL')}}/about">About</a></li>
  <li><a href="{{env('APP_URL')}}/about/projects">Projects</a></li>
  <li class="active">nodeinfo.json</li>
</ol>
<div class="col-xs-12 col-md-8">
<p class="lead">We created this network data exchange format to help standardize a means of sharing and collecting network data. <br>Unfortunately we never released an official specification so we have decided to create a successor with a strict format called <a href="/about/projects/wot.json">wot.json</a>.</p>
<div class="alert alert-info">
  <div class="alert-text">Notice: This format is deprecated in favor of <a href="/about/projects/wot.json">wot.json</a>, we will stop crawling nodeinfo.json after Febuary 28, 2017.</div>
</div>
  <p>Example nodeinfo.json file:</p>
  <pre style="padding:0;margin:0;border:none">
  <code class="json">
 {
    "generated": "2016-02-07T19:08:26+00:00",
    "hostname": "abkco.appno.de",
    "ip": "fcec:ae97:8902:d810:6c92:ec67:efb2:3ec5",
    "contact": {
        "name": "daniel",
        "email": "dan@projectmeshnet.org",
        "hypeIRC": "derp",
        "socialnode": "derp"
    },
    "last_modified": "2014-11-30T00:35:48+00:00",
    "services": [
        {
            "name": "appnode",
            "type": "http",
            "uri": "http:\/\/appno.de",
            "description": "null"
        },
        {
            "name": "socialnode",
            "type": "http",
            "uri": "http:\/\/socialno.de",
            "description": "socialnode is a stream-oriented social network service for Hyperboria."
        },
        {
            "name": "urlcloud",
            "type": "http",
            "uri": "http:\/\/urlcloud.net",
            "description": "a simple file sharing service for Hyperboria."
        },
        {
            "name": "HypeIRC",
            "type": "irc",
            "uri": "irc:\/\/[fcec:ae97:8902:d810:6c92:ec67:efb2:3ec5]",
            "description": "the Hyperboria IRC network."
        }
    ]
}
  </code>
  </pre>
</div>
</div>
@endsection