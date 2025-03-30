<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo $_SESSION['csrf_token']; ?>">
    <title>My Certificates - Government Certificate Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="/js/auth.js"></script>
    <script src="/js/logout.js"></script>
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
                        <li><a href="/applications" class="hover:text-indigo-200">My Applications</a></li>
                        <li><a href="/certificates" class="hover:text-indigo-200 font-bold">My Certificates</a></li>
                        <li><a href="/logout" class="hover:text-indigo-200">Logout</a></li>
                    </ul>
                </nav>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-grow container mx-auto px-4 py-8">
            <div class="bg-white rounded-lg shadow-md p-6 max-w-6xl mx-auto">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800">My Certificates</h2>
                </div>

                <!-- Filter and Search -->
                <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between space-y-3 md:space-y-0">
                    <div class="flex items-center space-x-4">
                        <label for="certificate-type-filter" class="text-sm font-medium text-gray-700">Certificate Type:</label>
                        <select id="certificate-type-filter" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="all">All Types</option>
                            <!-- Certificate types will be populated dynamically -->
                        </select>
                    </div>
                    <div class="relative">
                        <input type="text" id="search" placeholder="Search certificates..." class="w-full md:w-64 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>
                </div>

                <!-- Certificates Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Certificate ID</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Issue Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expiry Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="certificates-table-body">
                            <!-- Certificate rows will be populated dynamically -->
                            <!-- Example row for reference -->
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">CERT-12345</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Birth Certificate</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2023-06-15</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2033-06-15</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Valid</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="/api/certificates/generate/12345" class="text-indigo-600 hover:text-indigo-900 mr-3">Download PDF</a>
                                    <a href="/api/certificates/preview/12345" class="text-indigo-600 hover:text-indigo-900">View</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4 flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Showing <span class="font-medium">1</span> to <span class="font-medium">10</span> of <span class="font-medium">20</span> certificates
                    </div>
                    <div class="flex-1 flex justify-end">
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                            <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <span class="sr-only">Previous</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </a>
                            <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">1</a>
                            <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">2</a>
                            <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <span class="sr-only">Next</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </nav>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 py-4">
            <div class="container mx-auto px-4 text-center text-gray-500 text-sm">
                &copy; 2023 Government Certificate Management System. All rights reserved.
            </div>
        </footer>
    </div>

    <script>
        // JavaScript to fetch and display certificates
        document.addEventListener('DOMContentLoaded', function() {
            // Auth.js will handle redirecting if not authenticated
            Auth.requireAuth();
            
            // Fetch certificates from API
            fetch('/api/certificates/my-applications?status=approved', {
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
                const tableBody = document.getElementById('certificates-table-body');
                tableBody.innerHTML = ''; // Clear example row
                
                if (data.length === 0) {
                    // No certificates found
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                            No certificates found. Approved applications will appear here.
                        </td>
                    `;
                    tableBody.appendChild(row);
                } else {
                        // Populate table with certificates
                        data.forEach(cert => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${cert.certificate_number}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${cert.certificate_type}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${cert.issue_date}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${cert.expiry_date || 'N/A'}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Valid</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="/api/certificates/generate/${cert.id}" class="text-indigo-600 hover:text-indigo-900 mr-3">Download PDF</a>
                                    <a href="/api/certificates/preview/${cert.id}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                </td>
                            `;
                            tableBody.appendChild(row);
                        });
                    }
                })
                .catch(error => {
                    console.error('Error fetching certificates:', error);
                    const tableBody = document.getElementById('certificates-table-body');
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-red-500">
                                Error loading certificates. Please try again later.
                            </td>
                        </tr>
                    `;
                });
                
            // Fetch certificate types for filter
            fetch('/api/certificates/types')
                .then(response => response.json())
                .then(data => {
                    const typeFilter = document.getElementById('certificate-type-filter');
                    data.forEach(type => {
                        const option = document.createElement('option');
                        option.value = type.id;
                        option.textContent = type.name;
                        typeFilter.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching certificate types:', error);
                });
                
            // Filter functionality
            document.getElementById('certificate-type-filter').addEventListener('change', function() {
                // Implement filter logic here
            });
            
            // Search functionality
            document.getElementById('search').addEventListener('input', function() {
                // Implement search logic here
            });
        });
    </script>
</body>

</html>