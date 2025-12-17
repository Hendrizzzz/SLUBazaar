/**
 * SLU Bazaar - Profile JS
 * Handles specific interactions for the Profile Page
 */

function openEditProfileModal() {
    const modal = document.getElementById('edit-profile-modal');
    if (modal) {
        modal.style.display = 'flex';
        // Reset fields if needed, or keeping them filled with PHP values is fine
        document.getElementById('edit-current-password').value = '';
    }
}

function closeEditProfileModal() {
    const modal = document.getElementById('edit-profile-modal');
    if (modal) {
        modal.style.display = 'none';
    }
}

async function submitProfileUpdate() {
    const form = document.getElementById('edit-profile-form');
    const formData = new FormData(form);

    // Check passwords client side first
    const newPass = formData.get('new_password');
    const confirmPass = formData.get('confirm_password');

    if (newPass && newPass !== confirmPass) {
        showToast("New passwords do not match.", 'error');
        return;
    }

    try {
        // We use JSON for consistency with other parts of the app
        const jsonData = {};
        formData.forEach((value, key) => jsonData[key] = value);

        const response = await fetch('index.php?action=update_profile', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(jsonData)
        });

        const result = await response.json();

        if (result.success) {
            showToast("Profile updated successfully!", 'success');
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showToast(result.error || "Failed to update profile.", 'error');
        }

    } catch (error) {
        console.error('Error updating profile:', error);
        showToast("An error occurred. Please try again.", 'error');
    }
}

// Close modal when clicking outside
window.addEventListener('click', function (event) {
    const modal = document.getElementById('edit-profile-modal');
    if (event.target == modal) {
        closeEditProfileModal();
    }
});
