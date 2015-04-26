<?php $this->layout('base::template', ['title' => 'Create a MeshLocal']); ?>

<section id="main">
<div class="container">
  <div class="page-header text-center">
  <h2>Create a MeshLocal</h2>
  </div>
  <form class="form-horizontal"  method="post">
    <input type="hidden" name="<?= $token_id ?>" value="<?= $token_value ?>" />
    <div class="col-xs-12 col-md-8 col-md-offset-2">

      <div class="form-group well well-sm">
        <label for="<?= $form_names['name'] ?>" class="col-sm-2 control-label">Name</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="<?= $form_names['name'] ?>" name="<?= $form_names['name'] ?>" placeholder="Seattle Meshnet" aria-describedby="<?= $form_names['name'] ?>-helpBlock">
          <span id="<?= $form_names['name'] ?>-helpBlock" class="help-block">A name for your meshlocal. It should be unique.</span>
        </div>
      </div>
      <div class="form-group well well-sm">
        <label for="<?= $form_names['bio'] ?>" class="col-sm-2 control-label">Bio</label>
        <div class="col-sm-10">
          <textarea class="form-control" id="<?= $form_names['bio'] ?>" name="<?= $form_names['bio'] ?>" placeholder="A brief description here. (500 chars max)" aria-describedby="<?= $form_names['bio'] ?>-helpBlock"></textarea>
          <span id="<?= $form_names['bio'] ?>-helpBlock" class="help-block">A description of your meshlocal.</span>
        </div>
      </div>


    <div class="col-xs-12">
      <div class="form-group">
        <div class="text-center">
          <button type="submit" class="btn btn-primary">Create</button>
        </div>
      </div>
    </div>
  </form>


</div>
</section>
