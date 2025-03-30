document.addEventListener('DOMContentLoaded', function() {
    let currentPage = 1;
    const itemsPerPage = 10;
    let applications = [];
    let filteredApplications = [];
    
    // Fetch applications
    function fetchApplications() {
        Auth.requireAuth();
        
        fetch('/api/certificates/my-applications', {
            headers: {
                'Content-Type': 'application/json'
            },
            credentials: 'same-origin'
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            applications = data;
            filteredApplications = [...applications];
            updateTable();
        })
        .catch(error => {
            console.error('Error fetching applications:', error);
            document.getElementById('applications-table-body').innerHTML = `
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-sm text-red-500">Error loading applications. Please try again later.</td>
                </tr>
            `;
        });
    }
    
    // Update table with current page of applications
    function updateTable() {
        const tableBody = document.getElementById('applications-table-body');
        const start = (currentPage - 1) * itemsPerPage;
        const end = Math.min(start + itemsPerPage, filteredApplications.length);
        const paginatedItems = filteredApplications.slice(start, end);
        
        // Update pagination info
        document.getElementById('showing-start').textContent = filteredApplications.length > 0 ? start + 1 : 0;
        document.getElementById('showing-end').textContent = end;
        document.getElementById('total-items').textContent = filteredApplications.length;
        
        // Enable/disable pagination buttons
        document.getElementById('prev-page').disabled = currentPage === 1;
        document.getElementById('next-page').disabled = end >= filteredApplications.length;
        
        if (paginatedItems.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">No applications found</td>
                </tr>
            `;
            return;
        }
        
        tableBody.innerHTML = paginatedItems.map(app => `
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${app.id}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${app.certificate_type_name}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${app.applicant_name}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${new Date(app.created_at).toLocaleDateString()}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${getStatusClass(app.status)}">
                        ${app.status.charAt(0).toUpperCase() + app.status.slice(1)}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-3 flex items-center">
                    <button onclick="viewApplication(${app.id})" class="text-indigo-600 hover:text-indigo-900" title="View Details">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                    <button onclick="editApplication(${app.id})" class="text-blue-600 hover:text-blue-900" title="Edit Application">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </button>
                    <button onclick="deleteApplication(${app.id})" class="text-red-600 hover:text-red-900" title="Delete Application">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                    ${app.status === 'pending' ? `
                        <a href="/admin/review?id=${app.id}" class="text-green-600 hover:text-green-900" title="Admin Review">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </a>
                    ` : ''}
                </td>
            </tr>
        `).join('');
    }
    
    // Get status badge class
    function getStatusClass(status) {
        switch (status) {
            case 'pending':
                return 'bg-yellow-100 text-yellow-800';
            case 'approved':
                return 'bg-green-100 text-green-800';
            case 'rejected':
                return 'bg-red-100 text-red-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    }
    
    // Event listeners for pagination
    document.getElementById('prev-page').addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            updateTable();
        }
    });
    
    document.getElementById('next-page').addEventListener('click', () => {
        const maxPage = Math.ceil(filteredApplications.length / itemsPerPage);
        if (currentPage < maxPage) {
            currentPage++;
            updateTable();
        }
    });
    
    // Filter applications by status
    document.getElementById('status-filter').addEventListener('change', function() {
        const status = this.value;
        currentPage = 1;
        
        filteredApplications = status === 'all'
            ? [...applications]
            : applications.filter(app => app.status === status);
        
        updateTable();
    });
    
    // Search applications
    document.getElementById('search').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        currentPage = 1;
        
        filteredApplications = applications.filter(app =>
            app.id.toString().includes(searchTerm) ||
            app.certificate_type_name.toLowerCase().includes(searchTerm) ||
            app.applicant_name.toLowerCase().includes(searchTerm)
        );
        
        updateTable();
    });
    
    // Initial load
    fetchApplications();
});

// View application details
function viewApplication(id) {
    const modal = document.getElementById('application-modal');
    const detailsContainer = document.getElementById('application-details');
    
    fetch(`/api/certificates/applications/${id}`, {
        headers: {
            'Content-Type': 'application/json'
        },
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(data => {
        detailsContainer.innerHTML = `
            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Application ID</dt>
                    <dd class="mt-1 text-sm text-gray-900">${data.id}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Certificate Type</dt>
                    <dd class="mt-1 text-sm text-gray-900">${data.certificate_type_name}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Applicant Name</dt>
                    <dd class="mt-1 text-sm text-gray-900">${data.applicant_name}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Submission Date</dt>
                    <dd class="mt-1 text-sm text-gray-900">${new Date(data.created_at).toLocaleDateString()}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="mt-1">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${getStatusClass(data.status)}">
                            ${data.status.charAt(0).toUpperCase() + data.status.slice(1)}
                        </span>
                    </dd>
                </div>
            </dl>
        `;
        
        modal.classList.remove('hidden');
    })
    .catch(error => {
        console.error('Error fetching application details:', error);
        alert('Failed to load application details');
    });
}

// Edit application
function editApplication(id) {
    window.location.href = `/apply?id=${id}`;
}

// Delete application
function deleteApplication(id) {
    if (!confirm('Are you sure you want to delete this application?')) {
        return;
    }
    
    fetch(`/api/certificates/applications/${id}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        },
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            throw new Error(data.error);
        }
        alert('Application deleted successfully');
        window.location.reload();
    })
    .catch(error => {
        console.error('Error deleting application:', error);
        alert('Failed to delete application');
    });
}

// Close modal handlers
document.getElementById('close-modal').addEventListener('click', () => {
    document.getElementById('application-modal').classList.add('hidden');
});

document.getElementById('close-modal-btn').addEventListener('click', () => {
    document.getElementById('application-modal').classList.add('hidden');
});