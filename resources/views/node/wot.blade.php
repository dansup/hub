@extends('profile')

@section('content')

<div class="row profile">

  <div class="col-md-3">
    @include('node.partials.sidebar-nav', [
      'ip' => $n->addr
      ])
  </div>

  <div class="col-md-9">
    @include('node.partials.content-header')
    <div class="profile-content active">
      <div class="col-xs-12 col-md-8 col-md-offset-2 text-center">
        <div class="page-header text-center">
          <h2>Web of Trust</h2>
        </div>

        <div class="alert alert-danger alert-block">
          <p class="alert-text">We're sorry, this page is not yet available.</p>
        </div>
      </div>
    </div>
  </div>

</div>

@endsection