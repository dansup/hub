@extends('profile')

@section('content')

    <div class="row profile">
    <div role="tabpanel">
    <div class="col-md-3">
      <div class="profile-sidebar">
        <div class="profile-userpic">
          <img src="/img/avatar.png" class="img-responsive" alt="">
        </div>
        <div class="profile-navlet">
          <div class="profile-navlet-name">
            {{{ $s->hostname }}}
          </div>
          <div class="profile-navlet-job">
            Cjdns Version: {{{ $s->version }}}
          </div>
          <div class="profile-navlet-job">
            Latency: {{{ $s->latency }}} ms
          </div>
        </div>
        <div class="profile-userbuttons">
          <button type="button" class="btn btn-success btn-sm">Trust</button>
          <button type="button" class="btn btn-danger btn-sm">Distrust</button>
        </div>
        <div class="profile-usermenu">
          <ul class="nav" id="nodeNav" role="tablist">
            <li role="presentation" class="active">
              <a href="#home" aria-controls="home" role="tab" data-toggle="tab" >
              <i class="glyphicon glyphicon-home"></i>
              Overview </a>
            </li>
            <li role="presentation">
              <a href="#peers" aria-controls="peers" role="tab" data-toggle="tab" >
              <i class="glyphicon glyphicon-user"></i>
              Peers </a>
            </li>
            <li role="presentation">
              <a href="#services" aria-controls="services" role="tab" data-toggle="tab" >
              <i class="glyphicon glyphicon-ok"></i>
              Services </a>
            </li>
            <li role="presentation">
              <a href="#stats" aria-controls="stats" role="tab" data-toggle="tab" >
              <i class="glyphicon glyphicon-flag"></i>
              Stats </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="col-md-9 tab-content">
            <div role="tabpanel" class="profile-content tab-pane active" id="home">
              <div class="row">
              <div class="page-header text-center">
                <h3>{{{ $s->name }}}</h3>
              </div>

              <div class="col-xs-12 infolet">
                <div class="col-xs-12 col-sm-2 emphasis">
                    <h2><strong> 125 </strong></h2>                    
                    <p><small>Peers</small></p>
                    <button class="btn btn-default"><span class="fa fa-plus-circle"></span> Request </button>
                </div>
                <div class="col-xs-12 col-sm-2 emphasis">
                    <h2><strong>0</strong></h2>                    
                    <p><small>Services</small></p>
                    <button class="btn btn-default"><span class="fa fa-link"></span> Services </button>
                </div>
                <div class="col-xs-12 col-sm-2 emphasis">
                    <h2><strong> 15 </strong></h2>                    
                    <p><small>Followers</small></p>
                    <button class="btn btn-default"><span class="fa fa-plus"></span> Follow </button>
                </div>
                <div class="col-xs-12 col-sm-2 emphasis">
                    <h2><strong>0</strong></h2>                    
                    <p><small>Reports</small></p>
                    <a class="btn btn-default"><span class="fa fa-link"></span>  Reports </a>
                </div>
                <div class="col-xs-12 col-sm-4 emphasis">
                    <h2><strong><time class="timeago" datetime="{{{ $s->last_seen }}}">{{{ $s->last_seen }}}</time></strong></h2>                    
                    <p><small>Last Seen</small></p>
                    <div class="btn-group dropup btn-block center-block">
                      <button type="button" class="btn btn-default"><span class="fa fa-gear"></span> Options </button>
                      <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                      </button>
                      <ul class="dropdown-menu text-left" role="menu">
                        <li><a href="#"><span class="fa fa-envelope pull-right"></span> Send a message </a></li>
                        <li><a href="#"><span class="fa fa-list pull-right"></span>Trust  </a></li>
                        <li class="divider"></li>
                        <li><a href="#"><span class="fa fa-warning pull-right"></span>Report this user for abuse</a></li>
                        <li><a href="#"><span class="fa fa-warning pull-right"></span>Report this user for spam</a></li>
                      </ul>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 divider tp-20"></div>
            <div class="col-md-6 statuslet">
              <div class="text-center">
              <div class="well">
                  <h4>Comments</h4>
              <div class="input-group">
                  <input type="text" id="userComment" class="form-control input-sm chat-input" placeholder="Write your message here..." />
                <span class="input-group-btn" onclick="addComment()">     
                      <a href="#" class="btn btn-default btn-sm"><span class="fa fa-comment"></span> Add Comment</a>
                  </span>
              </div>
              <hr>
              <ul id="sortable" class="list-unstyled ui-sortable">
                  <a href="/nodes/fc28:a67a:b325:8529:fd07:6ab0:cf81:c177" class="small pull-left ip">fc28:a67a:b325:8529:fd07:6ab0:cf81:c177</a>
                  <small class="pull-right text-muted">
                     <span class="fa fa-clock-o"></span>  7 mins ago</small>
                  </br>
                  <li class="ui-state-default">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. </li>
                  </br>
                   <strong class="pull-left username">@derp</strong>
                  <small class="pull-right text-muted">
                     <span class="fa fa-clock-o"></span>  14 mins ago</small>
                  </br>
                  <li class="ui-state-default">Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</li>
                  
              </ul>
              </div>
            </div>
            </div>
            
            <div class="col-xs-12 col-sm-6 feedlet">
              <ul class="timeline">
                      <li>
                        <div class="timeline-badge success"><i class="fa fa-check"></i></div>
                        <div class="timeline-panel">
                          <div class="timeline-heading">
                            <h4 class="timeline-title">Setup Webhook API</h4>
                            <p><small class="text-muted"><i class="fa fa-clock-o"></i> 11 hours ago via Web</small></p>
                          </div>
                          <div class="timeline-body">
                            <p>Setup a webhook API.</p>
                          </div>
                        </div>
                      </li>
                      <li>
                        <div class="timeline-badge danger"><i class="fa fa-pencil-square-o"></i></div>
                        <div class="timeline-panel">
                          <div class="timeline-heading">
                            <h4 class="timeline-title">Updated node information</h4>
                            <p><small class="text-muted"><i class="fa fa-clock-o"></i> 11 hours ago via API</small></p>
                          </div>
                          <div class="timeline-body">
                            <p>Added an ownername.</p>
                          </div>
                        </div>
                      </li>

                  </ul>
            </div>
            
            </div>
            </div>
            <div role="tabpanel" class="profile-content tab-pane" id="peers">
              <div class="page-header text-center">
                <h3>{{{ $s->addr }}}</h3>
              </div>
              <div class="well"><h4>Peer stuff here</h4></div>
            </div>
            <div role="tabpanel" class="profile-content tab-pane" id="services">
              <div class="page-header text-center">
                <h3>{{{ $s->addr }}}</h3>
              </div>
              <div class="well"><h4>Services stuff here</h4></div>
            </div>
            <div role="tabpanel" class="profile-content tab-pane" id="stats">
              <div class="page-header text-center">
                <h3>{{{ $s->addr }}}</h3>
              </div>
              <div class="well"><h4>Stats stuff here</h4></div>
            </div>
    </div>
  </div>
</div>

@section('subjs')
<script type="text/javascript">
$('.timeline-panel').click(function() {
    $('.timeline-body', this).toggle(); // p00f
});
function addComment() {
  return alert('Comment Added! (Not really, this is just a demo)');
}
</script>
@stop

@endsection