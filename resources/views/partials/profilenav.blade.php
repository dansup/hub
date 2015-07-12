<div class="profile-usermenu">
  <ul class="nav" id="nodeNav" role="tablist">
    @if ($ip === \Req::ip())
      <li role="presentation">
        <a href="/node/{{{$ip}}}/edit">
          <i class="glyphicon glyphicon-pencil"></i>
          Edit
        </a>
      </li>
    @else
      <div class="text-center">
        <button class="btnf followButton" rel="{{{$ip}}}">Follow</button>
      </div>
    @endif
      <li role="presentation" class="{{ !Route::currentRouteNamed('node.view') ? :'active' }}">
        <a href="/node/{{$ip}}" >
          <i class="glyphicon glyphicon-home"></i>
          Overview
        </a>
      </li>
      <li role="presentation" class="{{ !Route::currentRouteNamed('node.activity') ? :'active' }}">
        <a href="/node/{{{$ip}}}/activity">
          <i class="glyphicon glyphicon-list-alt"></i>
          Activity <span class="nav-count">({{{ count($n->activity) }}})</span>
        </a>
      </li>
      <li role="presentation"class="{{ !Route::currentRouteNamed('node.peers') ? :'active' }}">
        <a href="/node/{{$ip}}/peers" >
          <i class="glyphicon glyphicon-user"></i>
          Peers <span class="nav-count">({{{ count($n->peers) }}})</span>
        </a>
      </li>
      <li role="presentation" class="{{ !Route::currentRouteNamed('node.services') ? :'active' }}">
        <a href="/node/{{$ip}}/services">
          <i class="glyphicon glyphicon-ok"></i>
          Services <span class="nav-count">({{{ count($n->services) }}})</span>
        </a>
      </li>
      <li role="presentation" class="{{ !Route::currentRouteNamed('node.comments') ? :'active' }}">
        <a href="/node/{{{$ip}}}/comments">
          <i class="glyphicon glyphicon-comment"></i>
          Comments <span class="nav-count">({{{ count($n->comments) }}})</span>
        </a>
      </li>
      <li role="presentation">
        <a href="/node/{{{$ip}}}/activity.rss">
          <i class="fa fa-rss"></i>
          Activity.rss
        </a>
      </li>
      <li role="presentation">
        <a href="/node/{{{$ip}}}.json">
          <i class="fa fa-exchange"></i>
          nodeinfo.json
        </a>
      </li>
    </ul>
  </div>
</div>