      <div class="profile-sidebar">
        <div class="profile-userpic">
          <img src="/assets/img/avatar.png" class="img-responsive" alt="">
        </div>
        <div class="profile-navlet">
          <div class="profile-navlet-name">
            {{{ $n->hostname }}}
          </div>
          @if ($ip === 'fcec:ae97:8902:d810:6c92:ec67:efb2:3ec5')
          <p class="text-info"><i class="glyphicon glyphicon-asterisk"></i> This node powers Hub!</p>
          @endif
          <div class="navlet-items text-left">
          <div class="profile-navlet-item">
            First Seen: <time class="timeago"
            datetime="{{{ $n->created_at }}}">
            {{{ $n->created_at }}}</time>
          </div>
          <div class="profile-navlet-item">
            Last Seen: <time class="timeago"
            datetime="{{{ $n->updated_at }}}">
            {{{ $n->created_at }}}</time>
          </div>
          <div class="profile-navlet-item">
            Cjdns Version: {{{ $n->version }}}
          </div>
          <div class="profile-navlet-item">
            Latency: {{{ $n->latency }}} ms
          </div>
          </div>
        </div>
@if ($ip === \Req::ip())
  <div class="profile-usermenu">
    <ul class="nav" id="nodeNav" role="tablist">
      <li role="presentation">
        <a href="/nodes/{{{$ip}}}/edit">
          <i class="glyphicon glyphicon-pencil"></i>
          Edit
        </a>
      </li>
      @else
    <div class="text-center">
      <button class="btnf followButton" rel="{{{$ip}}}">Follow</button>
    </div>
    <div class="profile-usermenu">
    <ul class="nav" id="nodeNav" role="tablist">
      @endif

      <li role="presentation" 
      class="{{ !Route::currentRouteNamed('node.view') ? null:'active' }}">
        <a href="/nodes/{{$ip}}" >
        <i class="glyphicon glyphicon-home"></i>
        Overview
      </a>
      </li>
      <li role="presentation" 
      class="{{ !Route::currentRouteNamed('node.activity') ? null:'active' }}">
        <a href="/nodes/{{{$ip}}}/activity">
          <i class="glyphicon glyphicon-list-alt"></i>
          Activity 
          <span class="nav-count">({{{ count($n->activity) }}})</span>
        </a>
      </li>
      <li role="presentation" class="{{ !Route::currentRouteNamed('node.peers') ? null:'active' }}">
        <a href="/nodes/{{$ip}}/peers" >
        <i class="glyphicon glyphicon-user"></i>
        Peers 
        <span class="nav-count">({{{ count($n->peers) }}})</span>
      </a>
      </li>
      <li role="presentation" 
      class="{{ !Route::currentRouteNamed('node.services') ? null:'active' }}">
        <a href="/nodes/{{$ip}}/services">
        <i class="glyphicon glyphicon-ok"></i>
        Services 
        <span class="nav-count">({{{ count($n->services) }}})</span>
      </a>
      </li>
      <li role="presentation" 
      class="{{ !Route::currentRouteNamed('node.followers') ? null:'active' }}">
        <a href="/nodes/{{{$ip}}}/followers">
        <i class="glyphicon glyphicon-star"></i>
        Followers
        <span class="nav-count" id="node-followers">{{{ count($n->followers) }}}</span>
      </a>
      </li>
      <li role="presentation" 
      class="{{ !Route::currentRouteNamed('node.follows') ? null:'active' }}">
        <a href="/nodes/{{{$ip}}}/follows">
        <i class="glyphicon glyphicon-eye-open"></i>
        Follows 
        <span class="nav-count" id="node-follows">{{{ count($n->follows) }}}</span>
      </a>
      </li>
      <li role="presentation" 
      class="{{ !Route::currentRouteNamed('node.comments') ? null:'active' }}">
        <a href="/nodes/{{{$ip}}}/comments">
        <i class="glyphicon glyphicon-comment"></i>
        Comments 
        <span class="nav-count">({{{ count($n->comments) }}})</span>
      </a>
      </li>
      <li role="presentation">
        <a href="/nodes/{{{$ip}}}/activity.rss">
        <i class="fa fa-rss"></i>
        activity.rss
      </a>
      </li>
      <li role="presentation">
        <a href="/nodes/{{{$ip}}}.json">
        <i class="fa fa-exchange"></i>
        nodeinfo.json
      </a>
      </li>
    </ul>
  </div>
</div>
</div>
