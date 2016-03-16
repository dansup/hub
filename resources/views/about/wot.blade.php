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
  <h1>wot.json - self hosted trust authority</h1>
</div>
<ol class="breadcrumb">
  <li><a href="{{env('APP_URL')}}">Home</a></li>
  <li><a href="{{env('APP_URL')}}/about">About</a></li>
  <li><a href="{{env('APP_URL')}}/about/projects">Projects</a></li>
  <li class="active">wot.json</li>
</ol>
<div class="col-xs-12 col-md-8">
  <p class="lead">A comprehensive self-hosted node identity specification</p>
  <p>Example wot.json file:</p>
  <pre style="padding:0;margin:0;border:none">
  <code class="json">
    {
      "meta" : {
        "sha256_hash" : "07123e1f482356c415f684407a3b8723e10b2cbbc0b8fcd6282c49d37c9c1abc",
        "generated_at" : "iso8601 timestamp here",
        "updated_at" : "iso8601 here",
        "locale" : "en-CA",
        "max_crawl_per_day" : 5,
        "max_crawl_per_hour" : 2,
        "max_crawl_per_minute" : 1,
        "generator" : "github.com/dansup/wot-gen"
      },
      "node" : {
        "ipv6" : "fc00:0000:0000",
        "public_key" : "fasfafsafsafasfs.k",
        "location" : {
          "latitude" : -45.4995,
          "longitude" : 39.0000,
          "city" : "Montreal",
          "country" : "Canada"
        },
        "network" : {
          "capacity" : "100mbit",
          "datacenter_hosted" : true,
          "seeking_peers" : false
        },
        "peers" : {
          "fcbf:7bbc:32e4:716:bd00:e936:c927:fc14" : {
            "self_operated" : true,
            "public_key" : "xxxx.k",
            "updated_at" : "iso 8601"
          }
        },
        "meshlocal" : {
          "participant" : false,
          "city" : false
        }
      },
      "identity" : {
        "name" : "John Smith",
        "email": "jsmith&commat;example.org",
        "primary_node" : {
          "ipv6" : "fcc0:0000:0000",
          "public_key" : "fasfadfdfdsfdsfdsf.k"
        },
        "operated_nodes" : {
          "fcbf:7bbc:32e4:716:bd00:e936:c927:fc14",
          "fcbf:7bbc:32e4:716:bd00:e936:c927:fc12",
          "fcbf:7bbc:32e4:716:bd00:e936:c927:fc10"
        },
        "pgp" : {
          "fingerprint" : "F8C9 382A 8493 9348",
          "key_server" : "pgp.mit.edu"
        },
        "irc.hypeirc.net" : {
          "nickname" : "derp",
          "channels" : {
            "#hyperboria",
            "#webdev"
          }
        },
        "irc.fc00.io" : {
          "nickname" : "daniel",
          "channels" : {
            "#cjdns",
            "#hyperboria",
            "#webdev"
          }
        },
        "socialno.de" : {
          "username" : "derp",
          "profile_url" : "http://socialno.de/derp",
          "verify_url" : "",
          "verify_text" : "I verify this is me. http://[fc00:0000:0000:0000:0000:0000]/wot.json"
        },
        "twitter.com" : {
          "username" : "jsmith",
          "profile_url" : "https://twitter.com/jsmith",
          "verify_url" : "https://twitter.com/jsmith/status/101",
          "verify_text" : "I verify this is me. http://[fc00:0000:0000:0000:0000:0000]/wot.json"
        },
        "github.com" : {
          "username" : "jsmith",
          "profile_url" : "https://github.com/jsmith",
          "verify_url" : "https://gist.github.com/jsmith/101",
          "verify_text" : "I verify this is me. http://[fc00:0000:0000:0000:0000:0000]/wot.json"
        }
      },
      "trusted" : {
          "fc00:0000:0001" : 20,
          "fc00:0000:0003" : 30,
          "fc00:0000:0004" : 0,
          "fc00:0000:0009" : 100
      },
      "signed" : {
          "fc00:0000:0001" : true,
          "fc00:0000:0000" : false
      }
    }
    
  </code>
  </pre>
</div>
</div>
@endsection