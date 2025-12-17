// LOG IN

document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('login-form');
    // const errorAlert = document.getElementById('error-alert'); // No longer needed
    const submitBtn = document.getElementById('login-btn');

    if (loginForm) {
        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault(); // STOP page reload

            // 1. Reset UI
            // errorAlert.style.display = 'none';
            submitBtn.disabled = true;
            submitBtn.innerText = "Logging in...";

            // 2. Get Data
            const formData = new FormData(loginForm);
            const jsonData = JSON.stringify(Object.fromEntries(formData));

            // 3. Send Request (Using our utils.js wrapper)
            const data = await apiFetch('index.php?action=login', {
                method: 'POST',
                body: jsonData
            });

            // 4. Handle Response
            if (data && data.success) {
                // SUCCESS
                showToast("Login successful! Redirecting...", 'success');
                setTimeout(() => {
                    window.location.href = data.redirect_url;
                }, 1000);
            } else {
                // ERROR: Show Toast
                showToast(data.error || "Login failed.", 'error');

                // Reset button
                submitBtn.disabled = false;
                submitBtn.innerText = "Log In";
            }
        });
    }
});

// REGISTER

document.addEventListener('DOMContentLoaded', () => {
    const registerForm = document.getElementById('register-form');
    // const errorAlert = document.getElementById('error-alert'); // No longer needed
    const submitBtn = document.getElementById('register-btn');

    if (registerForm) {
        registerForm.addEventListener('submit', async (e) => {
            e.preventDefault(); // STOP page reload

            // 1. Reset UI
            // errorAlert.style.display = 'none';
            submitBtn.disabled = true;
            submitBtn.innerText = "Creating an account...";

            // 2. Get Data
            const formData = new FormData(registerForm);

            // Basic Client Validation
            const pass = formData.get('password');
            const confirm = formData.get('confirm_password');
            if (pass !== confirm) {
                showToast("Passwords do not match.", 'error');
                submitBtn.disabled = false;
                submitBtn.innerText = "Register";
                return;
            }

            const jsonData = JSON.stringify(Object.fromEntries(formData));

            // 3. Send Request (Using our utils.js wrapper)
            const data = await apiFetch('index.php?action=register', {
                method: 'POST',
                body: jsonData
            });

            // 4. Handle Response
            if (data && data.success) {
                // SUCCESS
                showToast("Registration successful! Redirecting...", 'success');
                setTimeout(() => {
                    window.location.href = data.redirect_url;
                }, 1000);
            } else {
                // ERROR: Show Toast
                showToast(data.error || "Register failed.", 'error');

                // Reset button
                submitBtn.disabled = false;
                submitBtn.innerText = "Register";
            }
        });
    }
});