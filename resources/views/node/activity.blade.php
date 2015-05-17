@extends('profile')


@section('content')

<div class="row profile">
  <div role="tabpanel">
    <div class="col-md-3">
      @include('node.partials.sidebar-nav', [
        'ip' => $n->addr
        ])
        <div class="col-md-9">
          @include('node.partials.content-header')
          <div class="profile-content active">
            <div class="col-xs-12 col-md-8 col-md-offset-2">
              <div class="page-header text-center">
                <h2>Activity</h2>
              </div>
              <?php if(count($activity) > 1): ?>
              <ul class="timeline full-activity">
                <?php foreach ($activity as $act):  ?>
                <?php $icon = snake_case(str_replace(' ', '', $act['action'])); ?>
                <li>
                  <div class="timeline-badge {{{ $icon }}}"><i class="fa act-icon {{{ $icon }}}"></i></div>
                  <div class="timeline-panel">
                    <div class="timeline-heading">
                      <h4 class="timeline-title">{{{ $act['description'] }}}</h4>
                      <p><small class="text-muted"><i class="fa fa-clock-o"></i> <time class="timeago" datetime="<?= $act['created_at'] ?>"></time> via {{{ $act['source'] }}}</small></p>
                    </div>
                    <div class="timeline-body">
                      <p><?= $act['details'] ?></p>
                    </div>
                  </div>
                </li>
              <?php endforeach; ?>
            </ul>
            <?= $activity->render() ?>
          <?php else: ?>
          <p class="lead text-center">No activity available.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
</div>

@section('subjs')
<script type="text/javascript">
$('.timeline-panel').click(function() {
$('.timeline-body', this).toggle(); // p00f
});

</script>
@stop

@endsection