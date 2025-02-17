<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Password</title>
</head>

<body>
    <h2>Set Password</h2>

    <form action="/auth/save-password" method="post">
        <?= csrf_field() ?>
        <label for="password">Password Baru:</label>
        <input type="password" name="password" required>
        <button type="submit">Simpan Password</button>
    </form>
</body>

</html>