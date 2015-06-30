@extends('profile')


@section('content')

<link rel="stylesheet" href="/assets/css/highlight/railscasts.css">

  <div class="row">
    <div class="col-xs-12 col-sm-3">
      @include('node.partials.sidebar-nav', [
      'ip' => $n->addr
      ])
    </div>
    <div class="col-xs-12 col-sm-8">

      <div class="panel panel-default">
        <div class="panel-body">
          <div class="row">
            <div class="col-sm-12">
            <div class="addr-header">
              <div class="node-addr text-center">
                {{{ $n->addr }}}
              </div>
              <div class="node-pk text-center">
                {{{ $n->public_key }}}
              </div>
            </div>

              <div class="btn-group btn-group-justified" role="group" aria-label="{{{$n->addr}}}'s user actions">
                    <a href="/nodes/{{$n->addr}}/karma" class="btn btn-default" role="button">
                      <span class="ss-icon">
                        <i class="ss-flash"></i> 
                      </span>
                      <strong>
                        23
                      </strong>
                      Karma
                    </a>
                    <a href="/nodes/{{$n->addr}}/wot" class="btn btn-default" role="button">
                      <span class="ss-icon">
                        <i class="ss-users"></i> 
                      </span>
                      <strong>
                        0
                      </strong>
                      WoT Connections
                    </a>
                    <a href="/nodes/{{$n->addr}}/wots" class="btn btn-default" role="button">
                      <span class="ss-icon">
                        <i class="ss-users"></i> 
                      </span>
                      <strong>
                        0
                      </strong>
                      Signed Keys
                    </a>
                    <a href="/nodes/{{$n->addr}}/abuse-reports" class="btn btn-default" role="button">
                      <span class="ss-icon">
                        <i class="ss-list"></i> 
                      </span>
                      <strong>
                      0
                      </strong>
                      Abuse Reports
                    </a>
                    <div class="btn-group" role="group">
                      <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Options
                        <span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu">
                        <li><a href="/peer/request/new/{{$n->addr}}">Request to Peer</a></li>
                        <li><a href="/inbox/message/new/{{$n->addr}}">Send a message</a></li>
                        <li class="divider"></li>
                        <li><a href="/abuse/report/new/{{$n->addr}}?r=nlp&amp;t={{time()}}">Report</a></li>
                      </ul>
                    </div>
                  </div>
                  <hr>
                </div>
                <hr>
              </div>
<div>

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#data" aria-controls="data" role="tab" data-toggle="tab">Data</a></li>
    <li role="presentation"><a href="#visual" aria-controls="visual" role="tab" data-toggle="tab">Visual</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="data">
<pre><code class="json">{{ json_encode($n, JSON_PRETTY_PRINT)}}</code></pre>

    </div>
    <div role="tabpanel" class="tab-pane" id="visual">...</div>
  </div>

</div>


            </div>
          </div>
    </div>
    </div>

@endsection

@section('subjs')
<script src="/assets/js/highlight.pack.js"></script>
<script>hljs.initHighlightingOnLoad();</script>
<script type="text/javascript">
  $('.timeline-panel').click(function() {
  $('.timeline-body', this).toggle(); // p00f
  });
</script>
@stop
