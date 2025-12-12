<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SLU Bazaar</title>

    <!-- CSS -->
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/auth.css">
</head>

<body>

    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h1>Welcome Back</h1>
                <p>Login to SLU Bazaar</p>
                <p>For now, the default is on to log-in. Later it will be on the marketplace. </p>
            </div>

            <!-- Error Alert (Hidden by default) -->
            <div id="error-alert" class="alert alert-danger" style="display: none;"></div>

            <form id="login-form">
                <div class="form-group">
                    <label for="email">SLU Email</label>
                    <input type="email" id="email" name="email" placeholder="juan@slu.edu.ph" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" required>
                </div>

                <button type="submit" class="btn-primary" id="login-btn">Log In</button>
            </form>

            <div class="auth-footer">
                <p>Don't have an account? <a href="index.php?action=register">Register here</a></p>
            </div>
        </div>
    </div>

    <!-- JS -->
    <!-- Load the wrapper first -->
    <script src="/assets/js/utils.js"></script>
    <!-- Load the specific page logic -->
    <script src="/assets/js/auth.js"></script>

</body>

</html>