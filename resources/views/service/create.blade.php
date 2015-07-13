@extends('app')

@section('content')

<div class="container">

<div class="row">

<div class="col-xs-12">
  <div class="panel panel-default">
    <div class="panel-body">
      <div class="page-header">
        <h1>Create a new service</h1>
        <p>You can only add services that exist on the same node you're currently using.</p>
      </div>
    </div>
  </div>
  @if($errors->has())
    <div class="alert alert-info">
      @foreach ($errors->all() as $error)
        <p class="alert-text lead">{{ $error }}</p>
      @endforeach
    </div>
  @endif
</div>

<div class="col-xs-12 col-sm-8">
  <div class="panel panel-default">
    <div class="panel-body">
      <form action="/api/web/service/create.json" class="form-horizontal" method="POST">
        {!! csrf_field() !!}

        <div class="form-group">
          <label class="col-xs-2 control-label" for="addr">Address</label>
          <div class="col-xs-10">
            <input type="hidden" name="addr" value="{{ \Req::ip() }}"></input>
            <input type="text" id="addr" class="form-control" value="{{ \Req::ip() }}" disabled />
            <div class="help-text"><span class="label label-danger">REQUIRED</span> Your services cjdns ip address</div>
          </div>
        </div>

        <div class="form-group">
          <label class="col-xs-2 control-label" for="name">Name</label>
          <div class="col-xs-10">
            <input type="text" id="name" name="name" class="form-control" placeholder="Acme Inc." required />
            <div class="help-text"><span class="label label-danger">REQUIRED</span> The name of your service, non-unique and 40 (unicode) characters max.</div>
          </div>
        </div>

        <div class="form-group">
          <label class="col-xs-2 control-label" for="port">Protocol</label>
          <div class="col-xs-10">
            <input type="number" id="port" name="port" class="form-control" placeholder="80" min="1" max="65535" required />
            <div class="help-text"><span class="label label-danger">REQUIRED</span> The port of your service. It will be verified upon registration.</div>
          </div>
        </div>

        <div class="form-group">
          <label class="col-xs-2 control-label" for="url">URL</label>
          <div class="col-xs-10">
            <input type="text" id="url" name="url" class="form-control" placeholder="http://example.com" required />
            <div class="help-text"><span class="label label-danger">REQUIRED</span> The valid URL of your service.</div>
          </div>
        </div>
        
        <div class="form-group">
          <label class="col-xs-2 control-label" for="bio">Description</label>
          <div class="col-xs-10">
            <textarea id="bio" name="bio" rows="4" class="form-control"></textarea>
            <div class="help-text"><span class="label label-warning">Optional</span> A description of your service.</div>
          </div>          
        </div>

        <div class="form-group">
          <label class="col-xs-2 control-label" for="city">City</label>
          <div class="col-xs-10">
            <input type="text" id="city" name="city" class="form-control" placeholder="Edmonton"/>
            <div class="help-text"><span class="label label-warning">Optional</span> The city your service is located in.</div>
          </div>
        </div>

        <div class="form-group">
          <label class="col-xs-2 control-label" for="country">Country</label>
          <div class="col-xs-10">
            <input type="text" id="country" name="country" class="form-control" placeholder="Canada"/>
            <div class="help-text"><span class="label label-warning">Optional</span> The country your service is located in.</div>
          </div>
        </div>

        <div class="text-right">
          <button type="reset" class="btn btn-danger">Clear</button>
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>

      </form>
    </div>
  </div>
</div>
<div class="col-xs-12 col-sm-4">
  <div class="panel panel-default">
    <div class="panel-body">
      <div id="reqs">
        <h3>Requirements</h3>
        <p>You must verify ownership of the service to add it to Hub, using a randomly generated named empty plaintext file to the root of your nodes web server on port 80.</p>
      </div>
      <div id="process">
        <h3>Service Registration</h3>
        <p>In order to successfully add your service to hub, you must follow these steps:</p>
        <ol>
          <li>Register a user account, or login</li>
          <li>Register using this form</li>
          <li>Verify the service <a href="/user/services">here</a></li>
        </ol>
      </div>
    </div>
  </div>
</div>

</div>

</div>


@endsection