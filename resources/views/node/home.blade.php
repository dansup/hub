@extends('app')

@section('content')

<div class="container">
<table class="table table-striped">
      <thead>
        <tr>
          <th>IPv6 Address</th>
          <th>Version</th>
          <th>First Seen</th>
          <th>Last Seen</th>
        </tr>
      </thead>
      <tbody>
    <?php foreach ($nodes as $n): ?>
        <tr>
          <th scope="row"><a href="/nodes/{{{ $n->addr }}}">{{{ $n->addr }}}</a></th>
          <td>{{{ $n->version }}}</td>
          <td><time class="timeago" datetime="{{{ $n->first_seen }}}">{{{ $n->first_seen }}}</time></td>
          <td><time class="timeago" datetime="{{{ $n->last_seen }}}">{{{ $n->last_seen }}}</time></td>
        </tr>
        <tr>
    <?php endforeach; ?>
    <?= $nodes->render() ?>
      
      </tbody>
    </table>
</div>

@endsection