/**
 * Global Wrapper for Fetch API
 * Handles JSON headers and errors automatically.
 */
async function apiFetch(url, options = {}) {
    // Default Headers
    const headers = {
        'X-Requested-With': 'XMLHttpRequest', // Tells PHP isAjax() = true
        'Content-Type': 'application/json',   // Tells PHP input is JSON
        ...options.headers
    };

    const config = {
        ...options,
        headers: headers
    };

    try {
        const response = await fetch(url, config);

        // Handle 401 Unauthorized globally
        if (response.status === 401) {
            window.location.href = 'index.php?action=login';
            return null;
        }

        return await response.json();
    } catch (error) {
        console.error("Network Error:", error);
        return { success: false, error: "Network error. Please check your connection." };
    }
}

/**
 * Show a Toast Notification
 * @param {string} message 
 * @param {string} type 'success', 'error', 'info'
 */
function showToast(message, type = 'success') {
    // Standardize 'danger' to 'error'
    if (type === 'danger') type = 'error';

    // Create container if not exists (though we append direct to body usually)
    let toast = document.createElement('div');
    toast.className = `toast-notification toast-${type}`;

    // Icon mapping
    let iconClass = 'fa-check-circle';
    if (type === 'error') iconClass = 'fa-exclamation-circle';
    if (type === 'info') iconClass = 'fa-info-circle';

    toast.innerHTML = `
        <i class="fa-solid ${iconClass}" style="font-size: 1.2rem;"></i>
        <span>${message}</span>
    `;

    document.body.appendChild(toast);

    // Trigger animation
    setTimeout(() => {
        toast.classList.add('show');
    }, 10);

    // Remove after 3 seconds
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => {
            toast.remove();
        }, 300); // 300ms transition time
    }, 3000);
}