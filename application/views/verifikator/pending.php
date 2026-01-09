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