/**
 * SLU Bazaar Admin - Reports Management
 * Handles AJAX table loading, Modal interactions, and Report Resolution.
 */

document.addEventListener('DOMContentLoaded', function () {
    loadReports();

    // Event Delegation for dynamic buttons
    const tableBody = document.getElementById('reports-table-body');
    if (tableBody) {
        tableBody.addEventListener('click', handleTableActions);
    }

    // Modal Action Buttons
    document.getElementById('btn-dismiss')?.addEventListener('click', () => resolveReport('Dismiss'));
    document.getElementById('btn-ban')?.addEventListener('click', () => resolveReport('BanUser'));
    document.getElementById('btn-remove')?.addEventListener('click', () => resolveReport('RemoveItem'));

    // Filter Listeners
    document.getElementById('filter-status')?.addEventListener('change', loadReports);
    document.getElementById('filter-type')?.addEventListener('change', loadReports);
});

// --- 1. Load Reports Table ---
async function loadReports() {
    const status = document.getElementById('filter-status')?.value || 'Pending';
    const type = document.getElementById('filter-type')?.value || '';
    const tableBody = document.getElementById('reports-table-body');

    try {
        // Spinner
        tableBody.innerHTML = `
            <tr>
                <td colspan="6" class="py-8 text-center">
                    <div class="flex justify-center items-center">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                    </div>
                    <p class="text-gray-400 text-sm mt-2">Loading reports...</p>
                </td>
            </tr>`;

        const response = await fetch(`/admin/api/reports?status=${status}&type=${type}`);

        if (!response.ok) throw new Error(`HTTP ${response.status}`);

        const result = await response.json();

        if (result.success && result.data.length > 0) {
            renderTable(result.data);
        } else {
            tableBody.innerHTML = '<tr><td colspan="6" class="text-center py-8 text-gray-500">No reports found.</td></tr>';
        }

    } catch (error) {
        console.error('Error loading reports:', error);
        tableBody.innerHTML = `<tr><td colspan="6" class="text-center py-4 text-red-500">Failed to load reports: ${error.message}</td></tr>`;
    }
}

function renderTable(reports) {
    const tableBody = document.getElementById('reports-table-body');
    tableBody.innerHTML = reports.map(report => `
        <tr class="hover:bg-gray-50 transition-colors border-b last:border-b-0" id="row-${report.report_id}">
            <td class="px-6 py-4 text-sm text-gray-600">${new Date(report.date).toLocaleDateString()}</td>
            <td class="px-6 py-4">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                    ${report.type === 'User' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800'}">
                    ${report.type}
                </span>
            </td>
            <td class="px-6 py-4 text-sm text-gray-900 font-medium">${escapeHtml(report.reason)}</td>
            <td class="px-6 py-4 text-sm text-gray-600">${escapeHtml(report.reporter)}</td>
            <td class="px-6 py-4 text-sm text-gray-600 truncate max-w-xs" title="${escapeHtml(report.target)}">
                ${escapeHtml(report.target)}
            </td>
            <td class="px-6 py-4 text-right">
                <button 
                    data-id="${report.report_id}" 
                    class="btn-review text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300 transition-colors">
                    Review
                </button>
            </td>
        </tr>
    `).join('');
}


// --- 2. Handle Table Clicks (Open Modal) ---
async function handleTableActions(e) {
    if (e.target.classList.contains('btn-review')) {
        const reportId = e.target.getAttribute('data-id');
        await openReviewModal(reportId);
    }
}

let currentReportId = null;

async function openReviewModal(reportId) {
    currentReportId = reportId;
    const modal = document.getElementById('review-modal');
    const content = document.getElementById('modal-content');

    // Show modal in loading state
    modal.classList.remove('hidden');
    content.innerHTML = '<div class="text-center py-10">Loading details...</div>';

    try {
        const response = await fetch(`/admin/api/reports/${reportId}`);
        const result = await response.json();

        if (result.success) {
            renderModalContent(result.data);


            const btnRemove = document.getElementById('btn-remove');
            const btnBan = document.getElementById('btn-ban');
        } else {
            content.innerHTML = '<div class="text-red-500 py-4">Error loading report details.</div>';
        }
    } catch (error) {
        console.error('Error:', error);
        content.innerHTML = '<div class="text-red-500 py-4">Network error.</div>';
    }
}

function renderModalContent(data) {
    // Populate the read-only details
    document.getElementById('modal-desc').textContent = data.description || 'No description provided.';
    document.getElementById('modal-notes').value = data.admin_notes || '';

    // Images
    const imgContainer = document.getElementById('modal-images');
    imgContainer.innerHTML = '';
    if (data.evidence_images && data.evidence_images.length > 0) {
        data.evidence_images.forEach(imgUrl => {
            const img = document.createElement('img');
            img.src = imgUrl.startsWith('http') ? imgUrl : `/assets/uploads/${imgUrl}`;
            img.className = 'w-full h-auto rounded-lg border border-gray-200 mt-2';
            imgContainer.appendChild(img);
        });
    } else {
        imgContainer.innerHTML = '<p class="text-sm text-gray-500 italic">No evidence images attached.</p>';
    }


    document.getElementById('modal-content').innerHTML = '';

    const loadingDiv = document.getElementById('modal-loading');
    const detailsDiv = document.getElementById('modal-details');

    loadingDiv.classList.add('hidden');
    detailsDiv.classList.remove('hidden');
}


// --- 3. Resolve Report ---
async function resolveReport(action) {
    if (!currentReportId) return;

    const notes = document.getElementById('modal-notes').value;
    const btn = event.target; // The button clicked
    const originalText = btn.textContent;

    // Loading State
    btn.disabled = true;
    btn.textContent = 'Processing...';

    try {
        const response = await fetch('/admin/api/reports/resolve', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                report_id: currentReportId,
                action: action,
                admin_notes: notes
            })
        });

        const result = await response.json();

        if (result.success) {
            // Close modal
            closeModal();
            // Remove row from table
            const row = document.getElementById(`row-${currentReportId}`);
            if (row) row.remove();

            // Optional: Show toast success
            // alert('Report resolved successfully'); 
        } else {
            alert('Error: ' + (result.error || 'Failed to resolve report'));
        }

    } catch (error) {
        console.error('Error resolving:', error);
        alert('System error occurred.');
    } finally {
        btn.disabled = false;
        btn.textContent = originalText;
    }
}

// Utility: Close Modal
function closeModal() {
    document.getElementById('review-modal').classList.add('hidden');
    // Reset state
    document.getElementById('modal-loading').classList.remove('hidden');
    document.getElementById('modal-details').classList.add('hidden');
    currentReportId = null;
}

// Global scope for Close button in HTML
window.closeReviewModal = closeModal;

function escapeHtml(text) {
    if (!text) return '';
    return text
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}
