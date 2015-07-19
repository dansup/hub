@extends('app')

@section('content')

<div class="row">
<div class="col-xs-12 col-md-6 col-md-offset-3">
<div class="panel panel-default">
  <div class="panel-heading clearfix">
    <h3 class="panel-title pull-left">Register</h3>
    <div class="btn-group pull-right">
      <button class="btn btn-danger">
        <i class="fa fa-times"></i>
        Cancel
      </button>
      <button class="btn btn-success">
        <i class="fa fa-check"></i>
        Save
      </button>
    </div>
  </div>
  <div class="modal-body">
    <form class="form-horizontal">
      <div class="form-group">
        <label class="col-xs-3 control-label">Name</label>
        <div class="col-xs-9">
          <input type="text" class="form-control" value="John Smith">
        </div>
      </div>
      <div class="form-group">
        <label class="col-xs-3 control-label">Description</label>
        <div class="col-xs-9">
          <textarea class="form-control" rows="6">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam mauris tellus, vehicula ut tellus id, suscipit dapibus tortor. Integer viverra turpis ac fringilla hendrerit. Sed faucibus posuere felis et pellentesque. Cras varius tortor vitae molestie tempor. Proin ut viverra elit, ac gravida tortor.</textarea>
        </div>
      </div>
    </form>
  </div>
</div>

</div>
</div>

@endsection