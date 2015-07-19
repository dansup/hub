@extends('profile')

@section('content')
<div class="row">
  <div class="col-xs-12 col-md-6 col-md-offset-3">
    <div class="panel panel-default bootcards-file">
      <div class="panel-heading">
        <h3 class="panel-title">Service Listing</h3>
      </div>
      <div class="list-group">
        <div class="list-group-item">
          <a href="#">
            <i class="fa fa-globe"></i>
          </a>
          <h4 class="list-group-item-heading">
            <a href="#">
              {{ $s->name }}
            </a>
          </h4>
          <p class="list-group-item-text">url: <strong><a href="{!! $s->url !!}">{{$s->url}}</a></strong></p>
          <p class="list-group-item-text">node: <strong><a href="/node/{{ $s->addr }}">{{$s->addr}}</a></strong></p>
          <p class="list-group-item-text"><strong>{{$s->protocol}}</strong> <span class="label label-default">port: {{$s->port}}</span></p>
        </div>
        <div class="list-group-item">
          <p class="list-group-item-text"><strong>Added <time class="timeago" datetime="{{$s->created_at}}"></time></strong></p>
        </div>
        <div class="list-group-item">
          <p class="list-group-item-text">{{$s->bio}}</p>
        </div>
      </div>
      <div class="panel-footer">
        <div class="btn-group btn-group-justified">
          <div class="btn-group">
            <a class="btn btn-default" href="{{$s->url}}">
              <i class="fa fa-eye"></i>
              Go
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
           
@section('subjs')
<script type="text/javascript">
$('.timeline-panel').click(function() {
    $('.timeline-body', this).toggle(); // p00f
});
function addComment() {
  return alert('Comment Added! (Not really, this is just a demo)');
}
</script>
@stop

@endsection