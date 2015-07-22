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
    <div class="profile-content active row">
      <div class="col-xs-12 col-md-8 col-md-offset-2 text-center">
        <div class="page-header text-center">
          <h2 id='g'>Follows <span class="text-muted">({{ count($f) }})</span></h2>
        </div>

        @foreach($f as $followed)
          <li class="list-group-item">
            <a href="/node/{{$followed}}">{{ $followed }}</a>
          </li>
        @endforeach

      </div>
    </div>
  </div>

</div>

@endsection