<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate Application Form - Government Certificate Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="/js/application_form.js"></script>
    <meta name="csrf-token" content="<?php echo $_SESSION['csrf_token']; ?>">
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
                        <li><a href="/logout" class="hover:text-indigo-200">Logout</a></li>
                    </ul>
                </nav>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-grow container mx-auto px-4 py-8">
            <div class="bg-white rounded-lg shadow-md p-6 max-w-4xl mx-auto">
                <h2 class="text-2xl font-semibold mb-6 text-center text-gray-800">Certificate Application Form</h2>

                <form id="certificateForm" class="space-y-6" method="POST" action="/api/certificates/apply" enctype="multipart/form-data">
                    <!-- CSRF Token -->
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <!-- Certificate Type Selection -->
                    <div class="mb-6">
                        <label for="certificate_type_id" class="block text-sm font-medium text-gray-700 mb-1">Certificate Type</label>
                        <select id="certificate_type_id" name="certificate_type_id" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                            <option value="">Select Certificate Type</option>
                            <!-- Certificate types will be populated via JavaScript -->
                        </select>
                        <p class="mt-2 text-sm text-gray-500">Fee: <span id="certificate_fee">₹0</span></p>
                    </div>

                    <!-- Basic Applicant Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="applicant_name" class="block text-sm font-medium text-gray-700 mb-1">Applicant Name</label>
                            <input type="text" id="applicant_name" name="applicant_name" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                        </div>
                        <div>
                            <label for="father_name" class="block text-sm font-medium text-gray-700 mb-1">Father's Name</label>
                            <input type="text" id="father_name" name="form_data[father_name]" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                        </div>
                        <div>
                            <label for="applicant_phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                            <input type="tel" id="applicant_phone" name="applicant_phone" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                        </div>
                        <div>
                            <label for="applicant_email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                            <input type="email" id="applicant_email" name="applicant_email" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>

                    <!-- Address Information -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-800">Address Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="address_line1" class="block text-sm font-medium text-gray-700 mb-1">Address Line 1</label>
                                <input type="text" id="address_line1" name="form_data[address_line1]" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                            </div>
                            <div>
                                <label for="address_line2" class="block text-sm font-medium text-gray-700 mb-1">Address Line 2</label>
                                <input type="text" id="address_line2" name="form_data[address_line2]" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City/Village</label>
                                <input type="text" id="city" name="form_data[city]" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                            </div>
                            <div>
                                <label for="district" class="block text-sm font-medium text-gray-700 mb-1">District</label>
                                <input type="text" id="district" name="form_data[district]" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                            </div>
                            <div>
                                <label for="state" class="block text-sm font-medium text-gray-700 mb-1">State</label>
                                <input type="text" id="state" name="form_data[state]" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                            </div>
                            <div>
                                <label for="pincode" class="block text-sm font-medium text-gray-700 mb-1">Pincode</label>
                                <input type="text" id="pincode" name="form_data[pincode]" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                            </div>
                        </div>
                    </div>

                    <!-- Required Documents Section -->
                    <div id="documents_section" class="space-y-4 hidden">
                        <h3 class="text-lg font-medium text-gray-800">Required Documents</h3>
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        Please upload all required documents. Accepted formats: PDF, JPG, PNG (Max size: 2MB per file)
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div id="document_list" class="space-y-4">
                            <!-- Document upload fields will be dynamically added here -->
                        </div>
                    </div>

                    <!-- Additional Information Section -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-800">Additional Information</h3>
                        <div>
                            <label for="additional_info" class="block text-sm font-medium text-gray-700 mb-1">Any additional information or special requests</label>
                            <textarea id="additional_info" name="form_data[additional_info]" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                        </div>
                    </div>

                    <!-- Declaration -->
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="declaration" name="declaration" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" required>
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="declaration" class="font-medium text-gray-700">Declaration</label>
                                <p class="text-gray-500">I hereby declare that all the information provided by me is true to the best of my knowledge and belief. I understand that in case any information is found to be false, my application may be rejected.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-center">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Submit Application
                        </button>
                    </div>
                </form>
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

    <!-- JavaScript for Form Handling -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fetch certificate types
            fetch('/api/certificates/types')
                .then(response => response.json())
                .then(data => {
                    const selectElement = document.getElementById('certificate_type_id');
                    data.forEach(type => {
                        const option = document.createElement('option');
                        option.value = type.id;
                        option.textContent = `${type.name} (₹${type.fee})`;
                        option.dataset.fee = type.fee;
                        option.dataset.documents = type.required_documents;
                        selectElement.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching certificate types:', error));

            // Handle certificate type selection
            document.getElementById('certificate_type_id').addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const fee = selectedOption.dataset.fee || 0;
                const documentsSection = document.getElementById('documents_section');
                const documentList = document.getElementById('document_list');
                
                // Update fee display
                document.getElementById('certificate_fee').textContent = `₹${fee}`;
                
                // Clear previous document fields
                documentList.innerHTML = '';
                
                if (this.value) {
                    // Show documents section
                    documentsSection.classList.remove('hidden');
                    
                    // Parse required documents
                    try {
                        const requiredDocs = JSON.parse(selectedOption.dataset.documents);
                        requiredDocs.forEach((doc, index) => {
                            const docField = document.createElement('div');
                            docField.innerHTML = `
                                <label for="doc_${index}" class="block text-sm font-medium text-gray-700 mb-1">${doc}</label>
                                <input type="file" id="doc_${index}" name="documents[${doc}]" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                            `;
                            documentList.appendChild(docField);
                        });
                    } catch (e) {
                        console.error('Error parsing required documents:', e);
                    }
                } else {
                    // Hide documents section if no certificate type selected
                    documentsSection.classList.add('hidden');
                }
            });

            // Form submission handling
            document.getElementById('certificateForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const csrfToken = document.querySelector('input[name="csrf_token"]').value;
                
                // Submit the form data
                fetch('/api/certificates/apply', {
                    method: 'POST',
                    // Don't set Content-Type header when using FormData with files
                    // The browser will automatically set the correct multipart/form-data with boundary
                    headers: {
                        'X-CSRF-Token': csrfToken
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert('Error: ' + data.error);
                    } else {
                        alert('Application submitted successfully!');
                        // Redirect to application details or list
                        window.location.href = '/applications';
                    }
                })
                .catch(error => {
                    console.error('Error submitting application:', error);
                    alert('An error occurred while submitting your application. Please try again.');
                });
            });
        });
    </script>
</body>

</html>