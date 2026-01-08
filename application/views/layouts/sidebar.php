<?php $u = $this->session->userdata('user'); ?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <a href="#" class="brand-link">
    <span class="brand-text font-weight-light">Booking Rapat</span>
  </a>

  <div class="sidebar">
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="info">
        <a href="#" class="d-block"><?= $u ? $u['nama'] : 'Guest'; ?></a>
      </div>
    </div>

    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column">

        <?php if ($u && $u['role'] === 'customer'): ?>
          <li class="nav-item">
            <a href="<?= site_url('customer/booking'); ?>" class="nav-link">
              <i class="nav-icon fas fa-edit"></i><p>Pendaftaran Booking</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= site_url('customer/history'); ?>" class="nav-link">
              <i class="nav-icon fas fa-history"></i><p>Riwayat Booking</p>
            </a>
          </li>
        <?php endif; ?>

        <?php if ($u && $u['role'] === 'verifikator'): ?>
          <li class="nav-item">
            <a href="<?= site_url('verifikator'); ?>" class="nav-link">
              <i class="nav-icon fas fa-check"></i><p>Dashboard Verifikator</p>
            </a>
          </li>
        <?php endif; ?>

      </ul>
    </nav>
  </div>
</aside>

<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <h1><?= isset($page_title) ? $page_title : ''; ?></h1>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
      <?php endif; ?>
      <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
      <?php endif; ?>
