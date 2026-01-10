<?php
$csrf_name = $this->security->get_csrf_token_name();
$csrf_hash = $this->security->get_csrf_hash();
?>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
<style>
    .card-header {
        background-color: #f8f9fa;
        border-bottom: 2px solid #007bff;
    }


    .table thead th {
        background-color: #007bff;
        color: white;
        font-weight: 600;
        border: none;
        vertical-align: middle;
    }

    .table tbody tr:hover {
        background-color: #f5f5f5;
    }

    .dataTables_wrapper .dataTables_paginate {
        margin-top: 15px;
    }

    .badge {
        padding: 6px 12px;
        font-size: 12px;
        font-weight: 600;
    }
</style>

<!-- tempat notifikasi -->
<div id="flashArea"></div>

<div class="card shadow-sm">


    <div class="card-body">
        <table id="tabelBooking" class="table table-striped table-hover">
            <thead>
                <tr>
                    <th style="width: 12%;">Tanggal</th>
                    <th style="width: 12%;">Jam</th>
                    <th style="width: 12%;">Ruang</th>
                    <th style="width: 18%;">Judul</th>
                    <th style="width: 12%;">Nama</th>
                    <th style="width: 22%;">Alasan Penolakan</th>
                    <th style="width: 12%;">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($booked) && empty($rejected)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <em class="text-muted">Belum ada booking.</em>
                        </td>
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
                            <td><span class="text-muted">-</span></td>
                            <td><span class="badge badge-<?= $status_class; ?>"><?= $x->status; ?></span></td>
                        </tr>
                    <?php endforeach; ?>

                    <?php foreach ($rejected as $x): ?>
                        <tr class="table-danger">
                            <td><?= $x->tanggal; ?></td>
                            <td><?= $x->jam_mulai; ?> - <?= $x->jam_selesai; ?></td>
                            <td><?= $x->nama_ruang; ?></td>
                            <td><?= $x->judul_rapat; ?></td>
                            <td><?= $x->nama_user; ?></td>
                            <td><small><?= $x->catatan; ?></small></td>
                            <td><span class="badge badge-danger">REJECTED</span></td>
                        </tr>
                    <?php endforeach; ?>

                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>

<script>
    $(document).ready(function() {
        $('#tabelBooking').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "order": [
                [0, "desc"]
            ],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json"
            }
        });
    });
</script>