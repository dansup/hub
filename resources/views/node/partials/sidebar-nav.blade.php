<div class="profile-sidebar">
  <div class="profile-userpic">
    <img src="/assets/{{ ($n->avatar_hash == null ) ? 'img/avatar.png' : 'avatars/'.$n->avatar_hash }}" class="img-responsive " alt="">
  </div>

  <div class="profile-navlet">
    <div class="profile-navlet-name"> {{{ $n->hostname }}} </div>

    @if ($ip === 'fcec:ae97:8902:d810:6c92:ec67:efb2:3ec5')
    <p class="text-info"><i class="glyphicon glyphicon-asterisk"></i> This node powers Hub!</p>
    @endif

</div>

<div class="profile-usermenu">
  <ul class="nav" id="nodeNav" role="tablist">
    @if ($ip === !\Req::ip())
    <div class="text-center">
      <button class="btnf followButton" rel="{{{$ip}}}">Follow</button>
    </div>
    @endif
    <div style="padding-top:40px;"></div>
    <li role="presentation" class="{{ !Route::currentRouteNamed('node.view') ? null : 'active' }}">
      <a href="/node/{{$ip}}" >
        <i class="glyphicon glyphicon-home"></i>
        Overview
      </a>
    </li>

    <li role="presentation" class="{{ !Route::currentRouteNamed('node.activity') ? null : 'active' }}">
      <a href="/node/{{{$ip}}}/activity">
        <i class="glyphicon glyphicon-list-alt"></i>
        Activity
        <span class="nav-count">({{{ count($n->activity) }}})</span>
      </a>
    </li>

    <li role="presentation" class="{{ !Route::currentRouteNamed('node.peers') ? null : 'active' }}">
      <a href="/node/{{$ip}}/peers" >
        <i class="glyphicon glyphicon-user"></i>
        Peers
        <span class="nav-count">({{{ count($n->peers) }}})</span>
      </a>
    </li>

    <li role="presentation">
      <a href="#!/node/{{{$ip}}}/nodestats">
        <i class="fa fa-database"></i>
        NodeStats
        <span class="nav-count">({{{ count($n->peers) }}})</span>
      </a>
    </li>

    <li role="presentation" class="{{ !Route::currentRouteNamed('node.services') ? null : 'active' }}">
      <a href="/node/{{$ip}}/services">
        <i class="glyphicon glyphicon-ok"></i>
        Services
        <span class="nav-count">({{{ count($n->services) }}})</span>
      </a>
    </li>

    <li role="presentation" class="{{ !Route::currentRouteNamed('node.followers') ? null : 'active' }}">
      <a href="/node/{{{$ip}}}/followers">
        <i class="glyphicon glyphicon-star"></i>
        Followers
        <span class="nav-count" id="node-followers">{{{ count($n->followers) }}}</span>
      </a>
    </li>

    <li role="presentation" class="{{ !Route::currentRouteNamed('node.follows') ? null : 'active' }}">
      <a href="/node/{{{$ip}}}/follows">
        <i class="glyphicon glyphicon-eye-open"></i>
        Follows
        <span class="nav-count" id="node-follows">{{{ count($n->follows) }}}</span>
      </a>
    </li>

    <li role="presentation" class="{{ !Route::currentRouteNamed('node.comments') ? null : 'active' }}">
      <a href="/node/{{{$ip}}}/comments">
        <i class="glyphicon glyphicon-comment"></i>
        Comments
        <span class="nav-count">({{{ count($n->comments) }}})</span>
      </a>
    </li>

    <li role="presentation">
      <a href="/node/{{{$ip}}}/activity.rss">
        <i class="fa fa-rss-square"></i>
        Activity Feed
        <span class="nav-count">(RSS)</span>
      </a>
    </li>

    <li role="presentation">
      <a href="/node/{{{$ip}}}.json">
        <i class="fa fa-exchange"></i>
        NodeInfo Object
        <span class="nav-count">(JSON)</span>
      </a>
    </li>

  </ul>
</div>
</div>
