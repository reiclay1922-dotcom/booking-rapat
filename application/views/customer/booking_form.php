<div class="card">
  <div class="card-body">

    <?= validation_errors('<div class="alert alert-danger">','</div>'); ?>

    <form method="post" action="<?= site_url('customer/submit'); ?>">
      <div class="form-group">
        <label>Nama User</label>
        <input class="form-control" value="<?= $this->session->userdata('user')['nama']; ?>" readonly>
      </div>

      <div class="form-group">
        <label>Judul Rapat</label>
        <input class="form-control" name="judul_rapat" required>
      </div>

      <div class="form-group">
        <label>Tanggal</label>
        <input type="date" class="form-control" name="tanggal" required>
      </div>

      <div class="form-row">
        <div class="form-group col-md-6">
          <label>Jam Dari</label>
          <input type="time" class="form-control" name="jam_mulai" required>
        </div>
        <div class="form-group col-md-6">
          <label>Jam Sampai</label>
          <input type="time" class="form-control" name="jam_selesai" required>
        </div>
      </div>

      <div class="form-group">
        <small class="text-muted">Durasi: <span id="durasiText">-</span></small>
      </div>

      <div class="form-group">
        <label>Ruang Rapat</label>
        <select class="form-control" name="room_id" required>
          <option value="">-- pilih ruang --</option>
          <?php foreach ($rooms as $r): ?>
            <option value="<?= $r->id; ?>"><?= $r->nama_ruang; ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <button class="btn btn-primary" type="submit">Kirim ke Verifikator</button>
    </form>

  </div>
</div>

<script>
function hitungDurasi() {
  const mulai = document.querySelector('input[name="jam_mulai"]').value;
  const selesai = document.querySelector('input[name="jam_selesai"]').value;
  const out = document.getElementById('durasiText');

  if (!mulai || !selesai) { out.textContent = '-'; return; }

  const [mh, mm] = mulai.split(':').map(Number);
  const [sh, sm] = selesai.split(':').map(Number);
  const m1 = mh*60 + mm;
  const m2 = sh*60 + sm;

  if (m2 <= m1) { out.textContent = 'Jam tidak valid'; return; }

  const diff = m2 - m1;
  const jam = Math.floor(diff / 60);
  const menit = diff % 60;

  if (jam > 0 && menit > 0) out.textContent = `${jam} jam ${menit} menit`;
  else if (jam > 0) out.textContent = `${jam} jam`;
  else out.textContent = `${menit} menit`;
}

document.addEventListener('DOMContentLoaded', () => {
  document.querySelector('input[name="jam_mulai"]').addEventListener('change', hitungDurasi);
  document.querySelector('input[name="jam_selesai"]').addEventListener('change', hitungDurasi);
});
</script>
