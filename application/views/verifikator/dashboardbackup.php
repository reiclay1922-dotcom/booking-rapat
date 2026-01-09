<div class="card">
  <div class="card-header">
    <h3 class="card-title">Ruang Sedang Dipakai (Realtime)</h3>
  </div>

  <div class="card-body table-responsive">
    <table class="table table-bordered" id="tableActive" data-server-ts="<?= time(); ?>">

      <thead>
        <tr>
          <th>Ruang</th>
          <th>Judul</th>
          <th>Nama</th>
          <th>Mulai</th>
          <th>Selesai</th>
          <th>Durasi</th>
          <th>Sisa</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($active)): ?>
          <tr>
            <td colspan="7">Tidak ada rapat yang sedang berlangsung.</td>
          </tr>
        <?php else: ?>
          <?php foreach ($active as $x): ?>
            <tr class="row-active" data-start="<?= $x->tanggal . 'T' . $x->jam_mulai; ?>"
              data-end="<?= $x->tanggal . 'T' . $x->jam_selesai; ?>"
              data-start-ts="<?= strtotime($x->tanggal . ' ' . $x->jam_mulai); ?>"
              data-end-ts="<?= strtotime($x->tanggal . ' ' . $x->jam_selesai); ?>">
              <td><span class="badge badge-info"><?= $x->nama_ruang; ?></span></td>
              <td><?= $x->judul_rapat; ?></td>
              <td><?= $x->nama_user; ?></td>
              <td><?= $x->jam_mulai; ?></td>
              <td><?= $x->jam_selesai; ?></td>
              <td><?= format_durasi($x->durasi_menit); ?></td>
              <td><b class="sisa-text">-</b></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <h3 class="card-title">Jadwal Booking</h3>
  </div>
  <div class="card-body table-responsive">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Tanggal</th>
          <th>Jam</th>
          <th>Ruang</th>
          <th>Judul</th>
          <th>Nama</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($booked)): ?>
          <tr>
            <td colspan="6">Belum ada jadwal booking.</td>
          </tr>
        <?php else: ?>
          <?php foreach ($booked as $x): ?>
            <?php
            $status_class = $x->status === 'APPROVED' ? 'success' : 'warning';
            ?>
            <tr>
              <td><?= $x->tanggal; ?></td>
              <td><?= $x->jam_mulai; ?> - <?= $x->jam_selesai; ?></td>
              <td><?= $x->nama_ruang; ?></td>
              <td><?= $x->judul_rapat; ?></td>
              <td><?= $x->nama_user; ?></td>
              <td><span class="badge badge-<?= $status_class; ?>"><?= $x->status; ?></span></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<div class="card">
  <div class="card-body table-responsive">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>No</th>
          <th>Nama</th>
          <th>Judul</th>
          <th>Waktu</th>
          <th>Durasi</th>
          <th>Ruang</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($rows)): ?>
          <tr>
            <td colspan="7">Tidak ada booking pending.</td>
          </tr>
        <?php endif; ?>

        <?php $no = 1;
        foreach ($rows as $x): ?>
          <tr>
            <td><?= $no++; ?></td>
            <td><?= $x->nama_user; ?></td>
            <td><?= $x->judul_rapat; ?></td>
            <td><?= $x->tanggal; ?> (<?= $x->jam_mulai; ?> - <?= $x->jam_selesai; ?>)</td>
            <td><?= format_durasi($x->durasi_menit); ?></td>
            <td><?= $x->nama_ruang; ?></td>
            <td>
              <a class="btn btn-success btn-sm" href="<?= site_url('verifikator/approve/' . $x->id); ?>">
                Approve
              </a>

              <button class="btn btn-danger btn-sm btn-reject" data-id="<?= $x->id; ?>"
                data-judul="<?= htmlspecialchars($x->judul_rapat, ENT_QUOTES); ?>" data-toggle="modal"
                data-target="#modalReject">
                Reject
              </button>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<div class="modal fade" id="modalReject" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form method="post" id="formReject">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Reject Booking</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          <p id="txtJudul"></p>
          <div class="form-group">
            <label>Catatan Penolakan</label>
            <textarea class="form-control" name="catatan" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-danger">Reject</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const table = document.getElementById('tableActive');
    if (!table) return;

    // offset supaya countdown mengikuti waktu server (unix timestamp, aman dari masalah timezone parsing)
    let offsetSec = parseInt(table.dataset.serverTs || '0', 10) - Math.floor(Date.now() / 1000);

    function pad2(n) {
      return String(n).padStart(2, '0');
    }

    function formatHMS(diffSec) {
      const h = Math.floor(diffSec / 3600);
      const m = Math.floor((diffSec % 3600) / 60);
      const s = diffSec % 60;
      return `${pad2(h)}:${pad2(m)}:${pad2(s)}`;
    }

    // fallback kalau data-end-ts belum ada: parse ISO jadi local time (bukan Date string default)
    function isoToLocalTs(iso) {
      if (!iso) return 0;
      const parts = String(iso).split('T');
      if (parts.length !== 2) return 0;
      const [Y, M, D] = parts[0].split('-').map(Number);
      const t = parts[1].split(':').map(Number);
      const hh = t[0] ?? 0,
        mm = t[1] ?? 0,
        ss = t[2] ?? 0;
      const d = new Date(Y, (M || 1) - 1, D || 1, hh, mm, ss);
      return Math.floor(d.getTime() / 1000);
    }

    function tick() {
      const now = Math.floor(Date.now() / 1000) + offsetSec;

      document.querySelectorAll('tr.row-active').forEach(tr => {
        const el = tr.querySelector('.sisa-text');
        if (!el) return;

        // prioritas pakai unix timestamp dari server
        let startTs = parseInt(tr.dataset.startTs || '0', 10);
        let endTs = parseInt(tr.dataset.endTs || '0', 10);

        // fallback kalau belum ada
        if (!startTs) startTs = isoToLocalTs(tr.dataset.start);
        if (!endTs) endTs = isoToLocalTs(tr.dataset.end);

        if (!endTs) {
          el.textContent = '-';
          return;
        }

        // kalau belum mulai: tampilkan hitung mundur menuju mulai
        if (startTs && now < startTs) {
          el.textContent = 'Mulai ' + formatHMS(startTs - now);
          return;
        }

        const diff = endTs - now;
        if (diff <= 0) {
          el.textContent = 'Selesai';
          return;
        }

        el.textContent = formatHMS(diff);
      });
    }

    setInterval(tick, 1000);
    tick();
  });
</script>