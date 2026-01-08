<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Verifikator extends MY_Controller {

    public function index() {
    $this->require_role('verifikator');
    $this->load->model('Booking_model');

    $data['rows']   = $this->Booking_model->pending_list();
    $data['active'] = $this->Booking_model->active_now();
    $data['server_now'] = date('c'); // untuk offset waktu di JS
    $data['server_now_ts'] = time(); // unix timestamp (lebih akurat & aman)

    $data['page_title'] = 'Dashboard Verifikator';
    $this->render('verifikator/dashboard', $data);
    }


  public function approve($id) {
    $this->require_role('verifikator');
    $this->load->model('Booking_model');

    $this->Booking_model->update_status($id, 'APPROVED', $this->user()['id']);
    $this->session->set_flashdata('success', 'Booking berhasil di-approve.');
    redirect('verifikator');
  }

  public function reject($id) {
    $this->require_role('verifikator');
    $catatan = $this->input->post('catatan', true);

    if (!$catatan) {
      $this->session->set_flashdata('error', 'Catatan penolakan wajib diisi.');
      redirect('verifikator');
    }

    $this->load->model('Booking_model');
    $this->Booking_model->update_status($id, 'REJECTED', $this->user()['id'], $catatan);
    $this->session->set_flashdata('success', 'Booking berhasil di-reject.');
    redirect('verifikator');
  }
    public function live() {
    $this->require_role('verifikator');
    $this->load->model('Booking_model');

    $active = $this->Booking_model->active_now();

    $payload = [
        'server_now' => date('c'),
        'server_now_ts' => time(),
        'server_tz' => date_default_timezone_get(),
        'active' => array_map(function($x) {
        return [
            'id' => (int)$x->id,
            'nama_user' => $x->nama_user,
            'judul_rapat' => $x->judul_rapat,
            'nama_ruang' => $x->nama_ruang,
            'tanggal' => $x->tanggal,
            'jam_mulai' => $x->jam_mulai,
            'jam_selesai' => $x->jam_selesai,
            'durasi_menit' => (int)$x->durasi_menit,
            // ISO datetime utk countdown realtime
            'start_iso' => $x->tanggal.'T'.$x->jam_mulai,
            'end_iso'   => $x->tanggal.'T'.$x->jam_selesai,
            'start_ts' => strtotime($x->tanggal.' '.$x->jam_mulai),
            'end_ts'   => strtotime($x->tanggal.' '.$x->jam_selesai),
        ];
        }, $active)
    ];

    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($payload));
    }

}
