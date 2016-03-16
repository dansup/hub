@extends('layouts.bulma')

@section('content')
<section class="section">
  <div class="container">
    <h1 class="title is-centered">{{ $node->addr }}</h1>
    <h2 class="subtitle is-centered">public key: {{$node->public_key}}</h2>
    <hr>
    <div class="columns">
    @include('layouts.partials.node-sidebar')
    @include('layouts.partials.node-header')
      @if($node->peerCount > 0)
        <table class="table is-bordered is-striped">
            <thead>
                <tr>
                    <th>IPv6/Public Key</th>
                    <th>Version</th>
                    <th>Latency</th>
                    <th>Last Updated</th>
                </tr>
            </thead>
            <tbody>
            @foreach($node->peers()->selectRaw('DISTINCT peer_key')->get() as $peer)
              <tr>
                <th scope="row">
                  <a href="{{ $peer->peerNode->buildNodeUrl(false, true) }}">
                  {{ $peer->peerNode()->first()->addr }}
                  </a>
                  <br>
                  <small class="text-muted">
                  {{ $peer->peerNode()->first()->public_key }}
                  </small>
                </th>
                <td>{{$peer->peerNode->version}}</td>
                <td>{{$peer->peerNode->latency or 'unknown'}}</td>
                <td>{{$peer->peerNode->updated_at->diffForHumans()}}</td>
              </tr>
            @endforeach
            </tbody>
        </table>
      @else
      <div class="notification is-danger">
        <button class="delete"></button>
        We're sorry, this page is not currently available.11
      </div>
      @endif
      </div>
    </div>
  </div>
</section>
@endsection