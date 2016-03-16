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

<div class="example">
    @if( Auth::check() == true)
      <article class="media">
        <figure class="media-left">
          <p class="image is-64x64">
            <img src="http://placehold.it/128x128">
          </p>
        </figure>
        <div class="media-content">
        <form action="/node/ip/{{$node->addr}}/status/post" method="post">
        {!! csrf_field() !!}
          <p class="control">
            <textarea class="textarea" name="body" placeholder="Add a comment..."></textarea>
          </p>
          <p class="control">
            <button type="submit" class="button is-primary">Post comment</button>
          </p>
        </form>
        </div>
      </article>
      @else
      <div class="notification is-primary">
        <button class="delete"></button>
        Login to post a comment to {{ $node->addr}}'s wall.
      </div>
      @endif
    @if( $comments->count() > 0)
      @foreach( $comments as $comment)
      <article class="media">
        <figure class="media-left">
          <p class="image is-64x64">
            <img src="http://placehold.it/128x128">
          </p>
        </figure>
        <div class="media-content">
          <div class="content">
            <p>
              <strong><a href="/node/ip/{{$comment->user->ipv6}}">
              {{ $comment->user->ipv6 }}
              @if( $comment->user->ipv6 == $node->addr)
               <i class="fa fa-certificate icon is-small"></i>
              @endif
              </a>
              </strong>
              <br>
              {{$comment->body}}
              <br>
              <small>{{$comment->created_at->diffForHumans()}}</small>
            </p>
          </div>
        </div>
      </article>
      @endforeach
      <hr>
      <p class="is-centered">
        
      @if($comments->previousPageUrl())
        <a class="button" href="{{$comments->previousPageUrl()}}">Back</a>
      @endif
      @if($comments->hasMorePages())
        <a class="button" href="{{$comments->nextPageUrl()}}">Next</a>
      @endif
      </p>
    @endif
    </div>
      </div>
    </div>
  </div>
</section>
@endsection