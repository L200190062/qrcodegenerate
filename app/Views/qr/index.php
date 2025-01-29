<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <h2>Daftar QR Documents</h2>
    
    <?php if (session()->getFlashdata('message')) : ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('message') ?>
        </div>
    <?php endif; ?>

    <a href="<?= base_url('qr/create') ?>" class="btn btn-primary mb-3">Tambah Document</a>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Title</th>
                    <th>QR Code</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($documents as $index => $doc): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= esc($doc['title']) ?></td>
                    <td>
                        <img src="<?= base_url('uploads/qrcodes/' . $doc['qr_code']) ?>" 
                             alt="QR Code" style="width: 100px;">
                    </td>
                    <td><?= $doc['created_at'] ?></td>
                    <td>
                        <a href="<?= base_url('qr/show/' . $doc['id']) ?>" 
                           class="btn btn-info btn-sm">View</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>