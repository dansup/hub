@extends('layouts.bulma')

@section('content')
<section class="section">
  <div class="container">
    <h1 class="title is-centered">Browse Nodes</h1>
    <h2 class="subtitle is-centered"></h2>
    <hr>
    <div class="columns">
        <div class="column">
            @if($nodes->count() > 0)
            <table class="table is-bordered is-striped">
                <thead>
                    <tr>
                        <th>IPv6/Public Key</th>
                        <th>Peer Count</th>
                        <th>Service Count</th>
                        <th>Version</th>
                        <th>Wot Score</th>
                        <th>Last Updated</th>
                    </tr>
                </thead>
                <tbody>
                  @foreach( $nodes as $node )
                  <tr>
                    <th scope="row"><a href="{{ $node->buildNodeUrl() }}">{{ $node->addr }}</a><br><small class="text-muted">{{ $node->public_key }}</small></th>
                    <td class="is-centered">
                    <strong>
                    {{ $node->peerCount or 'unknown' }}
                    </strong>
                    </td>
                    <td class="is-centered">
                    <strong>
                    {{ $node->serviceCount or 'unknown' }}
                    </strong>
                    </td>
                    <td class="is-centered">
                    <strong>
                    {{ $node->version or 'unknown' }}
                    </strong>
                    </td>
                    <td class="is-centered">
                    <strong>
                    {{ $node->wotScore or 'unknown' }}
                    </strong>
                    </td>
                    <td>{{ $node->updated_at->diffForHumans() }}</td>
                 </tr>
                @endforeach
                </tbody>
            </table>
            <p class="is-centered">
              @if($nodes->previousPageUrl())
              <a class="button" href="{{$nodes->previousPageUrl()}}">Back</a>
              @endif
              @if($nodes->hasMorePages())
              <a class="button is-primary is-outlined" href="{{$nodes->nextPageUrl()}}">Next</a>
              @endif
          </p>
          @endif
      </div>
    </div>
</div>
@endsection
