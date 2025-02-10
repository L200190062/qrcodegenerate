<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <h2>Bulk QR Processing Jobs</h2>
    
    <?php if (session()->getFlashdata('message')) : ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('message') ?>
        </div>
    <?php endif; ?>

    <a href="<?= base_url('bulk-qr/create') ?>" class="btn btn-primary mb-3">New Bulk Process</a>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Created</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($qr_documents as $qr_document): ?>
                <tr>
                    <td><?= esc($qr_document['title']) ?></td>
                    <td><?= $qr_document['created_at'] ?></td>
                    <td>
                            <a target="_blank" href="<?= base_url( $qr_document['pdf_file']) ?>" 
                               class="btn btn-success btn-sm">Download File</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?> 