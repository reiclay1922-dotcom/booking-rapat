<div class="card">
  <div class="card-body table-responsive">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>No</th>
          <th>Judul</th>
          <th>Tanggal</th>
          <th>Jam</th>
          <th>Durasi</th>
          <th>Ruang</th>
          <th>Status</th>
          <th>Catatan</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($rows)): ?>
          <tr><td colspan="8">Belum ada booking.</td></tr>
        <?php endif; ?>

        <?php $no=1; foreach($rows as $x): ?>
          <tr>
            <td><?= $no++; ?></td>
            <td><?= $x->judul_rapat; ?></td>
            <td><?= $x->tanggal; ?></td>
            <td><?= $x->jam_mulai; ?> - <?= $x->jam_selesai; ?></td>
            <td><?= format_durasi($x->durasi_menit); ?></td>
            <td><?= $x->nama_ruang; ?></td>
            <td>
              <?php if ($x->status === 'PENDING'): ?>
                <span class="badge badge-warning">PENDING</span>
              <?php elseif ($x->status === 'APPROVED'): ?>
                <span class="badge badge-success">APPROVED</span>
              <?php else: ?>
                <span class="badge badge-danger">REJECTED</span>
              <?php endif; ?>
            </td>
            <td><?= $x->catatan ? $x->catatan : '-'; ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
