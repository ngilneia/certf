<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Applications Management</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="hidden md:flex md:flex-shrink-0">
            <div class="flex flex-col w-64 bg-indigo-800">
                <!-- Sidebar Header -->
                <div class="flex items-center justify-center h-16 bg-indigo-900">
                    <span class="text-white text-2xl font-bold">Admin Panel</span>
                </div>
                <!-- Sidebar Navigation -->
                <nav class="mt-5 flex-1 px-2 space-y-1">
                    <a href="/admin/dashboard" class="text-indigo-100 hover:bg-indigo-600 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <svg class="mr-3 h-6 w-6 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Dashboard
                    </a>
                    <a href="/admin/applications" class="bg-indigo-900 text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <svg class="mr-3 h-6 w-6 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Applications
                    </a>
                    <a href="/admin/certificates" class="text-indigo-100 hover:bg-indigo-600 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <svg class="mr-3 h-6 w-6 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                        </svg>
                        Certificates
                    </a>
                    <a href="/admin/users" class="text-indigo-100 hover:bg-indigo-600 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <svg class="mr-3 h-6 w-6 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        Users
                    </a>
                    <a href="/admin/settings" class="text-indigo-100 hover:bg-indigo-600 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <svg class="mr-3 h-6 w-6 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Settings
                    </a>
                </nav>
                <!-- User Profile -->
                <div class="flex-shrink-0 flex border-t border-indigo-700 p-4">
                    <div class="flex items-center">
                        <div>
                            <button class="flex-shrink-0 group block">
                                <div class="flex items-center">
                                    <div>
                                        <img class="inline-block h-9 w-9 rounded-full" src="https://ui-avatars.com/api/?name=Admin&background=6366f1&color=fff" alt="Profile">
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-white">Admin User</p>
                                        <p class="text-xs font-medium text-indigo-200 group-hover:text-white">View profile</p>
                                    </div>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex flex-col w-0 flex-1 overflow-hidden">
            <!-- Top Navigation -->
            <div class="relative z-10 flex-shrink-0 flex h-16 bg-white shadow">
                <button class="px-4 border-r border-gray-200 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 md:hidden">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                    </svg>
                </button>
                <div class="flex-1 px-4 flex justify-between">
                    <div class="flex-1 flex">
                        <h1 class="text-2xl font-semibold text-gray-900 my-auto">Applications Management</h1>
                    </div>
                    <div class="ml-4 flex items-center md:ml-6">
                        <button class="bg-white p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <span class="sr-only">View notifications</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <main class="flex-1 relative overflow-y-auto focus:outline-none">
                <div class="py-6">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
                        <!-- Filter and Search -->
                        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between space-y-3 md:space-y-0">
                            <div class="flex items-center space-x-4">
                                <label for="status-filter" class="text-sm font-medium text-gray-700">Status:</label>
                                <select id="status-filter" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="all">All</option>
                                    <option value="pending" selected>Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                            </div>
                            <div class="flex items-center space-x-4">
                                <label for="certificate-type-filter" class="text-sm font-medium text-gray-700">Certificate Type:</label>
                                <select id="certificate-type-filter" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="all">All Types</option>
                                    <!-- Certificate types will be populated via JavaScript -->
                                </select>
                            </div>
                            <div class="relative">
                                <input type="text" id="search" placeholder="Search applications..." class="w-full md:w-64 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                        </div>

                        <!-- Applications Table -->
                        <div class="bg-white shadow overflow-hidden sm:rounded-md">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Certificate Type</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applicant Name</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submission Date</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="applications-table-body" class="bg-white divide-y divide-gray-200">
                                    <!-- Applications will be loaded here via JavaScript -->
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">Loading applications...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4 flex items-center justify-between">
                            <div class="text-sm text-gray-700">
                                Showing <span id="showing-start">1</span> to <span id="showing-end">10</span> of <span id="total-items">0</span> applications
                            </div>
                            <div class="flex space-x-2">
                                <button id="prev-page" class="px-3 py-1 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                    Previous
                                </button>
                                <button id="next-page" class="px-3 py-1 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                    Next
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Application Review Modal -->
    <div id="application-modal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-screen overflow-y-auto">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center sticky top-0 bg-white z-10">
                <h3 class="text-lg font-medium text-gray-900">Application Review</h3>
                <button id="close-modal" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div id="application-details" class="px-6 py-4">
                <!-- Application details will be loaded here -->
            </div>
            <div class="px-6 py-4 border-t border-gray-200 flex justify-between sticky bottom-0 bg-white z-10">
                <div>
                    <button id="reject-btn" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        Reject Application
                    </button>
                </div>
                <div class="flex space-x-3">
                    <button id="close-modal-btn" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Close
                    </button>
                    <button id="approve-btn" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Approve Application
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Rejection Reason Modal -->
    <div id="rejection-modal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-lg w-full">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Rejection Reason</h3>
            </div>
            <div class="px-6 py-4">
                <label for="rejection-reason" class="block text-sm font-medium text-gray-700 mb-2">Please provide a reason for rejection:</label>
                <textarea id="rejection-reason" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter rejection reason..."></textarea>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                <button id="cancel-rejection" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancel
                </button>
                <button id="confirm-rejection" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Confirm Rejection
                </button>
            </div>
        </div>
    </div>

    <!-- JavaScript for Admin Applications -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let currentPage = 1;
            let itemsPerPage = 10;
            let applications = [];
            let filteredApplications = [];
            let currentApplicationId = null;
            
            // Fetch certificate types for filter
            fetch('/api/certificates/types')
                .then(response => response.json())
                .then(data => {
                    const selectElement = document.getElementById('certificate-type-filter');
                    data.forEach(type => {
                        const option = document.createElement('option');
                        option.value = type.id;
                        option.textContent = type.name;
                        selectElement.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching certificate types:', error));
            
            // Fetch applications
            function fetchApplications() {
                fetch('/api/admin/applications')
                    .then(response => response.json())
                    .then(data => {
                        applications = data;
                        applyFilters();
                    })
                    .catch(error => {
                        console.error('Error fetching applications:', error);
                        document.getElementById('applications-table-body').innerHTML = `
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-red-500">Error loading applications. Please try again.</td>
                            </tr>
                        `;
                    });
            }
            
            // Apply filters and update table
            function applyFilters() {
                const statusFilter = document.getElementById('status-filter').value;
                const typeFilter = document.getElementById('certificate-type-filter').value;
                const searchTerm = document.getElementById('search').value.toLowerCase();
                
                filteredApplications = applications.filter(app => {
                    const matchesStatus = statusFilter === 'all' || app.status === statusFilter;
                    const matchesType = typeFilter === 'all' || app.certificate_type_id.toString() === typeFilter;
                    const matchesSearch = searchTerm === '' || 
                        app.applicant_name.toLowerCase().includes(searchTerm) ||
                        app.certificate_type_name.toLowerCase().includes(searchTerm) ||
                        app.id.toString().includes(searchTerm);
                    
                    return matchesStatus && matchesType && matchesSearch;
                });
                
                currentPage = 1;
                updateTable();
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
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">No applications found.</td>
                        </tr>
                    `;
                    return;
                }
                
                tableBody.innerHTML = '';
                paginatedItems.forEach(app => {
                    const statusClass = {
                        'pending': 'bg-yellow-100 text-yellow-800',
                        'approved': 'bg-green-100 text-green-800',
                        'rejected': 'bg-red-100 text-red-800'
                    }[app.status] || 'bg-gray-100 text-gray-800';
                    
                    const row = document.createElement('tr');
                    row.className = 'hover:bg-gray-50';
                    row.innerHTML = `
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${app.id}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${app.certificate_type_name}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${app.applicant_name}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${new Date(app.created_at).toLocaleDateString()}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}">
                                ${app.status.charAt(0).toUpperCase() + app.status.slice(1)}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button data-id="${app.id}" class="review-application text-indigo-600 hover:text-indigo-900">Review</button>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
                
                // Add event listeners to review buttons
                document.querySelectorAll('.review-application').forEach(button => {
                    button.addEventListener('click', function() {
                        const appId = this.getAttribute('data-id');
                        currentApplicationId = appId;
                        showApplicationDetails(appId);
                    });
                });
            }
            
            // Show application details in modal
            function showApplicationDetails(appId) {
                fetch(`/api/admin/applications/${appId}`)
                    .then(response => response.json())
                    .then(app => {
                        const detailsContainer = document.getElementById('application-details');
                        
                        // Parse JSON data
                        const formData = typeof app.form_data === 'string' ? JSON.parse(app.form_data) : app.form_data;
                        const documents = typeof app.documents === 'string' ? JSON.parse(app.documents) : app.documents;
                        const requiredDocs = typeof app.required_documents === 'string' ? JSON.parse(app.required_documents) : app.required_documents;
                        
                        // Build details HTML
                        let detailsHTML = `
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Application ID</h4>
                                    <p class="mt-1 text-sm text-gray-900">${app.id}</p>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Certificate Type</h4>
                                    <p class="mt-1 text-sm text-gray-900">${app.certificate_type_name}</p>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Status</h4>
                                    <p class="mt-1 text-sm text-gray-900">${app.status.charAt(0).toUpperCase() + app.status.slice(1)}</p>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Submission Date</h4>
                                    <p class="mt-1 text-sm text-gray-900">${new Date(app.created_at).toLocaleString()}</p>
                                </div>
                            </div>
                            
                            <h4 class="text-lg font-medium text-gray-900 mb-3">Applicant Information</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Name</h4>
                                    <p class="mt-1 text-sm text-gray-900">${app.applicant_name}</p>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Father's Name</h4>
                                    <p class="mt-1 text-sm text-gray-900">${formData.father_name || 'N/A'}</p>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Phone</h4>
                                    <p class="mt-1 text-sm text-gray-900">${app.applicant_phone || 'N/A'}</p>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Email</h4>
                                    <p class="mt-1 text-sm text-gray-900">${app.applicant_email || 'N/A'}</p>
                                </div>
                            </div>
                            
                            <h4 class="text-lg font-medium text-gray-900 mb-3">Address</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Address Line 1</h4>
                                    <p class="mt-1 text-sm text-gray-900">${formData.address_line1 || 'N/A'}</p>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Address Line 2</h4>
                                    <p class="mt-1 text-sm text-gray-900">${formData.address_