@extends('profile')
@section('content')

<div class="row profile">
  <div role="tabpanel">
    <div class="col-md-3">
      @include('node.partials.sidebar-nav', [ 'ip' => $n->addr ])
      <!-- Missing $avatar_hash and public key -->
    </div>
    <div class="col-md-9">
      @include('node.partials.content-header')
      <!-- Missing $public_key -->

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
                <th>state</th>
                <th>pubkey</th>
                <th>bytesin</th>
                <th>bytesout</th>
                <!-- <th>version</th> -->
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

    function prettyBytes(num) {
        if (typeof num !== 'number') {
          throw new TypeError('Expected a number');
        }
        var exponent;
        var unit;
        var neg = num < 0;
        var units = ['B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

        if (neg) {
          num = -num;
        }

        if (num < 1) {
          return (neg ? '-' : '') + num + ' B';
        }

        exponent = Math.min(Math.floor(Math.log(num) / Math.log(1000)), units.length - 1);
        num = (num / Math.pow(1000, exponent)).toFixed(2) * 1;
        unit = units[exponent];

        return (neg ? '-' : '') + num + ' ' + unit;
      };


    /* See: ApiController::getNodePeers() */
    $('#nodestats-table').dataTable( {
        // "ajax": '/api/v0/node/{{{ $n->addr }}}/peers.json',
        "data": [
                  [
                    "ESTABLISHED",
                    "vf6src4q8v26ru97q4f6bprmldsd0c08vbuttb04bcyf94xljxu0.k",
                    prettyBytes(28655924),
                    prettyBytes(39993288),
                  ],
                  [
                    "ESTABLISHED",
                    "lqlg9mfppn8vs5ztx2fr9x9gvw2d7g9p885v7tsucn8zz2rdukw0.k",
                    prettyBytes(5128520),
                    prettyBytes(656646),
                  ],
                  [
                    "ESTABLISHED",
                    "2hwmvzgxhy6h02zsk81cz2h4ybwh1hxjtsk7j8cu9954lzwgu8t0.k",
                    prettyBytes(17164521),
                    prettyBytes(925119),
                  ],
                  [
                    "ESTABLISHED",
                    "xrj4g8klznc6ju2q1ljktlhff08c24lglmyqwtddtjy3vlsgrwq0.k",
                    prettyBytes(4848688),
                    prettyBytes(393939),
                  ],
                ]
    });


});

</script>

@stop
