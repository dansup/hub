@extends('profile')

@section('content')

<div class="container">
    <div class="row profile">
    <div role="tabpanel">
    <div class="col-md-3">
      <div class="profile-sidebar">
        <div class="profile-userpic">
          <img src="/img/avatar.png" class="img-responsive" alt="">
        </div>
        <div class="profile-navlet">
          <div class="profile-navlet-name">
            {{{ $n->hostname }}}
          </div>
          <div class="profile-navlet-job">
            v{{{ $n->version }}}
          </div>
        </div>
        <div class="profile-userbuttons">
          <button type="button" class="btn btn-success btn-sm">Follow</button>
          <button type="button" class="btn btn-danger btn-sm">Message</button>
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
              <div class="page-header text-center">
                <h3>{{{ $n->addr }}}</h3>
              </div>
            </div>
            <div role="tabpanel" class="profile-content tab-pane" id="peers">
              <div class="page-header text-center">
                <h3>{{{ $n->addr }}}</h3>
              </div>
              <div class="well"><h4>Peer stuff here</h4></div>
            </div>
            <div role="tabpanel" class="profile-content tab-pane" id="services">
              <div class="page-header text-center">
                <h3>{{{ $n->addr }}}</h3>
              </div>
              <div class="well"><h4>Services stuff here</h4></div>
            </div>
            <div role="tabpanel" class="profile-content tab-pane" id="stats">
              <div class="page-header text-center">
                <h3>{{{ $n->addr }}}</h3>
              </div>
              <div class="well"><h4>Stats stuff here</h4></div>
            </div>
    </div>
  </div>
</div>
</div>


@endsection