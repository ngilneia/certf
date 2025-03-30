<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo $_SESSION['csrf_token']; ?>">
    <title>My Applications - Government Certificate Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="/js/auth.js"></script>
    <script src="/js/logout.js"></script>
    <script src="/js/applications_list.js"></script>
</head>

<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-indigo-800 text-white shadow-lg">
            <div class="container mx-auto px-4 py-4 flex justify-between items-center">
                <h1 class="text-2xl font-bold">Government Certificate Management System</h1>
                <nav>
                    <ul class="flex space-x-4">
                        <li><a href="/dashboard" class="hover:text-indigo-200">Dashboard</a></li>
                        <li><a href="/applications" class="hover:text-indigo-200 font-bold">My Applications</a></li>
                        <li><a href="/logout" class="hover:text-indigo-200">Logout</a></li>
                    </ul>
                </nav>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-grow container mx-auto px-4 py-8">
            <div class="bg-white rounded-lg shadow-md p-6 max-w-6xl mx-auto">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800">My Applications</h2>
                    <a href="/apply" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        New Application
                    </a>
                </div>

                <!-- Filter and Search -->
                <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between space-y-3 md:space-y-0">
                    <div class="flex items-center space-x-4">
                        <label for="status-filter" class="text-sm font-medium text-gray-700">Status:</label>
                        <select id="status-filter" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="all">All</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                    <div class="relative">
                        <input type="text" id="search" placeholder="Search applications..." class="w-full md:w-64 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>
                </div>

                <!-- Applications Table -->
                <div class="overflow-x-auto">
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
        </main>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white py-6">
            <div class="container mx-auto px-4">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="mb-4 md:mb-0">
                        <p>&copy; 2023 Government Certificate Management System. All rights reserved.</p>
                    </div>
                    <div>
                        <ul class="flex space-x-4">
                            <li><a href="/privacy-policy" class="hover:text-indigo-300">Privacy Policy</a></li>
                            <li><a href="/terms-of-service" class="hover:text-indigo-300">Terms of Service</a></li>
                            <li><a href="/contact" class="hover:text-indigo-300">Contact</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Application Details Modal -->
    <div id="application-modal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-screen overflow-y-auto">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">Application Details</h3>
                <button id="close-modal" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div id="application-details" class="px-6 py-4">
                <!-- Application details will be loaded here -->
            </div>
            <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                <button id="close-modal-btn" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Close
                </button>
                <a id="download-certificate-btn" href="#" class="hidden px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Download Certificate
                </a>
            </div>
        </div>
    </div>

    <!-- JavaScript for Applications List -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let currentPage = 1;
            const itemsPerPage = 10;
            let applications = [];
            let filteredApplications = [];
            
            // Fetch applications
            function fetchApplications() {
                // Auth.js will handle redirecting if not authenticated
                Auth.requireAuth();
                
                fetch('/api/certificates/my-applications', {
                    headers: {
                        'Content-Type': 'application/json'
                        // Auth.js will automatically add Authorization and CSRF headers
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
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex space-x-2">
                            <button data-id="${app.id}" class="view-details text-indigo-600 hover:text-indigo-900">View Details</button>
                            ${app.status === 'pending' ? `
                                <button data-id="${app.id}" class="update-application text-blue-600 hover:text-blue-900">Update</button>
                                <button data-id="${app.id}" class="delete-application text-red-600 hover:text-red-900">Delete</button>
                            ` : ''}
                            ${app.status === 'approved' ? `<a href="/api/certificates/generate/${app.id}" class="text-green-600 hover:text-green-900" target="_blank">Download</a>` : ''}
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
                
                // Add event listeners to view details buttons
                document.querySelectorAll('.view-details').forEach(button => {
                    button.addEventListener('click', function() {
                        const appId = this.getAttribute('data-id');
                        showApplicationDetails(appId);
                    });
                });
                
                // Setup update and delete handlers
                setupUpdateDeleteHandlers();
            }
            
            // Show application details in modal
            function showApplicationDetails(appId) {
                const token = localStorage.getItem('token');
                fetch(`/api/certificates/applications/${appId}`, {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': '<?php echo $_SESSION["csrf_token"]; ?>'
                    },
                    credentials: 'same-origin'
                })
                    .then(response => response.json())
                    .then(app => {
                        const detailsContainer = document.getElementById('application-details');
                        const downloadBtn = document.getElementById('download-certificate-btn');
                        
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
                                    <p class="mt-1 text-sm text-gray-900">${formData.address_line2 || 'N/A'}</p>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">City/Village</h4>
                                    <p class="mt-1 text-sm text-gray-900">${formData.city || 'N/A'}</p>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">District</h4>
                                    <p class="mt-1 text-sm text-gray-900">${formData.district || 'N/A'}</p>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">State</h4>
                                    <p class="mt-1 text-sm text-gray-900">${formData.state || 'N/A'}</p>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Pincode</h4>
                                    <p class="mt-1 text-sm text-gray-900">${formData.pincode || 'N/A'}</p>
                                </div>
                            </div>
                        `;
                        
                        // Add documents section if available
                        if (requiredDocs && requiredDocs.length > 0) {
                            detailsHTML += `<h4 class="text-lg font-medium text-gray-900 mb-3">Uploaded Documents</h4><div class="space-y-3 mb-6">`;
                            
                            requiredDocs.forEach(doc => {
                                // Check if the document exists in the documents object
                                // Convert document keys to lowercase for case-insensitive comparison
                                let docFile = null;
                                if (documents) {
                                    // Try to find the document with exact key match first
                                    if (documents[doc]) {
                                        docFile = documents[doc];
                                    } else {
                                        // Try to find the document with case-insensitive match
                                        const docLower = doc.toLowerCase().trim();
                                        for (const key in documents) {
                                            if (key && documents[key] && key.toLowerCase().trim() === docLower) {
                                                docFile = documents[key];
                                                break;
                                            }
                                        }
                                    }
                                }
                                
                                // Debug document matching
                                console.log('Document required:', doc);
                                console.log('Document found:', docFile);
                                console.log('All documents:', documents);
                                
                                detailsHTML += `
                                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-md">
                                        <span class="text-sm text-gray-900">${doc}</span>
                                        ${docFile ? `<a href="/public/uploads/${docFile}" target="_blank" class="text-sm text-indigo-600 hover:text-indigo-900">View Document</a>` : '<span class="text-sm text-red-500">Not uploaded</span>'}
                                    </div>
                                `;
                            });
                            
                            detailsHTML += `</div>`;
                        }
                        
                        // Add review comments if available
                        if (app.review_comments) {
                            detailsHTML += `
                                <h4 class="text-lg font-medium text-gray-900 mb-3">Review Comments</h4>
                                <div class="p-3 bg-gray-50 rounded-md mb-6">
                                    <p class="text-sm text-gray-900">${app.review_comments}</p>
                                </div>
                            `;
                        }
                        
                        // Update modal content
                        detailsContainer.innerHTML = detailsHTML;
                        
                        // Show/hide download button based on status
                        if (app.status === 'approved') {
                            downloadBtn.href = `/api/certificates/generate/${app.id}`;
                            downloadBtn.classList.remove('hidden');
                        } else {
                            downloadBtn.classList.add('hidden');
                        }
                        
                        // Show modal
                        document.getElementById('application-modal').classList.remove('hidden');
                    })
                    .catch(error => {
                        console.error('Error fetching application details:', error);
                        alert('Error loading application details. Please try again.');
                    });
            }
            
            // Filter applications by status
            document.getElementById('status-filter').addEventListener('change', function() {
                const status = this.value;
                if (status === 'all') {
                    filteredApplications = [...applications];
                } else {
                    filteredApplications = applications.filter(app => app.status === status);
                }
                currentPage = 1;
                updateTable();
            });
            
            // Search applications
            document.getElementById('search').addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                if (searchTerm === '') {
                    filteredApplications = [...applications];
                } else {
                    filteredApplications = applications.filter(app => 
                        app.applicant_name.toLowerCase().includes(searchTerm) ||
                        app.certificate_type_name.toLowerCase().includes(searchTerm) ||
                        app.id.toString().includes(searchTerm)
                    );
                }
                currentPage = 1;
                updateTable();
            });
            
            // Pagination handlers
            document.getElementById('prev-page').addEventListener('click', function() {
                if (currentPage > 1) {
                    currentPage--;
                    updateTable();
                }
            });
            
            document.getElementById('next-page').addEventListener('click', function() {
                const totalPages = Math.ceil(filteredApplications.length / itemsPerPage);
                if (currentPage < totalPages) {
                    currentPage++;
                    updateTable();
                }
            });
            
            // Modal close handlers
            document.getElementById('close-modal').addEventListener('click', function() {
                document.getElementById('application-modal').classList.add('hidden');
            });
            
            document.getElementById('close-modal-btn').addEventListener('click', function() {
                document.getElementById('application-modal').classList.add('hidden');
            });
            
            // Handle update application
            function setupUpdateDeleteHandlers() {
                // Update application handlers
                document.querySelectorAll('.update-application').forEach(button => {
                    button.addEventListener('click', function() {
                        const appId = this.getAttribute('data-id');
                        // Redirect to application form with application ID
                        window.location.href = `/apply?edit=${appId}`;
                    });
                });
                
                // Delete application handlers
                document.querySelectorAll('.delete-application').forEach(button => {
                    button.addEventListener('click', function() {
                        const appId = this.getAttribute('data-id');
                        if (confirm('Are you sure you want to delete this application? This action cannot be undone.')) {
                            deleteApplication(appId);
                        }
                    });
                });
            }
            
            // Delete application
            function deleteApplication(appId) {
                const token = localStorage.getItem('token');
                fetch(`/api/certificates/applications/${appId}`, {
                    method: 'DELETE',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': '<?php echo $_SESSION["csrf_token"]; ?>'
                    },
                    credentials: 'same-origin'
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to delete application');
                    }
                    return response.json();
                })
                .then(data => {
                    alert('Application deleted successfully');
                    // Refresh applications list
                    fetchApplications();
                })
                .catch(error => {
                    console.error('Error deleting application:', error);
                    alert('Error deleting application. Please try again.');
                });
            }
            
            // Initial load
            fetchApplications();
        });
    </script>
</body>

</html>