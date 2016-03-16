        <nav class="navbar">
          <div class="navbar-item is-centered">
            <p class="heading">Peers</p>
            <p class="title">{{$node->peerCount}}</p>
          </div>
          <div class="navbar-item is-centered">
            <p class="heading">Services</p>
            <p class="title">{{$node->serviceCount}}</p>
          </div>
          <div class="navbar-item is-centered">
            <p class="heading">Abuse Reports</p>
            <p class="title">{{$node->abuseCount}}</p>
          </div>
          <div class="navbar-item is-centered">
            <p class="heading">WoT Score</p>
            <p class="title">{{$node->wotScore}}</p>
          </div>
        </nav>
        <div class="tabs">
          <ul>
            <li class="{{request()->is('node/ip/'.$node->addr)?'is-active':null}}"><a href="/node/ip/{{$node->addr}}">Wall</a></li>
            <li class="{{request()->is('node/ip/'.$node->addr.'/services')?'is-active':null}}"><a href="/node/ip/{{$node->addr}}/services">Services</a></li>
            <li class="{{request()->is('node/ip/'.$node->addr.'/peers')?'is-active':null}}"><a href="/node/ip/{{$node->addr}}/peers">Peers</a></li>
            <li class="{{request()->is('node/ip/'.$node->addr.'/peer-graph')?'is-active':null}}"><a href="/node/ip/{{$node->addr}}/peer-graph">Peer Graph</a></li>
            <li class="{{request()->is('node/ip/'.$node->addr.'/abuse-reports')?'is-active':null}}"><a href="/node/ip/{{$node->addr}}/abuse-reports">Abuse Reports</a></li>
            <li class="{{request()->is('node/ip/'.$node->addr.'/nodeinfo')?'is-active':null}}"><a href="/node/ip/{{$node->addr}}/nodeinfo">NodeInfo</a></li>
            <li class="{{request()->is('node/ip/'.$node->addr.'/wot')?'is-active':null}}"><a href="/node/ip/{{$node->addr}}/wot">WoT</a></li>
            <li class="{{request()->is('node/ip/'.$node->addr.'/stats')?'is-active':null}}"><a href="/node/ip/{{$node->addr}}/stats">Stats</a></li>
            <li><a href="/node/ip/{{$node->addr}}/feed.rss"><i class="fa fa-rss"></i></a></li>
          </ul>
        </div>