@extends('layouts.bulma')

@section('content')
<section class="hero">
  <div class="hero-content">
    <div class="container">
      <h1 class="title">
        Hyperboria Network Insight
      </h1>
      <h2 class="subtitle">
        We monitor the network using the cjdns admin API to gain insight into nodes, peers and services.
      </h2>
    <hr>
    </div>
  </div>
</section>
<section class="section">
<div class="content" style="padding-top:30px;">
  <div class="container">
    <div class="columns">
      <div class="column is-8">
        <div>
          <div>
            <div class="page-header">
              <h3 class="is-3">Recently updated nodes</h3>
            </div>
            @if ($nodes->count() > 0)
            <table class="table"> 
              <thead> 
                <tr> 
                  <th>IPv6 Address</th> 
                  <th>Latency</th> 
                  <th>Age</th> 
                  <th>Last seen</th> 
                </tr> 
              </thead> 
              <tbody> 
                @foreach ($nodes as $node)
                <tr> 
                  <th scope="row">
                  @if(empty($node->addr) == true)
                    <a href="{{ $node->buildNodeUrl(true) }}">{{ str_limit($node->public_key, 36) }}</a>
                  @else
                    <a href="{{ $node->buildNodeUrl() }}">{{ $node->addr }}</a>
                  @endif
                  </th> 
                  <td>{{ $node->latency }}ms</td> 
                  <td>{{ $node->age($node, true) }}</td> 
                  <td>{{ $node->updated_at->diffForHumans() }}</td> 
                </tr> 
                @endforeach
              </tbody> 
            </table>
            @else
            <p>No results</p>
            @endif
          </div>
          <div style="padding:80px 0;">
            <div class="page-header">
              <h3>Recently updated services</h3>
            </div>
          </div>
        </div>
      </div>
      <div class="column is-4">
        <div class="page-header">
          <h3>Site Stats</h3>
        </div>    
          <ul>
            <li>2452 known nodes</li>              
            <li>965 nodes seen > 1 hr</li>              
            <li>1365 nodes seen > 1 day</li>              
            <li>Avg node version: 17</li>              
            <li>Avg node peers: 3.14</li>              
            <li>Avg node age: 3 months</li>              
          </ul>   
      </div>
    </div>
  </div>
</div>
</section>

  @endsection
