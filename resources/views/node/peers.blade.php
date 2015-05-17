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
                <h2>Peers <span class="text-muted">({{{ count($n->peers) }}})</span></h2>
              </div>

                <ul class="list-group">
                  @foreach($n->peers as $peer)
                  @if (empty($peer->node['addr']))
                    <li class="list-group-item">
                      <p>{{{$peer->peer_key}}}</p>
                      <small>Unknown Node</small>
                    </li>
                  @else
                  <li class="list-group-item">
                    <a href="/nodes/{{{ $peer->node['addr'] }}}" class="ipv6">
                      {{{ $peer->node['addr'] }}} ({{{ count($peer->node->peers) / 100}}})
                    </a>
                  </li>
                  @endif
                  @endforeach
                
                </ul>

              </div>
            </div>
    </div>
  </div>
</div>
</div>
</div>


@endsection