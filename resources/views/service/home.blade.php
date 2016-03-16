@extends('layouts.bulma')

@section('content')
<section class="section">
  <div class="container">
    <h1 class="title is-centered">Service List</h1>
    <h2 class="subtitle is-centered">A list of services on hyperboria. <a href="/service/add">Add yours</a> today!</h2>
    <hr>
    <div class="columns">
      <div class="column">

        @if( $services->count() > 0)
        <table class="table is-striped is-bordered">
          <thead>
            <tr>
              <th></th>
              <th>Open source projects</th>
              <th>Year started</th>
              <th colspan="3">Links</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <th></th>
              <th>Open source projects</th>
              <th>Year started</th>
              <th colspan="3">Links</th>
            </tr>
          </tfoot>
          <tbody>

            @foreach( $services as $service)
            <tr>
              <td class="table-icon">
              <i class="{{$service->getIcon()}}"></i>
              </td>
              <td class="table-link">
                <a href="{{$service->url}}" target="_blank">{{$service->name}}</a>
              </td>
              <td>
                2003
              </td>
              <td class="table-link table-icon">
                <a href="#">
                  <i class="fa fa-github"></i>
                </a>
              </td>
              <td class="table-link table-icon">
                <a href="#">
                  <i class="fa fa-twitter"></i>
                </a>
              </td>
              <td class="table-link table-icon">
                <a href="#">
                  <i class="fa fa-globe"></i>
                </a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
        @endif
      </div>
    </div>
  </div>
</div>
</section>
@endsection