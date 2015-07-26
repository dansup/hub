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
                <th>version</th>
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

<!-- <link rel="stylesheet" href="/assets/css/jquery.dataTables.min.css"> -->
<script src="/assets/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">

  jQuery(document).ready(function($) {

    jQuery.getJSON(
     '/api/v0/node/{{{ $n->addr }}}/peers.json',
     { node: '{{{ $n->addr }}}' }, function(json, textStatus) {
      var dataSet = json;
      var textSet = textStatus;
      console.log('dataSet -> %s', dataSet);
      console.log('textSet -> %s', textSet);
    });


    /* See: ApiController::getNodePeers() */
    $('#nodestats-table').dataTable( {
        // "data": dataSet
        "data": [
                  [
                    "ESTABLISHED",
                    "vf6src4q8v26ru97q4f6bprmldsd0c08vbuttb04bcyf94xljxu0.k",
                    "28655924",
                    "39993288",
                    "v16"
                  ],
                  [
                    "ESTABLISHED",
                    "lqlg9mfppn8vs5ztx2fr9x9gvw2d7g9p885v7tsucn8zz2rdukw0.k",
                    "5128520",
                    "656646",
                    "v16",
                  ],
                  [
                    "ESTABLISHED",
                    "2hwmvzgxhy6h02zsk81cz2h4ybwh1hxjtsk7j8cu9954lzwgu8t0.k",
                    "17164521",
                    "925119",
                    "v16",
                  ],
                  [
                    "ESTABLISHED",
                    "xrj4g8klznc6ju2q1ljktlhff08c24lglmyqwtddtjy3vlsgrwq0.k",
                    "4848688",
                    "393939",
                    "v16",
                  ],
                ]
    });


});

</script>

@stop
