<?php $this->layout('base::template', ['title' => 'Socialnode Join']); ?>

<section id="main">
<div class="container">
  <div class="page-header text-center">
  <h2>Register an Account via Socialnode</h2>
  </div>
  <form class="form-horizontal"  method="post">
    <input type="hidden" name="<?= $token_id ?>" value="<?= $token_value ?>" />
    <div class="col-xs-12 col-md-8 col-md-offset-2">

      <div class="form-group well well-sm">
        <label for="" class="col-sm-2 control-label">Username</label>
        <div class="col-sm-10">
          <p class="lead"><?= $username ?></p>
        </div>
      </div>
      <div class="form-group well well-sm">
        <label for="<?= $form_names['email'] ?>" class="col-sm-2 control-label">Email</label>
        <div class="col-sm-10">
          <input type="email" class="form-control" id="<?= $form_names['email'] ?>" name="<?= $form_names['email'] ?>" placeholder="johnsmith@gmail.com" aria-describedby="<?= $form_names['email'] ?>-helpBlock">
          <span id="<?= $form_names['email'] ?>-helpBlock" class="help-block">A valid email.</span>
        </div>
      </div>
      <div class="form-group well well-sm">
        <label for="<?= $form_names['password'] ?>" class="col-sm-2 control-label">Password</label>
        <div class="col-sm-10">
          <input type="password" class="form-control" id="<?= $form_names['password'] ?>" name="<?= $form_names['password'] ?>" placeholder="●●●●●●●●" aria-describedby="<?= $form_names['password'] ?>-helpBlock">
          <span id="<?= $form_names['password'] ?>-helpBlock" class="help-block">A secure password.</span>
        </div>
      </div>
      <div class="form-group well well-sm">
        <label for="<?= $form_names['password_confirm'] ?>" class="col-sm-2 control-label">Password Confirm</label>
        <div class="col-sm-10">
          <input type="password" class="form-control" id="<?= $form_names['password_confirm'] ?>" name="<?= $form_names['password_confirm'] ?>" placeholder="●●●●●●●●" aria-describedby="<?= $form_names['password_confirm'] ?>-helpBlock">
          <span id="<?= $form_names['password_confirm'] ?>-helpBlock" class="help-block">Re-enter your password to confirm.</span>
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
