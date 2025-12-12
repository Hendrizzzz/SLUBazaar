<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SLU Bazaar</title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/auth.css">
</head>
<body>

    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h1>Create an account</h1>
                <p>Register to SLU Bazaar</p>
            </div>

            <!-- Error Alert (Hidden by default) -->
            <div id="error-alert" class="alert alert-danger" style="display: none;"></div>

            <form id="register-form">
                <div class="form-group">
                    <label for="fname">First Name</label>
                    <input type="text" id="fname" name="fname" autocomplete="given-name" placeholder="Juane" required>
                </div>
                <div class="form-group">
                    <label for="lname">Last Name</label>
                    <input type="text" id="lname" name="lname" autocomplete="family-name" placeholder="Dela Cruz" required>
                </div>

                <div class="form-group">
                    <label for="email">SLU Email</label>
                    <input type="email" id="email" name="email" placeholder="juan@slu.edu.ph" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" required>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="••••••••" required>
                </div>

                <button type="submit" class="btn-primary" id="register-btn">Register</button>
            </form>

            <div class="auth-footer">
                <p>Already have an account?<a href="index.php?action=login"> Log in here</a></p>
            </div>
        </div>
    </div>

    <!-- JS -->
    <!-- Load the wrapper first -->
    <script src="assets/js/utils.js"></script>
    <!-- Load the specific page logic -->
    <script src="assets/js/auth.js"></script>

</body>
</html>