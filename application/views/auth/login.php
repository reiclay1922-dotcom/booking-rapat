<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login</title>
  <link rel="stylesheet" href="<?= base_url('assets/adminlte/plugins/fontawesome-free/css/all.min.css'); ?>">
  <link rel="stylesheet" href="<?= base_url('assets/adminlte/dist/css/adminlte.min.css'); ?>">
</head>

<body class="hold-transition login-page">

  <div class="login-box">
    <div class="card card-outline card-primary">
      <div class="card-header text-center">
        <b>Booking Rapat</b>
      </div>
      <div class="card-body">
        <p class="login-box-msg">Login untuk masuk</p>

        <?php if (!empty($error)): ?>
          <div class="alert alert-danger"><?= $error; ?></div>
        <?php endif; ?>
        <?= validation_errors('<div class="alert alert-danger">', '</div>'); ?>

        <form action="<?= site_url('login'); ?>" method="post">
          <?php
          $csrf_name = $this->security->get_csrf_token_name();
          $csrf_hash = $this->security->get_csrf_hash();
          ?>
          <input type="hidden" name="<?= $csrf_name; ?>" value="<?= $csrf_hash; ?>">

          <div class="input-group mb-3">
            <input type="text" name="username" class="form-control" placeholder="Username" required>
            <div class="input-group-append">
              <div class="input-group-text"><span class="fas fa-user"></span></div>
            </div>
          </div>

          <div class="input-group mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password" required>
            <div class="input-group-append">
              <div class="input-group-text"><span class="fas fa-lock"></span></div>
            </div>
          </div>

          <button type="submit" class="btn btn-primary btn-block">Login</button>
        </form>

        <hr>
        <small class="text-muted">
          Contoh akun:<br>
          verifikator / admin123<br>
          customer1 / 123456
        </small>
      </div>
    </div>
  </div>

  <script src="<?= base_url('assets/adminlte/plugins/jquery/jquery.min.js'); ?>"></script>
  <script src="<?= base_url('assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
  <script src="<?= base_url('assets/adminlte/dist/js/adminlte.min.js'); ?>"></script>
</body>

</html>