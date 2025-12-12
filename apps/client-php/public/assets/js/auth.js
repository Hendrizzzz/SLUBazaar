// LOG IN

document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('login-form');
    const errorAlert = document.getElementById('error-alert');
    const submitBtn = document.getElementById('login-btn');

    if (loginForm) {
        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault(); // STOP page reload

            // 1. Reset UI
            errorAlert.style.display = 'none';
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
                // SUCCESS: Redirect to Marketplace or Admin Dashboard
                window.location.href = data.redirect_url; 
            } else {
                // ERROR: Show message
                errorAlert.innerText = data.error || "Login failed.";
                errorAlert.style.display = 'block';
                
                // Reset button
                submitBtn.disabled = false;
                submitBtn.innerText = "Log In";
            }
        });
    }
});

// REGISTER

document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('register-form');
    const errorAlert = document.getElementById('error-alert');
    const submitBtn = document.getElementById('register-btn');

    if (loginForm) {
        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault(); // STOP page reload

            // 1. Reset UI
            errorAlert.style.display = 'none';
            submitBtn.disabled = true;
            submitBtn.innerText = "Creating an account...";

            // 2. Get Data
            const formData = new FormData(loginForm);
            const jsonData = JSON.stringify(Object.fromEntries(formData));

            // 3. Send Request (Using our utils.js wrapper)
            const data = await apiFetch('index.php?action=register', {
                method: 'POST',
                body: jsonData
            });

            // 4. Handle Response
            if (data && data.success) {
                // SUCCESS: Redirect to Marketplace or Admin Dashboard
                window.location.href = data.redirect_url; 
            } else {
                // ERROR: Show message
                errorAlert.innerText = data.error || "Register failed.";
                errorAlert.style.display = 'block';
                
                // Reset button
                submitBtn.disabled = false;
                submitBtn.innerText = "Register";
            }
        });
    }
});