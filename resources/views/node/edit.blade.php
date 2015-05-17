@extends('profile')

@section('content')

   <div class="row profile">
    <div role="tabpanel">
    <div class="col-md-3">
      <div class="profile-sidebar">
        <div class="profile-userpic">
          <img src="/assets/img/avatar.png" class="img-responsive" alt="">
        </div>
        <div class="profile-navlet">
          <div class="profile-navlet-name">
            {{{ $n->hostname }}}
          </div>
          <div class="profile-navlet-job">
            Cjdns Version: {{{ $n->version }}}
          </div>
          <div class="profile-navlet-job">
            Latency: {{{ $n->latency }}} ms
          </div>
        </div>
        <div class="profile-usermenu">
          <ul class="nav" id="nodeNav" role="tablist">
            <li role="presentation" class="active">
              <a href="/nodes/{{{$n->addr}}}/edit">
                <i class="glyphicon glyphicon-pencil"></i>
                Edit
              </a>
            </li>
            <li role="presentation">
              <a href="/nodes/{{{$n->addr}}}/activity">
                  <i class="glyphicon glyphicon-home"></i>
                  Overview
              </a>
            </li>
            <li role="presentation">
              <a href="/nodes/{{{$n->addr}}}/activity">
                <i class="glyphicon glyphicon-list-alt"></i>
                Activity
              </a>
            </li>
            <li role="presentation">
              <a href="/nodes/{{{$n->addr}}}/comments">
              <i class="glyphicon glyphicon-comment"></i>
              Comments
            </a>
            </li>
            <li role="presentation">
              <a href="/nodes/{{{$n->addr}}}.json">
              <i class="fa fa-exchange"></i>
              nodeinfo.json
            </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="col-md-9 tab-content">
        <div role="tabpanel" class="profile-content tab-pane active" id="home">
           @if($errors->has())
               @foreach ($errors->all() as $error)
                <div class="alert alert-info">
                  <p class="alert-text lead">{{ $error }}</p>
                </div>
              @endforeach
            @endif
        <form class="form-horizontal" method="post" action="/api/web/node/update.json" role="form">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="web_token" value="{{{ sha1(\App\Hub\Req::ip().csrf_token()) }}}"/>
            <fieldset>

              <legend>Node Details</legend>
              <p>All fields are updated on save.</p>
              <!-- Text input-->
              <div class="form-group">
                <label class="col-sm-2 control-label" for="hostname">Hostname</label>
                <div class="col-sm-10">
                  <input type="text" id="hostname" name="hostname" placeholder="{{{ $n->hostname }}}" class="form-control">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="ownername">Owner Name</label>
                <div class="col-sm-10">
                  <input type="text" id="ownername" name="ownername" placeholder="{{{ $n->ownername }}}" class="form-control">
                </div>
              </div>

              <!-- Text input-->
              <div class="form-group">
                <label class="col-sm-2 control-label" for="textinput">Bio</label>
                <div class="col-sm-10">
                  <textarea class="form-control" name="bio" rows="3" disabled></textarea>
                </div>
              </div>

              <!-- Text input-->
              <div class="form-group">
                <label class="col-sm-2 control-label" for="textinput">City</label>
                <div class="col-sm-10">
                  <input type="text" id="city" name="city" placeholder="{{{ $n->city }}}" class="form-control">
                </div>
              </div>

              <!-- Text input-->
              <div class="form-group">
                <label class="col-sm-2 control-label" for="textinput">Province/State</label>
                <div class="col-sm-4">
                  <input type="text" id="province" name="province" placeholder="{{{ $n->province }}}" class="form-control">
                </div>

                <label class="col-sm-2 control-label" for="textinput">Country</label>
                <div class="col-sm-4">
                  <input type="text" id="country" name="country" placeholder="{{{ $n->country }}}" class="form-control">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="lat">Lattitude</label>
                <div class="col-sm-4">
                  <input type="text" id="lat" name="lat" placeholder="{{{ $n->lat }}}" class="form-control">
                </div>

                <label class="col-sm-2 control-label" for="lng">Longitude</label>
                <div class="col-sm-4">
                  <input type="text" id="lng" name="lng" placeholder="{{{ $n->lng }}}" class="form-control">
                </div>
              </div>


              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  <div class="pull-right">
                    <button type="reset" class="btn btn-default">Cancel</button>
                    <button type="submit" class="btn btn-success">Save</button>
                  </div>
                </div>
              </div>

            </fieldset>
          </form>

</div>
</div>
</div>
</div>
@endsection