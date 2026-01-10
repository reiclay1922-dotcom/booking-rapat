<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Booking_model extends CI_Model
{

  public function create($data)
  {
    return $this->db->insert('bookings', $data);
  }

  public function by_user($user_id)
  {
    $this->db->select("
      b.*,
      r.nama_ruang,
      TIMESTAMPDIFF(
        MINUTE,
        CONCAT(b.tanggal,' ',b.jam_mulai),
        CONCAT(b.tanggal,' ',b.jam_selesai)
      ) AS durasi_menit
    ");
    $this->db->from('bookings b');
    $this->db->join('rooms r', 'r.id=b.room_id');
    $this->db->where('b.user_id', $user_id);
    $this->db->order_by('b.created_at', 'DESC');
    return $this->db->get()->result();
  }

  public function pending_list()
  {
    $this->db->select("
      b.*,
      u.nama as nama_user,
      r.nama_ruang,
      TIMESTAMPDIFF(
        MINUTE,
        CONCAT(b.tanggal,' ',b.jam_mulai),
        CONCAT(b.tanggal,' ',b.jam_selesai)
      ) AS durasi_menit
    ");
    $this->db->from('bookings b');
    $this->db->join('users u', 'u.id=b.user_id');
    $this->db->join('rooms r', 'r.id=b.room_id');
    $this->db->where('b.status', 'PENDING');
    $this->db->order_by('b.created_at', 'DESC');
    return $this->db->get()->result();
  }

  public function booked_schedule()
  {
    $this->db->select("
    b.*,
    u.nama as nama_user,
    r.nama_ruang,
    TIMESTAMPDIFF(
      MINUTE,
      CONCAT(b.tanggal,' ',b.jam_mulai),
      CONCAT(b.tanggal,' ',b.jam_selesai)
    ) AS durasi_menit
  ");
    $this->db->from('bookings b');
    $this->db->join('users u', 'u.id=b.user_id');
    $this->db->join('rooms r', 'r.id=b.room_id');
    $this->db->where_in('b.status', ['PENDING', 'APPROVED']);

    // PENDING dulu biar yang butuh aksi di atas (opsional)
    $this->db->order_by("FIELD(b.status, 'PENDING','APPROVED')", '', false);

    // urut jadwal
    $this->db->order_by('b.jam_mulai', 'ASC');
    $this->db->order_by('b.tanggal', 'ASC');

    // biar stabil kalau jam sama
    $this->db->order_by('b.id', 'ASC');

    return $this->db->get()->result();
  }



  // Bentrok jika: existing_start < new_end AND existing_end > new_start
  public function has_conflict($room_id, $tanggal, $jam_mulai, $jam_selesai)
  {
    $this->db->from('bookings');
    $this->db->where('room_id', $room_id);
    $this->db->where('tanggal', $tanggal);
    $this->db->where_in('status', ['PENDING', 'APPROVED']); // ubah kalau mau hanya APPROVED
    $this->db->where('jam_mulai <', $jam_selesai);
    $this->db->where('jam_selesai >', $jam_mulai);
    return $this->db->count_all_results() > 0;
  }

  public function update_status($id, $status, $verifikator_id, $catatan = null)
  {
    $this->db->where('id', $id);
    return $this->db->update('bookings', [
      'status' => $status,
      'verifikator_id' => $verifikator_id,
      'catatan' => $catatan,
      'verified_at' => date('Y-m-d H:i:s')
    ]);
  }
  public function rejected_list()
  {
    $this->db->select("
      b.*,
      u.nama as nama_user,
      r.nama_ruang,
      TIMESTAMPDIFF(
        MINUTE,
        CONCAT(b.tanggal,' ',b.jam_mulai),
        CONCAT(b.tanggal,' ',b.jam_selesai)
      ) AS durasi_menit
    ");
    $this->db->from('bookings b');
    $this->db->join('users u', 'u.id=b.user_id');
    $this->db->join('rooms r', 'r.id=b.room_id');
    $this->db->where('b.status', 'REJECTED');
    $this->db->order_by('b.verified_at', 'DESC');
    return $this->db->get()->result();
  }

  public function active_now()
  {
    $this->db->select("
    b.*,
    u.nama AS nama_user,
    r.nama_ruang,
    TIMESTAMPDIFF(
      MINUTE,
      CONCAT(b.tanggal,' ',b.jam_mulai),
      CONCAT(b.tanggal,' ',b.jam_selesai)
    ) AS durasi_menit
  ");
    $this->db->from('bookings b');
    $this->db->join('users u', 'u.id=b.user_id');
    $this->db->join('rooms r', 'r.id=b.room_id');
    $this->db->where('b.status', 'APPROVED');

    // sedang berlangsung: start <= NOW < end
    $this->db->where("CONCAT(b.tanggal,' ',b.jam_mulai) <=", "NOW()", false);
    $this->db->where("CONCAT(b.tanggal,' ',b.jam_selesai) >", "NOW()", false);

    $this->db->order_by('r.nama_ruang', 'ASC');
    return $this->db->get()->result();
  }
}
