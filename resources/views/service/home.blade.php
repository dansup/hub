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
          @foreach ($services as $s)

          <a class="list-group-item" href="/service/{{$s->id}}/{!!str_slug($s->name,'-')!!}">
            
            <h4 class="list-group-item-heading">{{ $s->name }}</h4>
            <p class="list-group-item-text">
              <small>
              {{$s->url}}
              </small> | 
              <span class="badge">
              {{$s->protocol}}/{{$s->port}}
              </span> | 
              <span class="badge">
                created <time class="timeago" datetime="{{$s->created_at}}"></time>
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