<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login dengan NIM</title>
</head>

<body>
    <h2>Login dengan NIM</h2>

    <?php if (session()->getFlashdata('error')) : ?>
        <p style="color:red;"><?= session()->getFlashdata('error') ?></p>
    <?php endif; ?>

    <form action="/auth/check-login" method="post">
        <?= csrf_field() ?>

        <label for="nim">NIM:</label>
        <input type="text" name="nim" required>
        <button type="submit">Lanjut</button>
    </form>
</body>

</html>