@extends('profile')
@section('content')

<div class="row profile">
  <div role="tabpanel">
    <div class="col-md-3">
      @include('node.partials.sidebar-nav', [ 'ip' => $n->addr ])
    </div>
    <div class="col-md-9">
      @include('node.partials.content-header')
      <div class="profile-content active">

        <div class="col-xs-12 col-md-8 col-md-offset-2 text-center">
          <div class="page-header text-center">
            <h2>Node Stats <span class="text-muted">(Example)</span></h2>
          </div>
        </div>

        <div class="col-xs-12 col-md-8 col-md-offset-0 text-center">
          <table id='nodestats-table' class="table table-hover">
            <thead>
              <tr>
              @foreach ($tophubcol as $value)
                <th>{{ $value }}</th>
              @endforeach
              </tr>
            </thead>
          </table>
        </div>

      </div>
    </div>
  </div>
</div>

@endsection

@section('subjs')
<link rel="stylesheet" href="/assets/css/jquery.dataTables.min.css">
<script src="/assets/js/jquery.dataTables.min.js"></script>

<script type="text/javascript">
  jQuery(document).ready(function($) {

    var peers = jQuery.getJSON(
      '/api/v0/node/{{{ $n->addr }}}/peers.json', { node: '{{{ $n->addr }}}'}
    );

    peers.done(function(peers) {

      console.table(peers);

      $('#nodestats-table').dataTable( {

        "data": peers,

        @foreach ($falseopts as $value)
        {{ "$value" }}: false,
        @endforeach

        "columns": [
          @foreach ($tophubcol as $value)
            { "data": "{{ $value }}" },
          @endforeach
        ]
      });
    });

});
</script>

<script type="text/javascript">

  function prettyBytes(num) {
    if (typeof num !== 'number') { throw new TypeError('Expected a number'); }
    var exponent; var unit; var neg = num < 0;
    var units = ['B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
    if (neg) { num = -num; }
    if (num < 1) { return (neg ? '-' : '') + num + ' B'; }
    exponent = Math.min(Math.floor(Math.log(num) / Math.log(1000)), units.length - 1);
    num      = (num / Math.pow(1000, exponent)).toFixed(2) * 1;
    unit     = units[exponent];
    return (neg ? '-' : '') + num + ' ' + unit;
  };
</script>

@stop
