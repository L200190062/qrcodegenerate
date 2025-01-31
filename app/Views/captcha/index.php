<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University CAPTCHA Verification</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
    }

    .logo {
        max-width: 180px;
        margin-bottom: 2rem;
        filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.1));
    }

    .main-container {
        background-color: rgba(255, 255, 255, 0.9);
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .left-side {
        background: linear-gradient(45deg, #3498db, #8e44ad);
        color: white;
        padding: 3rem;
    }

    .right-side {
        padding: 3rem;
    }

    h1 {
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .lead {
        font-size: 1.1rem;
    }

    .captcha-container {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .btn-primary {
        background-color: #3498db;
        border: none;
        padding: 0.75rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #2980b9;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="main-container">
                    <div class="row g-0">
                        <div class="col-md-6 left-side d-flex flex-column justify-content-center">
                            <img src="/logo.png" alt="University Logo" class="logo mx-auto d-block">
                            <h1 class="text-center">Welcome to Our University</h1>
                            <p class="lead text-center mb-0">Empowering minds, shaping futures. Verify your identity to
                                access our secure student portal.</p>
                        </div>
                        <div class="col-md-6 right-side">
                            <h2 class="mb-4">CAPTCHA Verification</h2>

                            <?php if (session()->has('error')): ?>
                            <div class="alert alert-danger" role="alert">
                                <?= session()->get('error') ?>
                            </div>
                            <?php endif; ?>

                            <form action="<?= base_url('captcha-verify/verify') ?>" method="post">
                                <?= csrf_field() ?>
                                <div class="captcha-container text-center">
                                    <?= $captcha_image ?>
                                </div>
                                <div class="mb-4">
                                    <input type="text" class="form-control form-control-lg" name="captcha"
                                        placeholder="Enter CAPTCHA" required>
                                </div>
                                <button type="submit" class="btn btn-primary btn-lg w-100">Lanjut</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (optional, if you need JavaScript features) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>