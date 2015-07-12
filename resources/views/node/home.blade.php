@extends('app')

@section('content')
@if($errors->has())
<div class="col-xs-12 col-sm-10 col-sm-offset-1">
  @foreach ($errors->all() as $error)
  <div class="alert alert-info">
    <p class="alert-text lead">{{ $error }}</p>
  </div>
  @endforeach
</div>
@endif
<div class="col-xs-12 col-md-10 col-md-offset-1">
  <div class="col-xs-12 col-sm-8">
    {!! $nodes->render() !!}
  </div>
  <div class="col-xs-12 col-sm-4">
    <p class="text-right">
      <a class="btn btn-default" href="/node/me">My Node</a>
      <a class="btn btn-default" href="/node/create">Add Node</a>
    </p>
  </div>
  <div class="col-xs-12">

    <div class="bootcards-list">
      <div class="panel panel-default">
        <div class="list-group">
          @foreach ($nodes as $n)

          <a class="list-group-item" href="/node/{{$n->addr}}">
            <img src="{!! ($n->avatar_hash == null) ? '/assets/img/avatar.png' : '/assets/avatars/'.$n->avatar_hash !!}" alt="{{$n->addr}}'s node avatar" class="img-rounded pull-left" width="40px"/>
            <h4 class="list-group-item-heading">{{ $n->addr }}</h4>
            <p class="list-group-item-text">
              <small>
                {{$n->public_key}}
              </small> | 
              <span class="badge">
                v {{ $n->version }}
              </span> | 
              <span class="badge">
                seen <time class="timeago" datetime="{{$n->updated_at}}"></time>
              </span>
            </p>
          </a>

          @endforeach
        </div>
      </div>
    </div>

    {!! $nodes->render() !!}
  </div>
</div>

  @endsection