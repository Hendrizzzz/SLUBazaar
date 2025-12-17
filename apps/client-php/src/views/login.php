<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SLU Bazaar</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <?php
    // Manual Base URL Detection (since $container is not in scope here)
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $domainName = $_SERVER['HTTP_HOST'];
    $path = dirname($_SERVER['SCRIPT_NAME']);
    $path = str_replace('\\', '/', $path);
    $baseUrl = rtrim($protocol . $domainName . $path, '/') . '/';
    ?>
    <!-- Debug: Base URL calculated as: <?php echo $baseUrl; ?> -->
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>assets/css/auth-modern.css?v=<?php echo time(); ?>">
</head>

<body class="slu-bg">

    <div class="auth-card">
        <!-- Logo Header -->
        <div class="logo-container">
            <img src="<?php echo $baseUrl; ?>assets/img/SLU Logo.png" alt="SLU Logo" class="auth-logo">
            <div style="text-align: left;">
                <h1 class="brand-title">SLU Bazaar</h1>
                <p class="brand-subtitle">Student Marketplace</p>
            </div>
        </div>

        <h2 class="page-title">Welcome Back</h2>
        <p class="page-desc">Please enter your credentials to login.</p>

        <!-- Error Alert (Hidden by default) -->
        <div id="error-alert" class="alert alert-danger" style="display: none;"></div>

        <form id="login-form" method="POST" action="index.php?action=login">
            <div class="form-group">
                <label for="email" class="form-label">SLU Email</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="juan@slu.edu.ph" required>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="••••••••"
                    required>
            </div>

            <button type="submit" class="btn-primary" id="login-btn">Log In</button>
        </form>

        <div class="auth-footer">
            <p>Don't have an account? <a href="index.php?action=register">Register here</a></p>
        </div>
    </div>

    <!-- JS -->
    <script>const BASE_URL = "<?php echo $baseUrl; ?>";</script>
    <script src="<?php echo $baseUrl; ?>assets/js/utils.js?v=<?php echo time(); ?>"></script>
    <script src="<?php echo $baseUrl; ?>assets/js/auth.js?v=<?php echo time(); ?>"></script>

</body>

</html>