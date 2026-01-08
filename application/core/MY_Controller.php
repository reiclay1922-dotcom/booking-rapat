<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

  protected function user() {
    return $this->session->userdata('user');
  }

  protected function require_login() {
    if (!$this->user()) redirect('login');
  }

  protected function require_role($role) {
    $this->require_login();
    $u = $this->user();
    if ($u['role'] !== $role) {
      redirect($u['role'] === 'verifikator' ? 'verifikator' : 'customer/booking');
    }
  }

  protected function render($view, $data = []) {
    $this->load->view('layouts/header', $data);
    $this->load->view('layouts/sidebar', $data);
    $this->load->view($view, $data);
    $this->load->view('layouts/footer', $data);
  }
}
