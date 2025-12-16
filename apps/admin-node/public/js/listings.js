/**
 * SLU Bazaar Admin - Listings Management
 * Handles AJAX search, Status Filtering, Soft Delete, and Content Updates.
 */

document.addEventListener('DOMContentLoaded', function () {
    loadListings();

    // Event Delegation
    const tableBody = document.getElementById('listings-table-body');
    if (tableBody) {
        tableBody.addEventListener('click', handleListingActions);
    }

    // Modal Events
    document.getElementById('btn-save-listing')?.addEventListener('click', updateListingContent);

    // Filter Listeners
    let searchTimeout;
    document.getElementById('search-listings')?.addEventListener('input', (e) => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(loadListings, 300);
    });

    document.getElementById('filter-status')?.addEventListener('change', loadListings);
});

// --- 1. Load Listings Table ---
async function loadListings() {
    const query = document.getElementById('search-listings')?.value || '';
    const status = document.getElementById('filter-status')?.value || '';
    const tableBody = document.getElementById('listings-table-body');

    try {
        // Spinner
        tableBody.innerHTML = `
            <tr>
                <td colspan="6" class="py-8 text-center">
                    <div class="flex justify-center items-center">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                    </div>
                    <p class="text-gray-400 text-sm mt-2">Loading listings...</p>
                </td>
            </tr>`;

        const response = await fetch(`/admin/api/listings?q=${encodeURIComponent(query)}&status=${status}`);

        if (!response.ok) throw new Error(`HTTP ${response.status}`);

        const result = await response.json();

        if (result.success && result.data.length > 0) {
            renderTable(result.data);
        } else {
            tableBody.innerHTML = '<tr><td colspan="6" class="text-center py-8 text-gray-500">No listings found.</td></tr>';
        }

    } catch (error) {
        console.error('Error loading listings:', error);
        tableBody.innerHTML = `<tr><td colspan="6" class="text-center py-4 text-red-500">Failed to load data: ${error.message}</td></tr>`;
    }
}

function renderTable(listings) {
    const tableBody = document.getElementById('listings-table-body');
    tableBody.innerHTML = listings.map(item => `
        <tr class="hover:bg-gray-50 transition-colors border-b last:border-b-0" id="listing-row-${item.item_id}">
             <td class="px-6 py-4">
                <div class="flex items-center">
                    <div class="h-10 w-10 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400">
                        ${item.image_url
            ? `<img src="${item.image_url}" class="h-10 w-10 object-cover rounded-lg" alt="">`
            : '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>'}
                    </div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900 truncate max-w-[200px]" title="${escapeHtml(item.title)}">${escapeHtml(item.title)}</div>
                        <div class="text-xs text-gray-500">ID: ${item.item_id}</div>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4 text-sm text-gray-600">${escapeHtml(item.seller || 'Unknown')}</td>
            <td class="px-6 py-4 text-sm font-medium text-gray-900">₱${parseFloat(item.price).toLocaleString()}</td>
            <td class="px-6 py-4">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                    ${getStatusColor(item.status)}">
                    ${item.status}
                </span>
            </td>
             <td class="px-6 py-4 text-sm text-gray-500">
                ${new Date(item.created_at || Date.now()).toLocaleDateString()}
            </td>
            <td class="px-6 py-4 text-right whitespace-nowrap">
                <button 
                    onclick='openEditModal(${JSON.stringify(item).replace(/'/g, "&#39;")})'
                    class="text-blue-600 hover:text-blue-900 font-medium text-sm mr-3">
                    Edit
                </button>
                <button 
                    data-id="${item.item_id}" 
                    class="btn-delete text-red-600 hover:text-red-900 font-medium text-sm">
                    Delete
                </button>
            </td>
        </tr>
    `).join('');
}

function getStatusColor(status) {
    switch (status) {
        case 'Active': return 'bg-green-100 text-green-800';
        case 'Pending': return 'bg-yellow-100 text-yellow-800';
        case 'Sold': return 'bg-gray-100 text-gray-800'; // Or blue
        case 'Reported': return 'bg-red-100 text-red-800';
        default: return 'bg-gray-100 text-gray-600';
    }
}


// --- 2. Handle Delete Actions ---
async function handleListingActions(e) {
    if (e.target.classList.contains('btn-delete')) {
        const btn = e.target;
        const itemId = btn.getAttribute('data-id');

        if (!confirm('Are you sure you want to remove this listing? It will not be visible in the marketplace.')) return;

        btn.disabled = true;
        btn.textContent = '...';

        try {
            const response = await fetch(`/admin/api/listings/${itemId}/remove`, {
                method: 'POST'
            });
            const result = await response.json();

            if (result.success) {
                // Remove row
                document.getElementById(`listing-row-${itemId}`).remove();
            } else {
                alert('Error: ' + result.error);
                btn.disabled = false;
                btn.textContent = 'Delete';
            }

        } catch (error) {
            console.error('Error removing listing:', error);
            btn.disabled = false;
            btn.textContent = 'Error';
        }
    }
}


// --- 3. Handle Edit / Sanitize Content ---
let currentEditId = null;

function openEditModal(item) {
    currentEditId = item.item_id;

    document.getElementById('edit-title').value = item.title;
    document.getElementById('edit-description').value = item.description || '';

    // Show Modal
    document.getElementById('edit-modal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('edit-modal').classList.add('hidden');
    currentEditId = null;
}
window.closeEditModal = closeEditModal;

async function updateListingContent() {
    if (!currentEditId) return;

    const title = document.getElementById('edit-title').value;
    const desc = document.getElementById('edit-description').value;
    const btn = document.getElementById('btn-save-listing');

    btn.disabled = true;
    btn.textContent = 'Saving...';

    try {
        const response = await fetch(`/admin/api/listings/${currentEditId}/update`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                title: title,
                description: desc
            })
        });
        const result = await response.json();

        if (result.success) {
            closeEditModal();
            loadListings(); // Refresh table to show new content
        } else {
            alert('Error: ' + result.error);
        }

    } catch (error) {
        console.error('Error updating:', error);
        alert('Failed to update listing.');
    } finally {
        btn.disabled = false;
        btn.textContent = 'Save Changes';
    }
}

function escapeHtml(text) {
    if (!text) return '';
    return text.replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;");
}
