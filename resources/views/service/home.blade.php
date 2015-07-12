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
    {!! $services->render() !!}
  </div>
  <div class="col-xs-12 col-sm-4">
    <p class="text-right">
      <a class="btn btn-default" href="/service/create">Add Service</a>
    </p>
  </div>
  <div class="col-xs-12">

    <div class="bootcards-list">
      <div class="panel panel-default">
        <div class="list-group">
          @foreach ($services as $n)

          <a class="list-group-item" href="/node/{{$n->id}}">
            
            <h4 class="list-group-item-heading">{{ $n->name }}</h4>
            <p class="list-group-item-text">
              <small>
              </small> | 
              <span class="badge">
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

    {!! $services->render() !!}
  </div>
</div>

  @endsection