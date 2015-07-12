@extends('app')

@section('content')

<div class="row">
  
  <div class="col-xs-12">
    
    <div class="panel">
      <div class="panel-body">
        <div class="page-header text-center">
          <h1>Peer Request</h1>
          <p>Send a request to {{$ip}}.</p>
        </div>
        <div class="col-xs-12 col-sm-6 col-sm-offset-3">
        <p class="lead">Send a message to the person you are requesting peering with.</p>
          <div class="panel panel-default">
            <div class="panel-heading clearfix">
              <h3 class="panel-title pull-left">Form Card Title</h3>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" method="POST" action="/api/web/node/{{$ip}}/peer/request.json">
                <div class="form-group">
                  <label class="col-xs-3 control-label">Reason</label>
                  <div class="col-xs-9">
                    <select class="form-control" name="reason">
                      <option value="0" selected>Need more peers</option>
                      <option value="1">Low latency</option>
                      <option value="2">Trust operator</option>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-xs-3 control-label">Request Type</label>
                  <div class="col-xs-9">
                  <label class="radio-inline">
                    <input type="radio" name="rtype" id="rtype0" value="0" checked="checked"> Request details
                  </label>
                  <label class="radio-inline">
                    <input type="radio" name="rtype" id="rtype1" value="1"> Send details
                  </label>
                  </div>  
                </div>
                <div class="form-group fcreds">
                  <label class="col-xs-3 control-label">Peer details</label>
                  <div class="col-xs-9">
                    <textarea class="form-control" name="creds" rows="6"></textarea>
                  </div>
                </div>  
                <div class="form-group">
                  <label class="col-xs-3 control-label">Message</label>
                  <div class="col-xs-9">
                    <textarea class="form-control" name="body" rows="6"></textarea>
                  </div>
                </div>
            </div>
            <div class="panel-footer">
              <div class="btn-group pull-right">
                <button type="reset" class="btn btn-danger">
                  <i class="fa fa-times"></i>
                  Cancel
                </button>
                <button type="submit" class="btn btn-success">
                  <i class="fa fa-check"></i>
                  Send
                </button>
              </div>
            </div>
            </form>
          </div>
        </div>
      </div>
    </div>

  </div>

</div>

@endsection
@section('subjs')
<script type="text/javascript">
  if($('input[name="rtype[]"][value="1"]:checked').length > 0) {

  }
$(document).ready(function()
  {
    $('input[name="rtype[]"]').change(function()
  {
    if($(this).val() == 1)
  {
    $(".fcreds").show();
  }
  else {
    $(".fcreds").hide();
  }
  });
  $(".fcreds").hide();
});

</script>
@endsection