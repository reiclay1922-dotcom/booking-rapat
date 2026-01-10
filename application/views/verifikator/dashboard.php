<?php
$csrf_name = $this->security->get_csrf_token_name();
$csrf_hash = $this->security->get_csrf_hash();
?>

<!-- tempat notifikasi -->
<div id="flashArea"></div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Jadwal Booking</h3>
    </div>

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