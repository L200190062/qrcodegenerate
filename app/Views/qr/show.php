<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <h2><?= esc($document['title']) ?></h2>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">QR Code</h5>
                    <img src="<?= base_url('uploads/qrcodes/' . $document['qr_code']) ?>" 
                         alt="QR Code" class="img-fluid">
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Document Details</h5>
                    <p><strong>Created:</strong> <?= $document['created_at'] ?></p>
                    <?php if ($document['description']): ?>
                        <p><strong>Description:</strong><br><?= esc($document['description']) ?></p>
                    <?php endif; ?>
                    <a href="<?= base_url('uploads/pdf/' . $document['pdf_file']) ?>" 
                       class="btn btn-primary" target="_blank">
                        View PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12 mt-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">PDF Preview</h5>
                <embed src="<?= base_url('uploads/pdf/final/' . $document['pdf_file']) ?>" 
                       type="application/pdf" 
                       width="100%" 
                       height="600px">
            </div>
        </div>
    </div>

    <a href="<?= base_url('qr') ?>" class="btn btn-secondary mt-3">Back to List</a>
</div>
<?= $this->endSection() ?>