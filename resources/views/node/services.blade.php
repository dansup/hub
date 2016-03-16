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

      @if ($services->count() > 0)
        <div class="columns">
        @foreach($services as $service)
          <div class="column">
            
          <div class="card">
            <div class="card-content">
              <div class="media">
                <figure class="media-image is-40">
                  <img src="http://placehold.it/40x40" alt="Image">
                </figure>
                <div class="media-content">
                  <p class="title is-5">{{$service->name}}</p>
                  <p class="subtitle is-6"><a href="{{$service->url}}">{{$service->url}}</a>
                  <br>
                  <strong>
                  {{$service->service_type}}
                  </strong>
                  </p>
                </div>
              </div>

              <div class="content">
                {{ $service->description }}
                <br>
                <small>added {{$service->created_at->diffForHumans()}}</small>
              </div>
            </div>
          </div>
          </div>
        @endforeach
        </div>
      @endif

      </div>
    </div>
  </div>
</section>
@endsection