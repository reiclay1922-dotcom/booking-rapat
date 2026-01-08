<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends MY_Controller {

  public function booking() {
    $this->require_role('customer');
    $this->load->model('Room_model');

    $data['rooms'] = $this->Room_model->all();
    $data['page_title'] = 'Pendaftaran Booking';
    $this->render('customer/booking_form', $data);
  }

  public function submit() {
    $this->require_role('customer');

    $this->form_validation->set_rules('judul_rapat', 'Judul Rapat', 'required|max_length[150]');
    $this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
    $this->form_validation->set_rules('jam_mulai', 'Jam Mulai', 'required');
    $this->form_validation->set_rules('jam_selesai', 'Jam Selesai', 'required');
    $this->form_validation->set_rules('room_id', 'Ruang', 'required|integer');

    if (!$this->form_validation->run()) {
      return $this->booking();
    }

    $jam_mulai   = $this->input->post('jam_mulai', true);
    $jam_selesai = $this->input->post('jam_selesai', true);

    if ($jam_selesai <= $jam_mulai) {
      $this->session->set_flashdata('error', 'Jam selesai harus setelah jam mulai.');
      redirect('customer/booking');
    }

    $this->load->model('Booking_model');
    $room_id = (int)$this->input->post('room_id');
    $tanggal = $this->input->post('tanggal', true);

    if ($this->Booking_model->has_conflict($room_id, $tanggal, $jam_mulai, $jam_selesai)) {
      $this->session->set_flashdata('error', 'Jadwal bentrok untuk ruang & tanggal tersebut. Pilih jam lain.');
      redirect('customer/booking');
    }

    $u = $this->user();
    $this->Booking_model->create([
      'user_id' => $u['id'],
      'judul_rapat' => $this->input->post('judul_rapat', true),
      'tanggal' => $tanggal,
      'jam_mulai' => $jam_mulai,
      'jam_selesai' => $jam_selesai,
      'room_id' => $room_id,
      'status' => 'PENDING'
    ]);

    $this->session->set_flashdata('success', 'Booking berhasil dikirim ke verifikator.');
    redirect('customer/history');
  }

  public function history() {
    $this->require_role('customer');
    $this->load->model('Booking_model');

    $data['rows'] = $this->Booking_model->by_user($this->user()['id']);
    $data['page_title'] = 'Riwayat Booking';
    $this->render('customer/history', $data);
  }
}
