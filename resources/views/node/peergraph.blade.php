@extends('layouts.bulma')
@section('subcss')
<link rel="stylesheet" type="text/css" href="/assets/css/vis.css">
@endsection
@section('subjs')
<script type="text/javascript" src="/assets/js/vis.js"></script>
<script type="text/javascript">
 var nodes = null;
    var edges = null;
    var network = null;
    $(document).ready(function() {
      $.getJSON( "/api/v1/node/{{$node->addr}}/peergraph.json", function( res ) {
        console.log('data' + res);
        function draw() {
          // create people.
          // value corresponds with the age of the person
          nodes = res.nodes;

          // create connections between people
          // value corresponds with the amount of contact between two people
          edges = res.edges;

          // Instantiate our network object.
          var container = document.getElementById('peerGraph');
          var data = {
            nodes: nodes,
            edges: edges
          };
          var options = {
            nodes: {
              shape: 'dot',
              scaling:{
                label: {
                  min:8,
                  max:20
                }
              }
            }
          };
          network = new vis.Network(container, data, options);
        }
        draw();
      });
    });
</script>
@endsection
@section('content')
<section class="section">
  <div class="container">
    <h1 class="title is-centered">{{ $node->addr }}</h1>
    <h2 class="subtitle is-centered">public key: {{$node->public_key}}</h2>
    <hr>
    <div class="columns">
    @include('layouts.partials.node-sidebar')
    @include('layouts.partials.node-header')

        <div id="peerGraph" style="height:500px;"></div>
      </div>
    </div>
  </div>
</section>
@endsection