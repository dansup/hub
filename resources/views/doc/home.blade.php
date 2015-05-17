@extends('app')

@section('content')

      <div class="docotron jumbotron-docs text-center">
        <h1> Hub Docs</h1>
        <p>The official documentation of Hub. Batteries not included.</p>
      </div>


      <div class="container content">


      <div class="row">

        <div class="col-xs-12 col-md-4">
          <div class="panel panel-default text-center">
            <div class="panel-heading">
              <h4><a href="/docs/site/help#nodes">Nodes</a></h4>
            </div>
          </div>
        </div>

        <div class="col-xs-12 col-md-4">
          <div class="panel panel-default text-center">
            <div class="panel-heading">
              <h4><a href="/docs/site/help#people">People</a></h4>
            </div>
          </div>
        </div>

        <div class="col-xs-12 col-md-4">
          <div class="panel panel-default text-center">
            <div class="panel-heading">
              <h4><a href="/docs/site/help#services">Services</a></h4>
            </div>
          </div>
        </div>
      </div>


      <div class="row">

        <div class="col-xs-12 col-md-4">
          <div class="panel panel-default text-center">
            <div class="panel-heading">
              <h4>NodeInfo.json</h4>
            </div>
            <div class="panel-body">
              <p>A comprehensive hyperboria specific json specification for exchanging node info/data.</p>
              <p><a href="#" class="btn btn-default">Spec/Demo</a></p>
            </div>
          </div>
        </div>

        <div class="col-xs-12 col-md-4">
          <div class="panel panel-default text-center">
            <div class="panel-heading">
              <h4>API</h4>
            </div>
            <div class="panel-body">
              <p>Developers will love this powerful, easy to use REST API.</p>
              <p><a href="#" class="btn btn-default">API</a></p>
            </div>
          </div>
        </div>
        <div class="col-xs-12 col-md-4">
          <div class="panel panel-default text-center">
            <div class="panel-heading">
              <h4>Open Source + Federated</h4>
            </div>
            <div class="panel-body">
              <p>Run your own instance, and leverage the community strength of federation.</p>
              <p><a href="/services" class="btn btn-default">Services</a></p>
            </div>
          </div>
        </div>

      </div>


      </div>

@endsection

