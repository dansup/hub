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

            <li role="presentation" class="{{ !Route::currentRouteNamed('node.view') ? :'active' }}">
              <a href="/nodes/{{$ip}}" >
              <i class="glyphicon glyphicon-home"></i>
              Overview
            </a>
            </li>
            <li role="presentation" class="{{ !Route::currentRouteNamed('node.activity') ? :'active' }}">
              <a href="/nodes/{{{$ip}}}/activity">
                <i class="glyphicon glyphicon-list-alt"></i>
                Activity <span class="nav-count">({{{ count($n->activity) }}})</span>
              </a>
            </li>
            <li role="presentation"class="{{ !Route::currentRouteNamed('node.peers') ? :'active' }}">
              <a href="/nodes/{{$ip}}/peers" >
              <i class="glyphicon glyphicon-user"></i>
              Peers <span class="nav-count">({{{ count($n->peers) }}})</span>
            </a>
            </li>
            <li role="presentation" class="{{ !Route::currentRouteNamed('node.services') ? :'active' }}">
              <a href="/nodes/{{$ip}}/services">
              <i class="glyphicon glyphicon-ok"></i>
              Services <span class="nav-count">({{{ count($n->services) }}})</span>
            </a>
            </li>
            <li role="presentation" class="{{ !Route::currentRouteNamed('node.comments') ? :'active' }}">
              <a href="/nodes/{{{$ip}}}/comments">
              <i class="glyphicon glyphicon-comment"></i>
              Comments <span class="nav-count">({{{ count($n->comments) }}})</span>
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