<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Review - Government Certificate Management System</title>
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
                        <h1 class="text-2xl font-semibold text-gray-900 my-auto">Application Review</h1>
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
                        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                            <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                                <div>
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">Application #<span id="application-id"></span></h3>
                                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Submitted on <span id="submission-date"></span></p>
                                </div>
                                <div>
                                    <span id="status-badge" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                </div>
                            </div>
                            <div class="border-t border-gray-200">
                                <dl>
                                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Certificate Type</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2" id="certificate-type"></dd>
                                    </div>
                                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Applicant Name</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2" id="applicant-name"></dd>
                                    </div>
                                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Contact Information</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            <p>Email: <span id="applicant-email"></span></p>
                                            <p>Phone: <span id="applicant-phone"></span></p>
                                        </dd>
                                    </div>
                                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Submitted By</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2" id="submitted-by"></dd>
                                    </div>
                                </dl>
                            </div>

                            <!-- Application Details -->
                            <div class="px-4 py-5 sm:px-6 border-t border-gray-200">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Application Details</h3>
                            </div>
                            <div class="border-t border-gray-200 px-4 py-5 sm:px-6" id="application-details">
                                <!-- Application details will be loaded here via JavaScript -->
                            </div>

                            <!-- Uploaded Documents -->
                            <div class="px-4 py-5 sm:px-6 border-t border-gray-200">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Uploaded Documents</h3>
                            </div>
                            <div class="border-t border-gray-200">
                                <ul class="divide-y divide-gray-200" id="documents-list">
                                    <!-- Documents will be loaded here via JavaScript -->
                                </ul>
                            </div>

                            <!-- Review Form -->
                            <div class="px-4 py-5 sm:px-6 border-t border-gray-200">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Application Review</h3>
                            </div>
                            <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                                <form id="review-form">
                                    <div class="mb-4">
                                        <label for="review-comments" class="block text-sm font-medium text-gray-700 mb-1">Review Comments</label>
                                        <textarea id="review-comments" name="comments" rows="4" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"></textarea>
                                    </div>
                                    <div class="flex justify-end space-x-3">
                                        <button type="button" id="reject-button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            Reject Application
                                        </button>
                                        <button type="button" id="approve-button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                            Approve Application
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get application ID from URL
            const urlParams = new URLSearchParams(window.location.search);
            const applicationId = urlParams.get('id');
            
            if (!applicationId) {
                alert('Application ID is required');
                window.location.href = '/admin/applications';
                return;
            }
            
            // Load application details
            fetch(`/api/admin/applications/${applicationId}`)
                .then(response => response.json())
                .then(data => {
                    // Populate application details
                    document.getElementById('application-id').textContent = data.id;
                    document.getElementById('submission-date').textContent = new Date(data.created_at).toLocaleDateString();
                    document.getElementById('certificate-type').textContent = data.certificate_type_name;
                    document.getElementById('applicant-name').textContent = data.applicant_name;
                    document.getElementById('applicant-email').textContent = data.applicant_email || 'N/A';
                    document.getElementById('applicant-phone').textContent = data.applicant_phone || 'N/A';
                    document.getElementById('submitted-by').textContent = data.submitted_by_name;
                    
                    // Update status badge
                    const statusBadge = document.getElementById('status-badge');
                    if (data.status === 'pending') {
                        statusBadge.className = 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800';
                        statusBadge.textContent = 'Pending';
                    } else if (data.status === 'approved') {
                        statusBadge.className = 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800';
                        statusBadge.textContent = 'Approved';
                        // Disable review form if already approved
                        document.getElementById('review-comments').disabled = true;
                        document.getElementById('approve-button').disabled = true;
                        document.getElementById('reject-button').disabled = true;
                    } else if (data.status === 'rejected') {
                        statusBadge.className = 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800';
                        statusBadge.textContent = 'Rejected';
                        // Disable review form if already rejected
                        document.getElementById('review-comments').disabled = true;
                        document.getElementById('approve-button').disabled = true;
                        document.getElementById('reject-button').disabled = true;
                    }
                    
                    // Populate form data
                    const formData = JSON.parse(data.form_data);
                    const detailsContainer = document.getElementById('application-details');
                    let detailsHTML = '<dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">';
                    
                    for (const [key, value] of Object.entries(formData)) {
                        const formattedKey = key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                        detailsHTML += `
                            <div>
                                <dt class="text-sm font-medium text-gray-500">${formattedKey}</dt>
                                <dd class="mt-1 text-sm text-gray-900">${value}</dd>
                            </div>
                        `;
                    }
                    
                    detailsHTML += '</dl>';
                    detailsContainer.innerHTML = detailsHTML;
                    
                    // Populate documents
                    const documents = JSON.parse(data.documents);
                    const documentsList = document.getElementById('documents-list');
                    
                    if (Object.keys(documents).length === 0) {
                        documentsList.innerHTML = '<li class="px-4 py-3 text-sm text-gray-500">No documents uploaded</li>';
                    } else {
                        let documentsHTML = '';
                        for (const [docType, filename] of Object.entries(documents)) {
                            const formattedDocType = docType.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                            documentsHTML += `
                                <li class="px-4 py-3 flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">${formattedDocType}</p>
                                    </div>
                                    <div>
                                        <a href="/uploads/${filename}" target="_blank" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">View Document</a>
                                    </div>
                                </li>
                            `;
                        }
                        documentsList.innerHTML = documentsHTML;
                    }
                    
                    // Set review comments if any
                    if (data.review_comments) {
                        document.getElementById('review-comments').value = data.review_comments;
                    }
                })
                .catch(error => {
                    console.error('Error loading application:', error);
                    alert('Failed to load application details');
                });
            
            // Handle approve button click
            document.getElementById('approve-button').addEventListener('click', function() {
                const comments = document.getElementById('review-comments').value;
                
                fetch(`/api/admin/applications/${applicationId}/approve`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ comments: comments })
                })
                .then(response => response.json())
                .then(data => {
                    alert('Application approved successfully');
                    window.location.reload();
                })
                .catch(error => {
                    console.error('Error approving application:', error);
                    alert('Failed to approve application');
                });
            });
            
            // Handle reject button click
            document.getElementById('reject-button').addEventListener('click', function() {
                const comments = document.getElementById('review-comments').value;
                
                if (!comments) {
                    alert('Please provide rejection reason in the comments');
                    return;
                }
                
                fetch(`/api/admin/applications/${applicationId}/reject`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ comments: comments })
                })
                .then(response => response.json())
                .then(data => {
                    alert('Application rejected successfully');
                    window.location.reload();
                })
                .catch(error => {
                    console.error('Error rejecting application:', error);
                    alert('Failed to reject application');
                });
            });
        });
    </script>
</body>

</html>