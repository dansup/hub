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
          <h2>Comments</h2>
        </div>

         
        <div class="panel panel-default">
          <form class="form-horizontal" method="POST" action="/node/{{$n->addr}}/comment/add">
          {!! csrf_field() !!}
          <input type="hidden" name="caid" value="{{$n->addr}}" />
          <input type="hidden" name="ct" value="{{time()}}" />
          <input type="hidden" name="cid" value="0" />
          <input type="hidden" name="parent_id" value="0" />

          <div class="modal-body">
              <div class="form-group">
                <label class="col-xs-3 control-label">Comment</label>
                <div class="col-xs-9">
                  <textarea name="body" class="form-control" rows="6"></textarea>
                </div>
              </div>
          </div>
          <div class="panel-footer">
            <div class="btn-group pull-right">
              <button type="reset" class="btn btn-danger">
                <i class="fa fa-times"></i>
                Cancel
              </button>
              <button type="submit" class="btn btn-success">
                <i class="fa fa-check"></i>
                Save
              </button>
            </div>
          </div>
          </form>
        </div>

      <?php if(count($comments) > 0): ?>
        <ul class="media-list list-group">
          <?php foreach ($comments as $c): ?>
            <li class="list-group-item media">
              <div class="media-left">
                <a href="#">
                  <img class="media-object img-thumbnail" src="/assets/img/avatar.png" alt="..." width="32px">
                </a>

              </div>
              <div class="media-body">
                <h4 class="media-heading"><a href="/node/{{ $c->author_addr }}" class="text-left ipv6">{{ $c->author_addr }}</a></h4>
                <p class="text-left">
                  {{ $c->body }}
                </p>
                <p class="small text-muted">
                  <span class="pull-right"> 
                    <i class="fa fa-clock-o"></i>  <a href="/node/{{$n->addr}}/status/{{$c->id}}"><time class="timeago" datetime="{{$c->created_at}}">{{$c->created_at}}</time></a>
                  </span>
                </p>
              </div>
            </li>
          <?php endforeach; ?>
        </ul>
        <?php echo $comments->render() ?>
      <?php else: ?>
        <div class="col-xs-12 col-md-8 col-md-offset-2 text-center">
          <p class="lead text-center">No Comments available.</p>
        <?php endif; ?>
      </div>
    </div>
    </div>
  </div>

  </div>

  @endsection