<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<section class="container">
    <input type="text" class="form-control">
    <br>
    <a href="/generate-certificate" class="btn btn-primary">Cetak Sertifikat</a>
</section>

<?= $this->endSection() ?>