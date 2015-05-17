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
            <div class="row">


            </div>
            <div class="col-xs-12 divider tp-20"></div>
            <div class="col-md-6 statuslet">
              <div >
                <div>
                  <h4 class="text-center">Comments</h4>
                  <form method="post" action="/nodes/{{{ $n->addr }}}/comment/add">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="caid" value="{{ sha1(csrf_token().\App\Hub\Req::ip()) }}">
                    <input type="hidden" name="ct" value="{{ sha1(csrf_token().time().'App\Node') }}">
                    <input type="hidden" name="cid" value="{{ $n->addr }}">
                    <div class="input-group">
                      <input type="text" name="body" id="userComment" class="form-control input-sm chat-input" placeholder="Write your message here..." />
                      <span class="input-group-btn">     
                        <button type="submit" class="btn btn-default btn-sm"><span class="fa fa-comment"></span> Add Status</submit>
                        </span>
                      </div>
                    </form>
                    <hr>
                    <ul class="media-list list-group">
                      @if ( count($n->comments) > 0 )
                      @foreach ($n->last3Comments as $c)
                      <div class="[ panel panel-default ] panel-google-plus">
                              {{--<div class="dropdown">
                              <span class="dropdown-toggle" type="button" data-toggle="dropdown">
                                  <span class="[ glyphicon glyphicon-chevron-down ]"></span>
                              </span>
                              <ul class="dropdown-menu" role="menu">
                                  <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Action</a></li>
                                  <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Another action</a></li>
                                  <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Something else here</a></li>
                                  <li role="presentation" class="divider"></li>
                                  <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Separated link</a></li>
                              </ul>
                            </div> --}}
                                {{--<div class="panel-google-plus-tags">
                              <ul>
                                  <li>#Millennials</li>
                                  <li>#Generation</li>
                              </ul>
                            </div> --}}
                            <div class="panel-heading">
                              <img class="[ img-circle pull-left ]" src="/assets/img/avatar.png" width="46px" alt="{{{ (!empty($c->node->hostname)) ? $c->node->hostname : $c->author_addr }}}" />
                              <h3><a href="/nodes/{{{$c->author_addr}}}">{{{ (!empty($c->node->hostname)) ? $c->node->hostname : substr($c->author_addr, 0, 12).'...' }}}</a></h3>
                              <h5><span>via Web</span> - <span><time class="timeago" datetime="{{{$c->created_at}}}">{{{$c->created_at}}}</time></span> </h5>
                            </div>
                            <div class="panel-body">
                              <p>{{{ $c->body }}}</p>
                            </div>
                            <div class="panel-footer">
                              <button type="button" class="[ btn btn-default ]">+1</button>
                              <button type="button" class="[ btn btn-default ]">
                                <span class="[ glyphicon glyphicon-share-alt ]"></span>
                              </button>
                              <div class="input-placeholder">Add a comment...</div>
                            </div>
                            <div class="panel-google-plus-comment">
                              <img class="img-circle" src="/assets/img/avatar.png" width="46px" alt="User Image" />
                              <div class="panel-google-plus-textarea">
                                <textarea rows="3"></textarea>
                                <button type="submit" class="[ btn btn-success disabled ]">Post comment</button>
                                <button type="reset" class="[ btn btn-default ]">Cancel</button>
                              </div>
                              <div class="clearfix"></div>
                            </div>
                          </div> 
                          @endforeach
                          @else
                          <div class="alert alert-info text-center">
                            <p class="lead">No comments yet!</p>
                            <p>Why not be the first ?</p>
                          </div>
                          @endif
                        </ul>
                      </div>
                    </div>
                  </div>

                  <div class="col-xs-12 col-sm-6 feedlet">
                    <h4 class="text-center">Activity</h4>
                    @if (count($n->activity) > 0)
                    <ul class="timeline">
                      @foreach ($n->last5Activity as $act)
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
                      @endforeach
                    </ul>
                    @else
                    <div class="alert alert-info">
                      <p class="lead text-center">No activity yet!</p>
                    </div>
                    @endif
                  </div>

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