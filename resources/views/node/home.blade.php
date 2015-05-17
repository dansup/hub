@extends('app')

@section('content')
  @if($errors->has())
    <div class="col-xs-12 col-sm-10 col-sm-offset-1">
     @foreach ($errors->all() as $error)
      <div class="alert alert-info">
        <p class="alert-text lead">{{ $error }}</p>
      </div>
    @endforeach
    </div>
  @endif
  <div class="col-xs-12 col-md-10 col-md-offset-1">
    <div class="col-xs-12 col-sm-8">
      <?= $nodes->render() ?>
    </div>
    <div class="col-xs-12 col-sm-4">
     <p class="text-right">
     <a class="btn btn-default" href="/nodes/me">My Node</a>
     <a class="btn btn-default" href="/nodes/create">Add Node</a>
     </p>
    </div>
    <div class="alert alert-info" style="display:block;margin-top:60px;">
      <p class="alert-text">View <a href="http://dev.hub.hyperboria.net/nodes/fcec:ae97:8902:d810:6c92:ec67:efb2:3ec5" style="font-weight:600;">an example</a> of a node w/ activity</p>
    </div>
  <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>IPv6 Address</th>
          <th>Hostname</th>
          <th>First Seen</th>
          <th>Last Seen</th>
          <th>Latency</th>
          <th>Version</th>
        </tr>
      </thead>
      <tbody>
    <?php foreach ($nodes as $n): ?>
        <tr>
          <th scope="row"><a href="/nodes/{{{ $n->addr }}}" class="ipv6">{{{ $n->addr }}}</a></th>
          <td>{{{ $n->hostname }}}</td>
          <td><time class="timeago" datetime="{{{ $n->created_at }}}">{{{ $n->created_at }}}</time></td>
          <td><time class="timeago" datetime="{{{ $n->updated_at }}}">{{{ $n->updated_at }}}</time></td>
          <td><strong>{{{ $n->latency }}}</strong> ms</td>
          <td>v<strong>{{{ $n->version }}}</strong></td>
        </tr>
        <tr>
    <?php endforeach; ?>
      
      </tbody>
    </table>
    <?= $nodes->render() ?>
</div>

@endsection