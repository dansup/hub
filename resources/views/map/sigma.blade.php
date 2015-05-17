@extends('app')

@section('content')
<link rel="stylesheet" type="text/css" href="/assets/css/vis.css">

<h1 class="text-center">Hub Network Graph</h1>
<p class="text-center">Note: onload the nodes like to dance, eventually they calm down. You can also zoom in/out.</p>
<div class="col-xs-12 col-sm-6">
<input type="text" id="autocomplete" class="form-control" placeholder="Search by IPV6" />
<div class="autocomplete-suggestions">
</div>
</div>
<hr>
<div class="col-xs-12" id="map"></div>

@endsection

@section('subjs')
<script src="/assets/js/vis.min.js"></script>
<script src="/assets/js/jquery.autocomplete.min.js"></script>
<script>
    var ns, nodes, edges, network;
    $(document).ready(function () {
      nodes = new vis.DataSet();
      edges = new vis.DataSet();
     $.getJSON('/api/v1/map/graph/node.json', function(res) {
        nodes.add(res);
        ns = res;
     });
     $.getJSON('/api/v1/map/graph/edge.json', function(res) {
        edges.add(res);
     });

      var container = $('#map').get(0);
      var data = {
        nodes: nodes,
        edges: edges,
      };
      var options = {
        freezeForStabilization: true,
          edges: {
            color : '#AAAAAA',
            'color.highlight' : '#333333',
            width: 2,
          },
      };
      var network = new vis.Network(container, data, options);
      var acoptions = { 
            paramName: 'query',
            serviceUrl: '/api/v1/node/autocomplete.json',
            minChars:3,
            onSelect: function(suggestion) {
                network.focusOnNode(suggestion['value']);
            }
        };
    $('#autocomplete').autocomplete(acoptions);

    });
</script>
@endsection