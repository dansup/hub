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
      <h2>Services <span class="text-muted">({{{ count($n->services) }}})</span></h2>
      </div>

      <ul class="list-group">
      <?php foreach($n->services as $s): ?>
      <li class="list-group-item"><a href="/nodes/{{{$s}}}">{{{ $s }}}</a></li>
      <?php endforeach; ?>
      </ul>

      </div>
    </div>
    </div>
    </div>
  </div>
</div>

@endsection