<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

  public function login() {
    if ($this->session->userdata('user')) {
      $u = $this->session->userdata('user');
      redirect($u['role'] === 'verifikator' ? 'verifikator' : 'customer/booking');
    }

    if ($this->input->method() === 'post') {
      $this->form_validation->set_rules('username', 'Username', 'required');
      $this->form_validation->set_rules('password', 'Password', 'required');

      if ($this->form_validation->run()) {
        $this->load->model('User_model');
        $u = $this->User_model->find_by_username($this->input->post('username', true));

        if ($u && password_verify($this->input->post('password'), $u->password_hash)) {
          $this->session->set_userdata('user', [
            'id' => $u->id,
            'nama' => $u->nama,
            'role' => $u->role
          ]);
          redirect($u->role === 'verifikator' ? 'verifikator' : 'customer/booking');
        } else {
          $data['error'] = 'Username / password salah.';
        }
      }
    }

    $this->load->view('auth/login', isset($data) ? $data : []);
  }

  public function logout() {
    $this->session->unset_userdata('user');
    redirect('login');
  }
}
