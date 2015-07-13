@extends('profile')

@section('content')

<div class="row profile">

  <div class="col-md-3">
    @include('node.partials.sidebar-nav', [
      'ip' => $n->addr
      ])
  </div>

  <div class="col-md-9">
    <div class="profile-content active row">
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
          <div class="page-header">
          <h1>Node Details</h1>
          </div>
        <fieldset>

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
              <textarea class="form-control" name="bio" rows="3"></textarea>
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
@endsection