<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<section class="container">
    <form action="/logout" method="post">
        <?= csrf_field() ?>
        <button type="submit" class="btn btn-danger">Logout</button>
    </form>
    <input type="text" class="form-control">
    <br>
    <a href="/generate-certificate" class="btn btn-primary">Cetak Sertifikat</a>
</section>

<?= $this->endSection() ?>