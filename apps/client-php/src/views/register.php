<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - SLU Bazaar</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Pure CSS Styles -->
    <?php
    // Manual Base URL Detection
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $domainName = $_SERVER['HTTP_HOST'];
    $path = dirname($_SERVER['SCRIPT_NAME']);
    $path = str_replace('\\', '/', $path);
    $baseUrl = rtrim($protocol . $domainName . $path, '/') . '/';
    ?>
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>assets/css/auth-modern.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="assets/css/global.css">
</head>

<body class="slu-bg">

    <div class="bg-layer-stripes"></div>
    <div class="bg-layer-building"></div>

    <div class="auth-card" style="max-width: 500px;"> <!-- Slightly wider for register form -->
        <!-- Logo Header -->
        <div class="logo-container">
            <img src="<?php echo $baseUrl; ?>assets/img/SLU Logo.png" alt="SLU Logo" class="auth-logo">
            <div style="text-align: left;">
                <h1 class="brand-title">SLU Bazaar</h1>
                <p class="brand-subtitle">Create Account</p>
            </div>
        </div>

        <p class="page-desc">Join the marketplace for Saint Louis University students.</p>

        <!-- Error Alert (Hidden by default) -->
        <div id="error-alert" class="alert alert-danger" style="display: none;"></div>

        <form id="register-form" method="POST" action="index.php?action=register">
            <div style="display: flex; gap: 1rem;">
                <div class="form-group" style="flex: 1;">
                    <label for="fname" class="form-label">First Name</label>
                    <input type="text" id="fname" name="fname" class="form-control" autocomplete="given-name"
                        placeholder="Juane" required>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label for="lname" class="form-label">Last Name</label>
                    <input type="text" id="lname" name="lname" class="form-control" autocomplete="family-name"
                        placeholder="Dela Cruz" required>
                </div>
            </div>

            <div class="form-group">
                <label for="email" class="form-label">SLU Email</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="juan@slu.edu.ph" required>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="••••••••"
                    required>
            </div>

            <div class="form-group">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control"
                    placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn-primary" id="register-btn">Create Account</button>
        </form>

        <div class="auth-footer">
            <p>Already have an account? <a href="index.php?action=login">Log in here</a></p>
        </div>
    </div>

    <!-- JS -->
    <script>const BASE_URL = "<?php echo $baseUrl; ?>";</script>
    <script src="<?php echo $baseUrl; ?>assets/js/utils.js?v=<?php echo time(); ?>"></script>
    <script src="<?php echo $baseUrl; ?>assets/js/auth.js?v=<?php echo time(); ?>"></script>

</body>

</html>