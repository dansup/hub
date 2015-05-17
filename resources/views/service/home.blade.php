@extends('app')

@section('content')

<div class="col-xs-12 col-md-10 col-md-offset-1">
<table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>Name</th>
          <th>First Seen</th>
          <th>Last Seen</th>
        </tr>
      </thead>
      <tbody>
    <?php foreach ($services as $s): ?>
        <tr>
          <th scope="row"><a href="/services/{{{ $s->id }}}">{{{ $s->name }}}</a></th>
          <td><time class="timeago" datetime="{{{ $s->created_at }}}">{{{ $s->created_at }}}</time></td>
          <td><time class="timeago" datetime="{{{ $s->updated_at }}}">{{{ $s->updated_at }}}</time></td>
        </tr>
        <tr>
    <?php endforeach; ?>
    <?= $services->render() ?>
      
      </tbody>
    </table>
</div>

@endsection