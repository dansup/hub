      <div class="column is-3">
        <nav class="menu">
          <p class="menu-heading">
            NodeInfo
          </p>
          <p class="menu-tabs">
            <a class="is-active" href="#">Widget</a>
            <a href="#">API</a>
            <a href="#">RSS Feed</a>
          </p>
          <div class="menu-block">
            <span class="menu-icon">
              <i class="fa fa-list"></i>
            </span>
            owner: <strong>unknown</strong>
          </div>
          <div class="menu-block">
            <span class="menu-icon">
              <i class="fa fa-list"></i>
            </span>
            last crawled: <strong>{{ $node->updated_at->diffForHumans() }}</strong>
          </div>
          <div class="menu-block">
            <span class="menu-icon">
              <i class="fa fa-list"></i>
            </span>
            first crawled: <strong>{{ $node->created_at->diffForHumans() }}</strong>
          </div>
          <div class="menu-block">
            <span class="menu-icon">
              <i class="fa fa-list"></i>
            </span>
            cjdns version: <strong>{{ $node->version or 'unknown' }}</strong>
          </div>
          <div class="menu-block">
            <span class="menu-icon">
              <i class="fa fa-globe"></i>
            </span>
            city: <strong>{{ $node->city or 'unknown' }}</strong>
          </div>
          <div class="menu-block">
            <span class="menu-icon">
              <i class="fa fa-globe"></i>
            </span>
            country: <strong>{{ $node->country or 'unknown' }}</strong>
          </div>
          <div class="menu-block">
            <a href="{{$node->buildNodeUrl()}}/request/peer" class="button is-default is-outlined is-fullwidth">
              Request Peering
            </a><br>
            <a href="{{$node->buildNodeUrl()}}/report/abuse" class="button is-danger is-outlined is-fullwidth">
              Report Abuse
            </a>
          </div>
        </nav>
      </div>
      <div class="column is-9">