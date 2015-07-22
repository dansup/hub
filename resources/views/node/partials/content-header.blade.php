    <div class="panel panel-default">
      <div class="panel-body">
        <div class="row">
          <div class="col-sm-12">
            <div class="addr-header">
              <div class="node-addr text-center">
                {{ $n->addr }}
              </div>
              <div class="node-pk text-center">
                {{ (isset($n->public_key)) ? $n->public_key : '???' }}
              </div>
            </div>

            <div class="btn-group btn-group-justified" role="group" aria-label="{{$n->addr}}'s user actions">
              <a href="/node/{{$n->addr}}/karma" class="btn btn-default" role="button">
                <span class="ss-icon">
                  <i class="ss-flash"></i>
                </span>
                <strong>
                  23
                </strong>
                Karma
              </a>
              <a href="/node/{{$n->addr}}/wot" class="btn btn-default" role="button">
                <span class="ss-icon">
                  <i class="ss-users"></i>
                </span>
                <strong>
                  0
                </strong>
                WoT Connections
              </a>
              <a href="/node/{{$n->addr}}/wots" class="btn btn-default" role="button">
                <span class="ss-icon">
                  <i class="ss-users"></i>
                </span>
                <strong>
                  0
                </strong>
                Signed Keys
              </a>
              <a href="/node/{{$n->addr}}/abuse-reports" class="btn btn-default" role="button">
                <span class="ss-icon">
                  <i class="ss-list"></i>
                </span>
                <strong>
                  {{ (isset($n->abuse)) ? count($n->abuse) : '???' }}
                </strong>
                Abuse Reports
              </a>
              <div class="btn-group" role="group">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Options
                  <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                  <li><a href="/node/{{$n->addr}}/peer/request">Request to Peer</a></li>
                  <li><a href="/node/{{$n->addr}}/message">Send a message</a></li>
                  <li class="divider"></li>
                  <li><a href="/abuse/report/new/{{$n->addr}}?r=nlp&amp;t={{time()}}">Report</a></li>
                </ul>
              </div>
            </div>
            <hr>
          </div>

          <hr>

        </div>