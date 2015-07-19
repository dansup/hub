@extends('landing')

@section('content')

    <div class="navbar-wrapper">
      <div class="container">

        <nav class="navbar navbar-inverse navbar-static-top">
          <div class="container-fluid">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="/">Hub</a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
              <ul class="nav navbar-nav">
                <li class="active"><a href="/"><i class="fa fa-home"></i> Home</a></li>
                <li><a href="{{ url('/nodes') }}"><i class="fa fa-th"></i> Nodes</a></li>
                <li><a href="{{ url('/services') }}"><i class="fa fa-bookmark"></i> Services</a></li>
                <li><a href="{{ url('/people') }}"><i class="fa fa-user"></i> People</a></li>
                <form class="navbar-form navbar-right">
                  <input type="text" id="autocomplete" class="form-control searchbox" placeholder="Search...">
                  <div class="autocomplete-suggestions">
                  </div>
                </form>
            </ul>
            <ul class="nav navbar-nav navbar-right">
            @if (Auth::guest())
            <li><a href="{{ url('/auth/login') }}">Login</a></li>
            <li><a href="{{ url('/auth/register') }}">Register</a></li>
            @else
            <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>
            <ul class="dropdown-menu" role="menu">
            <li><a href="{{ url('/node/me') }}">My Node</a></li>
            <li><a href="{{ url('/auth/logout') }}">Logout</a></li>
            </ul>
            </li>
            @endif
            </ul>
            </div>
          </div>
        </nav>

      </div>
    </div>


    <!-- Carousel
    ================================================== -->
    <div id="myCarousel" class="carousel slide" data-ride="carousel">
      <!-- Indicators -->
      <ol class="carousel-indicators">
        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
        <li data-target="#myCarousel" data-slide-to="1"></li>
        <li data-target="#myCarousel" data-slide-to="2"></li>
      </ol>
      <div class="carousel-inner" role="listbox">
        <div class="item active">
          <div class="container">
            <div class="row">
              <div class="carousel-caption">
                <div class="col-xs-12 col-sm-3">
                  <p style="font-size:10em;">
                    <i class="fa fa-connectdevelop"></i>
                  </p>
                </div>
                <div class="col-xs-12 col-sm-8 text-left">
                    <h1>Community Graph.</h1>
                    <p>Hub is an open network graph utility for Hyperboria. Inspired by fb OpenGraph, hub aims to provide a similiar service for the network.</p>
                    <p>
                      <a class="btn btn-lg btn-white" href="/site/features/api" role="button">Hub Graph APIs<br><small>Now Available</small></a>
                      <a class="btn btn-lg btn-white disabled" href="/site/features/api/libs" role="button">API Libraries<br><small>coming soon</small></a>
                    </p>
                 </div>
              </div>
            </div>
          </div>
        </div>
        <div class="item">
          <div class="container">
            <div class="row">
              <div class="carousel-caption">
                <div class="col-xs-12 col-sm-3">
                  <p style="font-size:10em;">
                    <i class="fa fa-cogs"></i>
                  </p>
                </div>
                <div class="col-xs-12 col-sm-8 text-left">
                    <h1>People, Places &amp; Things.</h1>
                    <p><b>Nodes</b>, <b>people</b> and <b>services</b> form the foundation of hub, enabling rich directorys and individual listings for each section.</p>
                    <p>
                      <a class="btn btn-lg btn-white" href="/nodes" role="button">Browse Nodes</a>
                      <a class="btn btn-lg btn-white" href="/people" role="button">Browse People</a>
                      <a class="btn btn-lg btn-white" href="/services" role="button">Browse Services</a>
                    </p>
                 </div>
              </div>
            </div>
          </div>
        </div>
        <div class="item">
          <div class="container">
            <div class="row">
              <div class="carousel-caption">
                <div class="col-xs-12 col-sm-3">
                  <p style="font-size:10em;">
                    <i class="fa fa-code"></i>
                  </p>
                </div>
                <div class="col-xs-12 col-sm-8 text-left">
                    <h1>Open Source.</h1>
                    <p>Hub is open source, and licenced under GPLv3.</p>
                    <p>
                      <a class="btn btn-lg btn-white" href="https://github.com/dansup/hub" role="button">Source Code<br><small>via GitHub</small></a>
                      <a class="btn btn-lg btn-white disabled" href="#" role="button">Documentation<br><small>coming soon</small></a>
                    </p>
                 </div>
              </div>
            </div>
          </div>
        </div>
        </div>
      </div>
    </div><!-- /.carousel -->


    <!-- Marketing messaging and featurettes
    ================================================== -->
    <!-- Wrap the rest of the page in another container to center all the content. -->

    <div class="container content marketing">
<div class="row">
@if($errors->has())
  <div class="col-xs-12 col-sm-10 col-sm-offset-1">
   @foreach ($errors->all() as $error)
    <div class="alert alert-info">
      <p class="alert-text lead">{{ $error }}</p>
    </div>
  @endforeach
  </div>
@endif
<div class="col-xs-12 col-md-4">
  <div class="panel panel-default text-center">
    <div class="panel-heading">
      <h4>Nodes</h4>
    </div>
    <div class="panel-body">
      <ul class="text-left">
        <li>Node Info Directory</li>
        <li>Detailed node info, stats and more</li>
        <li>Add/Edit your node</li>
      </ul>
      <p><a href="/nodes" class="btn btn-default">Nodes</a></p>
    </div>
  </div>
</div>
<div class="col-xs-12 col-md-4">
  <div class="panel panel-default text-center">
    <div class="panel-heading">
      <h4>People</h4>
    </div>
    <div class="panel-body">
      <ul class="text-left">
        <li>Human Directory</li>
        <li>PGP, Social Directory</li>
        <li>Comments</li>
      </ul>
      <p><a href="#" class="btn btn-default">People</a></p>
    </div>
  </div>
</div>
<div class="col-xs-12 col-md-4">
  <div class="panel panel-default text-center">
    <div class="panel-heading">
      <h4>Services</h4>
    </div>
    <div class="panel-body">
      <ul class="text-left">
        <li>Services Directory</li>
        <li>Public irc servers, web servers, mail servers</li>
        <li>Add/Edit your service</li>
      </ul>
      <p><a href="/services" class="btn btn-default">Services</a></p>
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
<div class="col-xs-12 text-center">
  <p class="lead">For more features, click <a href="/site/features">here</a></p></div>
</div>
</div>

@endsection
