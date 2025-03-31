<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate Application Form - Government Certificate Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="/js/application_form.js"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen py-8">
        <div class="max-w-4xl mx-auto px-4">
            <h1 class="text-3xl font-bold text-gray-900 mb-8" id="formTitle">Apply for Certificate</h1>
            
            <form id="certificateForm" class="bg-white shadow-md rounded-lg p-6 space-y-6" method="POST" action="/api/applications/submit" enctype="multipart/form-data">
                <input type="hidden" id="certificateType" name="certificateType" value="">
                
                <!-- Personal Information Section -->
                <div class="space-y-4">
                    <h2 class="text-xl font-semibold text-gray-800">Personal Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="applicantName" class="block text-sm font-medium text-gray-700">Full Name</label>
                            <input type="text" id="applicantName" name="applicantName" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label for="applicantEmail" class="block text-sm font-medium text-gray-700">Email Address</label>
                            <input type="email" id="applicantEmail" name="applicantEmail" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label for="applicantPhone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                            <input type="tel" id="applicantPhone" name="applicantPhone" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label for="applicantAddress" class="block text-sm font-medium text-gray-700">Address</label>
                            <input type="text" id="applicantAddress" name="applicantAddress" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>
                </div>

                <!-- Certificate Specific Fields Section -->
                <div class="space-y-4">
                    <h2 class="text-xl font-semibold text-gray-800">Certificate Details</h2>
                    <div id="certificateSpecificFields" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Dynamic fields will be inserted here based on certificate type -->
                    </div>
                </div>

                <!-- Required Documents Section -->
                <div class="space-y-4">
                    <h2 class="text-xl font-semibold text-gray-800">Required Documents</h2>
                    <div id="requiredDocuments" class="space-y-4">
                        <!-- Dynamic document upload fields will be inserted here based on certificate type -->
                    </div>
                </div>

                <!-- Declaration Section -->
                <div class="space-y-4">
                    <h2 class="text-xl font-semibold text-gray-800">Declaration</h2>
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="declaration" name="declaration" type="checkbox" required
                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="declaration" class="font-medium text-gray-700">I hereby declare that all the information provided above is true and correct to the best of my knowledge.</label>
                        </div>
                    </div>
                </div>

                <!-- Fee Information -->
                <div class="bg-gray-50 p-4 rounded-md">
                    <p class="text-sm text-gray-600">Application Fee: <span id="applicationFee">₹0.00</span></p>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Submit Application
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>