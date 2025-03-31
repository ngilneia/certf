<?php
$certificateType = 'Scheduled Tribe Certificate';
$requiredDocuments = [
    'voter_id' => 'Voter ID (Xerox copy)',
    'lc_vc_cert' => 'LC/VC Certification (Original)',
    'yma_cert' => 'YMA Certification (Original)',
    'birth_cert' => 'Birth Certificate (or parent\'s Voter ID)',
    'photo' => 'Passport Size Photo (1)'
];
$applicationFee = 10.00;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scheduled Tribe Certificate Application - Government Certificate Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="/js/application_form.js"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen py-8">
        <div class="max-w-4xl mx-auto px-4">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Apply for Scheduled Tribe Certificate</h1>
            
            <form id="certificateForm" class="bg-white shadow-md rounded-lg p-6 space-y-6" method="POST" action="/api/applications/submit" enctype="multipart/form-data">
                <input type="hidden" name="certificateType" value="<?php echo htmlspecialchars($certificateType); ?>">
                <input type="hidden" name="applicationFee" value="<?php echo htmlspecialchars($applicationFee); ?>">
                
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

                <!-- Scheduled Tribe Specific Fields -->
                <div class="space-y-4">
                    <h2 class="text-xl font-semibold text-gray-800">Tribe Details</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="tribeName" class="block text-sm font-medium text-gray-700">Name of Tribe</label>
                            <input type="text" id="tribeName" name="tribeName" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label for="fatherName" class="block text-sm font-medium text-gray-700">Father's Name</label>
                            <input type="text" id="fatherName" name="fatherName" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label for="motherName" class="block text-sm font-medium text-gray-700">Mother's Name</label>
                            <input type="text" id="motherName" name="motherName" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label for="birthPlace" class="block text-sm font-medium text-gray-700">Place of Birth</label>
                            <input type="text" id="birthPlace" name="birthPlace" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>
                </div>

                <!-- Required Documents Section -->
                <div class="space-y-4">
                    <h2 class="text-xl font-semibold text-gray-800">Required Documents</h2>
                    <div class="space-y-4">
                        <?php foreach ($requiredDocuments as $id => $document): ?>
                        <div class="border rounded-md p-4">
                            <label for="<?php echo htmlspecialchars($id); ?>" class="block text-sm font-medium text-gray-700 mb-2">
                                <?php echo htmlspecialchars($document); ?>
                            </label>
                            <input type="file" id="<?php echo htmlspecialchars($id); ?>" name="documents[<?php echo htmlspecialchars($id); ?>]" required
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        </div>
                        <?php endforeach; ?>
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
                    <p class="text-sm text-gray-600">Application Fee: ₹<?php echo number_format($applicationFee, 2); ?></p>
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