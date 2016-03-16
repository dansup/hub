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

      <div class="notification is-danger">
        <button class="delete"></button>
        We're sorry, this page is not currently available.
      </div>
      </div>
    </div>
  </div>
</section>
@endsection