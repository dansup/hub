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
          <!-- Fake Table / Mockup Table -->
          <table id='nodestats-table' class="table table-hover">
            <thead>
              <tr>
                <th>version</th>
                <th>pubkey</th>
                <th>state</th>
                <th>bytesin</th>
                <th>bytesout</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>v16</td>
                <td>lqlg9mfppn8vs5ztx2fr9x9gvw2d7g9p885v7tsucn8zz2rdukw0.k</td>
                <td>ESTABLISHED</td>
                <td>5128520</td>
                <td>656646</td>
              </tr>
              <tr>
                <td>v16</td>
                <td>2hwmvzgxhy6h02zsk81cz2h4ybwh1hxjtsk7j8cu9954lzwgu8t0.k</td>
                <td>ESTABLISHED</td>
                <td>17164521</td>
                <td>925119</td>
              </tr>
              <tr>
                <td>v16</td>
                <td>xrj4g8klznc6ju2q1ljktlhff08c24lglmyqwtddtjy3vlsgrwq0.k</td>
                <td>ESTABLISHED</td>
                <td>4848688</td>
                <td>393939</td>
              </tr>
            </tbody>
          </table>
        </div>

          <!-- Actual DataTables -->
          <table id="example" class="table table-striped table-hover"></table>

      </div>
    </div>
  </div>
</div>

<!-- see: view/profile to avoid dupe script includes -->

<!-- <script src="/assets/js/jquery.min.js"></script> -->
<!-- <script src="/assets/js/jquery.dataTables.js"></script> -->
<!-- <script type="text/javascript">

  jQuery(document).ready(function($) {
    /* Fake Table / Mockup Table */
    var nsTable = $('#nodestats-table').dataTable();
    var dataSet = [];

    /* CORS header 'Access-Control-Allow-Origin' missing */
    jQuery.getJSON(
     'http://[fc5d:ac93:74a5:7217:bb2b:6091:42a0:218]/api/v0/node/fccc:5b2c:2336:fd59:794d:c0fa:817d:8d8/peers.json',
     { node: '{{{ $n->addr }}}' }, function(json, textStatus) {

      var dataSet = json;
      var textSet = textStatus;

      console.log('dataSet -> %s', dataSet);
      console.log('textSet -> %s', textSet);
    });

    $('#nodestats-table-wip').dataTable( {
      "data": dataSet,
      "columns": []
    });


});

</script>
-->
</div>
@endsection
