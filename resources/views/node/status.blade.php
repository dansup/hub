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
                <h2>Status</h2>
              </div>

                  <ul class="media-list list-group">
                      <li class="list-group-item media">
                        <div class="media-left">
                          <a href="/nodes/{{{ $c->author_addr }}}">
                            <img class="media-object img-thumbnail" src="/assets/img/avatar.png" alt="{{{ $c->author_addr }}}'s Avatar" width="32px">
                          </a>
                        </div>
                        <div class="media-body">
                          <h4 class="media-heading"><a href="/nodes/{{{ $c->author_addr }}}" class="text-left ipv6-sm">{{{ $c->author_addr }}}</a></h4>
                          <p class="text-left">
                            {{{ $c->body }}}
                          </p>
                          <p class="small text-muted">
                            <span class="pull-right"> 
                              <i class="fa fa-clock-o"></i>  <a href="/nodes/{{{$n->addr}}}/status/{{{$c->id}}}"><time class="timeago" datetime="{{{$c->created_at}}}">{{{$c->created_at}}}</time></a>
                            </span>
                          </p>
                        </div>
                      </li>
                    </ul>

          </div>
        </div>
      </div>
    </div>
  </div>



  @endsection