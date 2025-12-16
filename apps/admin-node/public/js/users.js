/**
 * SLU Bazaar Admin - User Management
 * Handles AJAX search, filtering, and Ban/Unban actions.
 */

document.addEventListener('DOMContentLoaded', function () {
    loadUsers();

    // Event Delegation for dynamic buttons
    const tableBody = document.getElementById('users-table-body');
    if (tableBody) {
        tableBody.addEventListener('click', handleUserActions);
    }

    // Filter Listeners
    let searchTimeout;
    document.getElementById('search-users')?.addEventListener('input', (e) => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(loadUsers, 300); // 300ms debounce
    });

    document.getElementById('filter-status')?.addEventListener('change', loadUsers);
});

// --- 1. Load Users Table ---
async function loadUsers() {
    const query = document.getElementById('search-users')?.value || '';
    const status = document.getElementById('filter-status')?.value || '';
    const tableBody = document.getElementById('users-table-body');

    try {
        // Spinner
        tableBody.innerHTML = `
            <tr>
                <td colspan="5" class="py-8 text-center">
                    <div class="flex justify-center items-center">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                    </div>
                    <p class="text-gray-400 text-sm mt-2">Loading directory...</p>
                </td>
            </tr>`;

        const response = await fetch(`/admin/api/users?q=${encodeURIComponent(query)}&status=${status}`);

        if (!response.ok) throw new Error(`HTTP ${response.status}`);

        const result = await response.json();

        if (result.success && result.data.length > 0) {
            renderTable(result.data);
        } else {
            tableBody.innerHTML = '<tr><td colspan="5" class="text-center py-8 text-gray-500">No users found matching your search.</td></tr>';
        }

    } catch (error) {
        console.error('Error loading users:', error);
        tableBody.innerHTML = `<tr><td colspan="5" class="text-center py-4 text-red-500">Failed to load users: ${error.message}</td></tr>`;
    }
}

function renderTable(users) {
    const tableBody = document.getElementById('users-table-body');
    tableBody.innerHTML = users.map(user => `
        <tr class="hover:bg-gray-50 transition-colors border-b last:border-b-0" id="user-row-${user.user_id}">
            <td class="px-6 py-4">
                <div class="flex items-center">
                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold uppercase">
                        ${user.name ? user.name.charAt(0) : 'U'}
                    </div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900">${escapeHtml(user.name)}</div>
                        <div class="text-sm text-gray-500">${escapeHtml(user.email)}</div>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4 text-sm text-gray-600">
                ${new Date(user.created_at || Date.now()).toLocaleDateString()}
            </td>
            <td class="px-6 py-4">
                <span id="status-badge-${user.user_id}" 
                      class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                      ${user.status === 'banned' ? 'bg-red-100 text-red-800' :
            user.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}">
                    ${capitalize(user.status)}
                </span>
            </td>
            <td class="px-6 py-4 text-center">
                ${user.reports_against_count > 0
            ? `<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-800">
                       ${user.reports_against_count} Reports
                     </span>`
            : '<span class="text-gray-400 text-xs">-</span>'}
            </td>
            <td class="px-6 py-4 text-right">
                <button 
                    data-id="${user.user_id}" 
                    data-status="${user.status}"
                    class="btn-toggle-status text-xs font-semibold px-3 py-1.5 rounded border transition-colors
                    ${user.status === 'banned'
            ? 'text-gray-700 border-gray-300 hover:bg-gray-100'
            : 'text-red-600 border-red-200 hover:bg-red-50'}">
                    ${user.status === 'banned' ? 'Unban User' : 'Ban User'}
                </button>
            </td>
        </tr>
    `).join('');
}


// --- 2. Handle Status Actions (Ban / Unban) ---
async function handleUserActions(e) {
    if (e.target.classList.contains('btn-toggle-status')) {
        const btn = e.target;
        const userId = btn.getAttribute('data-id');
        const currentStatus = btn.getAttribute('data-status');

        const newStatus = currentStatus === 'banned' ? 'active' : 'banned';
        const actionText = newStatus === 'banned' ? 'Ban' : 'Unban';

        if (!confirm(`Are you sure you want to ${actionText} this user?`)) return;

        // UI Loading State
        btn.disabled = true;
        btn.textContent = 'Updating...';

        try {
            const response = await fetch(`/admin/api/users/${userId}/status`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ status: newStatus })
            });
            const result = await response.json();

            if (result.success) {
                // Update UI without full reload
                updateUserRow(userId, newStatus);
            } else {
                alert('Error: ' + result.error);
                // Reset button
                btn.disabled = false;
                btn.textContent = currentStatus === 'banned' ? 'Unban User' : 'Ban User';
            }

        } catch (error) {
            console.error('Error updating status:', error);
            btn.disabled = false;
            btn.textContent = 'Error';
        }
    }
}

function updateUserRow(userId, newStatus) {
    // Update Badge
    const badge = document.getElementById(`status-badge-${userId}`);
    if (badge) {
        badge.className = `inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${newStatus === 'banned' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'
            }`;
        badge.textContent = capitalize(newStatus);
    }

    // Update Button
    const btn = document.querySelector(`button[data-id="${userId}"]`);
    if (btn) {
        btn.disabled = false;
        btn.setAttribute('data-status', newStatus);

        if (newStatus === 'banned') {
            btn.textContent = 'Unban User';
            btn.className = 'btn-toggle-status text-xs font-semibold px-3 py-1.5 rounded border transition-colors text-gray-700 border-gray-300 hover:bg-gray-100';
        } else {
            btn.textContent = 'Ban User';
            btn.className = 'btn-toggle-status text-xs font-semibold px-3 py-1.5 rounded border transition-colors text-red-600 border-red-200 hover:bg-red-50';
        }
    }
}


function escapeHtml(text) {
    if (!text) return '';
    return text.replace(/</g, "&lt;").replace(/>/g, "&gt;");
}

function capitalize(s) {
    if (!s) return '';
    return s.charAt(0).toUpperCase() + s.slice(1);
}
