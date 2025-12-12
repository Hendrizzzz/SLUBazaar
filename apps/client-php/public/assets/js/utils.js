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