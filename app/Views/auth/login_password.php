<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login dengan Password</title>
</head>

<body>
    <h2>Login</h2>

    <form action="/auth/process-login" method="post">

        <?= csrf_field() ?>

        <label for="nim">NIM:</label>
        <input type="text" name="nim" required>

        <label for="password">Password:</label>
        <input type="password" name="password" required>

        <button type="submit">Login</button>
    </form>
</body>

</html>