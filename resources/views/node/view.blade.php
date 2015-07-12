@extends('profile')


@section('content')

<link rel="stylesheet" href="/assets/css/highlight/railscasts.css">
<link rel="stylesheet" href="/assets/css/morris.css">

<div class="row profile">
  <div role="tabpanel">
  <div class="col-sm-3">
    @include('node.partials.sidebar-nav', [
      'ip' => $n->addr
      ])
  </div>

  <div class="col-sm-9">

        @include('node.partials.content-header')
        <div>

          <!-- Nav tabs -->
          <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#overview" aria-controls="data" role="tab" data-toggle="tab">Overview</a></li>
            <li role="presentation"><a href="#graphs" aria-controls="graphs" role="tab" data-toggle="tab">Graphs</a></li>
            <li role="presentation"><a href="#data" aria-controls="data" role="tab" data-toggle="tab">NodeInfo.json</a></li>
            <li role="presentation"><a href="#visual" aria-controls="visual" role="tab" data-toggle="tab">Statuses</a></li>
          </ul>

          <!-- Tab panes -->
          <div class="tab-content" style="padding-top:20px">
            <div role="tabpanel" class="tab-pane active" id="overview">

              <div class="col-sm-8">
                @if (\Req::ip() === $n->addr)
                <div class="panel panel-default">
                  <form class="form-horizontal" method="POST" action="/node/{{$n->addr}}/status/update">
                    {!! csrf_field() !!}
                    <div class="panel-heading">
                      Post Status Update
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <textarea name="body" class="form-control" rows="6"></textarea>
                        </div>
                    </div>
                    <div class="panel-footer">
                      <div class="btn-group pull-right">
                        <button type="reset" class="btn btn-danger">
                          <i class="fa fa-times"></i>
                          Cancel
                        </button>
                        <button type="submit" class="btn btn-success">
                          <i class="fa fa-check"></i>
                          Post
                        </button>
                      </div>
                    </div>
                  </form>
                </div>
                @endif
                <div class="panel panel-default">
                  <div class="panel-heading clearfix">
                    <h3 class="panel-title pull-left">Node Overview</h3>
                  </div>
                  <div class="list-group">
                    <div class="list-group-item">
                      <p class="list-group-item-text">
                      Hostname
                      </p>
                      <h4 class="list-group-item-heading">
                      {{{ (is_null($n->hostname) ? 'Unknown' : $n->hostname )}}}
                      </h4>
                    </div>
                    <div class="list-group-item">
                      <p class="list-group-item-text">
                      Operator
                      </p>
                      <h4 class="list-group-item-heading">
                      {{{ (is_null($n->ownername) ? 'Unknown' : $n->ownername )}}}
                       </h4>
                    </div>

                    <div class="list-group-item">
                      <p class="list-group-item-text">
                      Last Seen
                      </p>
                      <h4 class="list-group-item-heading">
                      <time class="timeago" datetime="{{{ $n->updated_at }}}"> {{{ $n->updated_at }}}</time>
                       </h4>
                    </div>

                    <div class="list-group-item">
                      <p class="list-group-item-text">
                      First Seen
                      </p>
                      <h4 class="list-group-item-heading">
                      <time class="timeago" datetime="{{{ $n->created_at }}}"> {{{ $n->created_at }}}</time>
                       </h4>
                    </div>

                    <div class="list-group-item">
                      <p class="list-group-item-text">
                      Version
                      </p>
                      <h4 class="list-group-item-heading">
                      {{{ (is_int($n->version) ? $n->version : 'Unknown' )}}}
                       </h4>
                    </div>

                    <div class="list-group-item">
                      <p class="list-group-item-text">
                      Latency
                      </p>
                      <h4 class="list-group-item-heading">
                      {{{ (is_int($n->latency) ? $n->latency : 'Unknown' )}}}
                       </h4>
                    </div>

                    <div class="list-group-item">
                      <p class="list-group-item-text">Bio</p>
                      <p class="list-group-item-text lead">{{{ ($n->bio == null) ? 'Bio not available' : $n->bio }}}</p>
                    </div>
                  </div>
                  <div class="panel-footer">
                    <small></small>
                  </div>
                </div>
              </div>
              <div class="col-sm-4">
                @if ($n->addr === \Req::ip())
                <a class="btn btn-primary btn-block" href="/node/{{$n->addr}}/edit">
                  <i class="fa fa-pencil"></i>
                  Edit
                </a>
                @endif        
                {!! ($n->verified) ? 
                  '<a href="/docs/site/help#nodes-verification" class="btn btn-primary btn-block">'.
                  'Verified</a>' : 
                  '<a href="/docs/site/help#nodes-verification" class="btn btn-gray btn-block">'.
                  'Not Verified</a>'!!}

                {!! ($n->active) ? 
                  '<a href="/docs/site/help#nodes-active" class="btn btn-primary btn-block">'.
                  'Active</a>' : 
                  '<a href="/docs/site/help#nodes-active" class="btn btn-gray btn-block">'.
                  'Inactive</a>'!!}

                {!! ($n->hasNodeInfo) ? 
                  '<a href="/docs/site/help#nodes-nodeinfo" class="btn btn-primary btn-block">'.
                  'Valid NodeInfo.json</a>' : 
                  '<a href="/docs/site/help#nodes-nodeinfo" class="btn btn-gray btn-block">'.
                  'NodeInfo.json not found</a>'!!}

                <br>
                <p>
                @foreach ($n->badges as $badge)
                <button id="bid-{{$badge->id}}" class="btn btn-primary">
                  {{$badge->label}}
                </button>
                @endforeach
                </p>

              </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="graphs">
                  <div class="col-sm-8">
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h4>Node Peers</h4>
                </div>
                <div class="panel-body">
                    <div id="peer-chart" style="height:350px;"></div>
                  </div>
                </div>
              </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="data">
              <pre><code class="json">{{ json_encode($n, JSON_PRETTY_PRINT)}}</code></pre>
            </div>

            <div role="tabpanel" class="tab-pane" id="visual">
              <div class="row">

                <div class="col-xs-12">
                  <div class="col-sm-6">
                    <div class="panel panel-default">
                      <div class="panel-heading">Peers</div>
                      <div class="panel-body"></div>
                    </div>
                  </div>


                </div>
              </div>

            </div>
          </div>

        </div>


      </div>
    </div>

  </div>

</div>

  @endsection

  @section('subjs')
  <script src="/assets/js/raphael.min.js"></script>
  <script src="/assets/js/morris.min.js"></script>
  <script src="/assets/js/highlight.pack.js"></script>
  <script>hljs.initHighlightingOnLoad();</script>
  <script type="text/javascript">
      $(function() {

      var peerChartDataUrl = 'http://hub.dev/api/v0/charts/node/' + window.location.pathname.split('/')[2] +'/peers.json';
        // Create a Bar Chart with Morris
        var chart = Morris.Line({
          // ID of the element in which to draw the chart.
          element: 'peer-chart',
          data: [{ 'x': 0, 'y' : 0}], // Set initial data (ideally you would provide an array of default data)
          xkey: 'x', // Set the key for X-axis
          ykeys: ['y'], // Set the key for Y-axis
          labels: ['Peers', 'Time'], // Set the label when bar is rolled over,
          resize: true
        });

        // Fire off an AJAX request to load the data
          $('a[href="#graphs"]').on('shown.bs.tab', function () {
            $.ajax({
                type: "GET",
                dataType: 'json',
                url: peerChartDataUrl // This is the URL to the API
              })
              .done(function( data ) {
                // When the response to the AJAX request comes back render the chart with new data
                chart.setData(data);
              })
              .fail(function() {
                // If there is no communication between the server, show an error
                alert( "error occured" );
              });
          });
      });

  </script>
  @stop
