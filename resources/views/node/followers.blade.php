@extends('profile')


@section('content')

<div class="row profile">
  <div role="tabpanel">
    <div class="col-md-3">
      @include('node.partials.sidebar-nav', [
        'ip' => $n->addr
        ])
        <div class="col-md-9">
          @include('node.partials.content-header')
          <div class="profile-content active">
            <div class="col-xs-12 col-md-8 col-md-offset-2 text-center">
              <div class="page-header text-center">
                <h2>Followers <span class="text-muted">({{{ count($f) }}})</span></h2>
              </div>

              <?php foreach($f as $follower): ?>
              <li class="list-group-item"><a href="/nodes/{{{$follower}}}">{{{ $follower }}}</a></li>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>



  @endsection