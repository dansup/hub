<?php $this->layout('base::template', ['title' => 'Login']); ?>

<section id="main">
<div class="container">
  <div class="page-header text-center">
  <h2>Login</h2>
  </div>
  <form class="form-horizontal"  method="post">
    <input type="hidden" name="<?= $token_id ?>" value="<?= $token_value ?>" />
    <div class="col-xs-12 col-md-8 col-md-offset-2">

      <div class="form-group well well-sm">
        <label for="<?= $form_names['username'] ?>" class="col-sm-2 control-label">Username</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="<?= $form_names['username'] ?>" name="<?= $form_names['username'] ?>" placeholder="johnsmith" aria-describedby="<?= $form_names['username'] ?>-helpBlock">
        </div>
      </div>
      <div class="form-group well well-sm">
        <label for="<?= $form_names['password'] ?>" class="col-sm-2 control-label">Password</label>
        <div class="col-sm-10">
          <input type="password" class="form-control" id="<?= $form_names['password'] ?>" name="<?= $form_names['password'] ?>" placeholder="●●●●●●●●" aria-describedby="<?= $form_names['password'] ?>-helpBlock">
        </div>
      </div>


    <div class="col-xs-12">
      <div class="form-group">
        <div class="text-center">
          <button type="submit" class="btn btn-primary btn-lg">Login</button>
        </div>
      </div>
    </div>
  </form>


</div>
</section>
