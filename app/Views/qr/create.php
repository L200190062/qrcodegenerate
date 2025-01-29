<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <h2>Upload New Document</h2>

    <?php if (session()->has('errors')) : ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach (session('errors') as $error) : ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach ?>
            </ul>
        </div>
    <?php endif ?>

    <form action="<?= base_url('qr/store') ?>" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" name="title" 
                   value="<?= old('title') ?>" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" 
                      rows="3"><?= old('description') ?></textarea>
        </div>

        <div class="mb-3">
            <label for="pdf_file" class="form-label">PDF File</label>
            <input type="file" class="form-control" id="pdf_file" name="pdf_file" 
                   accept="application/pdf" required>
        </div>

        <button type="submit" class="btn btn-primary">Upload</button>
        <a href="<?= base_url('qr') ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<?= $this->endSection() ?>