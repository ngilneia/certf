<?php
// Delayed Death Registration Form Template
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delayed Death Registration Application - Government Certificate Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
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
                <h2 class="text-2xl font-semibold mb-6 text-center text-gray-800">Delayed Death Registration Application</h2>
                <div class="mb-6 bg-blue-50 border-l-4 border-blue-400 p-4">
                    <p class="text-blue-700">Fee: ₹20.00</p>
                </div>

                <form id="deathRegistrationForm" class="space-y-6" method="POST" action="/api/certificates/apply" enctype="multipart/form-data">
                    <!-- CSRF Token -->
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <input type="hidden" name="certificate_type_id" value="2"> <!-- ID for Delayed Death Registration -->

                    <!-- Deceased Person's Information -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-800">Deceased Person's Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="deceased_name" class="block text-sm font-medium text-gray-700 mb-1">Full Name of Deceased</label>
                                <input type="text" id="deceased_name" name="form_data[deceased_name]" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                            </div>
                            <div>
                                <label for="date_of_death" class="block text-sm font-medium text-gray-700 mb-1">Date of Death</label>
                                <input type="date" id="date_of_death" name="form_data[date_of_death]" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                            </div>
                            <div>
                                <label for="place_of_death" class="block text-sm font-medium text-gray-700 mb-1">Place of Death</label>
                                <input type="text" id="place_of_death" name="form_data[place_of_death]" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                            </div>
                            <div>
                                <label for="cause_of_death" class="block text-sm font-medium text-gray-700 mb-1">Cause of Death</label>
                                <select id="cause_of_death" name="form_data[cause_of_death]" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                                    <option value="">Select Cause of Death</option>
                                    <option value="natural">Natural Causes</option>
                                    <option value="accident">Accident</option>
                                    <option value="illness">Illness</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Applicant Information -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-800">Applicant's Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="applicant_name" class="block text-sm font-medium text-gray-700 mb-1">Applicant's Full Name</label>
                                <input type="text" id="applicant_name" name="applicant_name" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                            </div>
                            <div>
                                <label for="relationship" class="block text-sm font-medium text-gray-700 mb-1">Relationship with Deceased</label>
                                <input type="text" id="relationship" name="form_data[relationship]" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                            </div>
                            <div>
                                <label for="applicant_phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                <input type="tel" id="applicant_phone" name="applicant_phone" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                            </div>
                            <div>
                                <label for="applicant_email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                <input type="email" id="applicant_email" name="applicant_email" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            </div>
                        </div>
                    </div>

                    <!-- Required Documents Section -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-800">Required Documents</h3>
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                            <p class="text-sm text-yellow-700">Please upload all required documents. Accepted formats: PDF, JPG, PNG (Max size: 2MB per file)</p>
                        </div>
                        
                        <!-- Document Upload Fields -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Voter ID (2 copies - original + attested)</label>
                                <input type="file" name="documents[voter_id]" accept=".pdf,.jpg,.jpeg,.png" class="w-full" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">LC/VC Certification (2 copies - original)</label>
                                <input type="file" name="documents[lc_vc_cert]" accept=".pdf,.jpg,.jpeg,.png" class="w-full" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">YMA/YLA/YCA/MTP Certification (2 copies - original)</label>
                                <input type="file" name="documents[yma_cert]" accept=".pdf,.jpg,.jpeg,.png" class="w-full" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Deceased's Photograph (2 copies)</label>
                                <input type="file" name="documents[deceased_photo]" accept=".jpg,.jpeg,.png" class="w-full" required>
                            </div>
                            <div id="police_report_section" class="hidden">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Police General Diary Entry (2 attested copies - for unnatural deaths)</label>
                                <input type="file" name="documents[police_report]" accept=".pdf,.jpg,.jpeg,.png" class="w-full">
                            </div>
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

    <!-- Document Preview Modal -->
    <div id="previewModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium">Document Preview</h3>
                <button onclick="closePreview()" class="text-gray-500 hover:text-gray-700">&times;</button>
            </div>
            <div id="previewContent" class="max-h-96 overflow-auto"></div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Show/hide police report field based on cause of death
        const causeSelect = document.getElementById('cause_of_death');
        const policeReportSection = document.getElementById('police_report_section');

        causeSelect.addEventListener('change', function() {
            if (this.value === 'accident' || this.value === 'other') {
                policeReportSection.classList.remove('hidden');
                policeReportSection.querySelector('input').required = true;
            } else {
                policeReportSection.classList.add('hidden');
                policeReportSection.querySelector('input').required = false;
            }
        });

        // Preview functionality for uploaded documents
        const fileInputs = document.querySelectorAll('input[type="file"]');
        fileInputs.forEach(input => {
            input.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const file = this.files[0];
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            showPreview(e.target.result, 'image');
                        };
                        reader.readAsDataURL(file);
                    } else if (file.type === 'application/pdf') {
                        showPreview(URL.createObjectURL(file), 'pdf');
                    }
                }
            });
        });
    });

    function showPreview(url, type) {
        const modal = document.getElementById('previewModal');
        const content = document.getElementById('previewContent');
        
        content.innerHTML = type === 'image' 
            ? `<img src="${url}" class="max-w-full h-auto">` 
            : `<iframe src="${url}" class="w-full h-96"></iframe>`;
            
        modal.classList.remove('hidden');
    }

    function closePreview() {
        document.getElementById('previewModal').classList.add('hidden');
    }
    </script>
</body>
</html>