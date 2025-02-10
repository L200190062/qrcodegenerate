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
                    <th>Job Name</th>
                    <th>Progress</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($jobs as $job): ?>
                <tr>
                    <td><?= esc($job['job_name']) ?></td>
                    <td>
                        <?= $job['processed_files'] ?>/<?= $job['total_files'] ?>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" 
                                 style="width: <?= ($job['total_files'] > 0) ? ($job['processed_files'] / $job['total_files'] * 100) : 0 ?>%">
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-<?= $job['status'] === 'completed' ? 'success' : 
                            ($job['status'] === 'processing' ? 'warning' : 
                            ($job['status'] === 'failed' ? 'danger' : 'secondary')) ?>">
                            <?= ucfirst($job['status']) ?>
                        </span>
                    </td>
                    <td><?= $job['created_at'] ?></td>
                    <td>
                        <?php if ($job['status'] === 'completed' && $job['zip_file']): ?>
                            <a href="<?= base_url('bulk-qr/download/' . $job['id']) ?>" 
                               class="btn btn-success btn-sm">Download ZIP</a>
                            <a href="<?= base_url('bulk-qr/view/' . $job['id']) ?>" 
                               class="btn btn-success btn-sm">View ZIP</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?> 