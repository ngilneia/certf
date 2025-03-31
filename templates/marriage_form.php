<?php
$certificateType = 'Marriage Certificate';
$requiredDocuments = [
    'spouse1_voter_id' => 'Voter ID (Xerox copy) - Spouse 1',
    'spouse2_voter_id' => 'Voter ID (Xerox copy) - Spouse 2',
    'lc_vc_cert' => 'LC/VC Certification (Original)',
    'witness1_voter_id' => 'Witness 1 Voter ID (Xerox copy)',
    'witness2_voter_id' => 'Witness 2 Voter ID (Xerox copy)',
    'witness3_voter_id' => 'Witness 3 Voter ID (Xerox copy)'
];
$applicationFee = 10.00;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marriage Certificate Application - Government Certificate Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="/js/application_form.js"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen py-8">
        <div class="max-w-4xl mx-auto px-4">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Apply for Marriage Certificate</h1>
            
            <form id="certificateForm" class="bg-white shadow-md rounded-lg p-6 space-y-6" method="POST" action="/api/applications/submit" enctype="multipart/form-data">
                <input type="hidden" name="certificateType" value="<?php echo htmlspecialchars($certificateType); ?>">
                <input type="hidden" name="applicationFee" value="<?php echo htmlspecialchars($applicationFee); ?>">
                
                <!-- Spouse 1 Information Section -->
                <div class="space-y-4">
                    <h2 class="text-xl font-semibold text-gray-800">Spouse 1 Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="spouse1Name" class="block text-sm font-medium text-gray-700">Full Name</label>
                            <input type="text" id="spouse1Name" name="spouse1Name" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label for="spouse1Age" class="block text-sm font-medium text-gray-700">Age</label>
                            <input type="number" id="spouse1Age" name="spouse1Age" required min="18"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label for="spouse1Religion" class="block text-sm font-medium text-gray-700">Religion</label>
                            <input type="text" id="spouse1Religion" name="spouse1Religion" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label for="spouse1Address" class="block text-sm font-medium text-gray-700">Address</label>
                            <input type="text" id="spouse1Address" name="spouse1Address" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>
                </div>

                <!-- Spouse 2 Information Section -->
                <div class="space-y-4">
                    <h2 class="text-xl font-semibold text-gray-800">Spouse 2 Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="spouse2Name" class="block text-sm font-medium text-gray-700">Full Name</label>
                            <input type="text" id="spouse2Name" name="spouse2Name" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label for="spouse2Age" class="block text-sm font-medium text-gray-700">Age</label>
                            <input type="number" id="spouse2Age" name="spouse2Age" required min="18"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label for="spouse2Religion" class="block text-sm font-medium text-gray-700">Religion</label>
                            <input type="text" id="spouse2Religion" name="spouse2Religion" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label for="spouse2Address" class="block text-sm font-medium text-gray-700">Address</label>
                            <input type="text" id="spouse2Address" name="spouse2Address" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>
                </div>

                <!-- Marriage Details Section -->
                <div class="space-y-4">
                    <h2 class="text-xl font-semibold text-gray-800">Marriage Details</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="marriageDate" class="block text-sm font-medium text-gray-700">Date of Marriage</label>
                            <input type="date" id="marriageDate" name="marriageDate" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label for="marriagePlace" class="block text-sm font-medium text-gray-700">Place of Marriage</label>
                            <input type="text" id="marriagePlace" name="marriagePlace" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label for="marriageType" class="block text-sm font-medium text-gray-700">Type of Marriage</label>
                            <select id="marriageType" name="marriageType" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Select type</option>
                                <option value="religious">Religious</option>
                                <option value="civil">Civil</option>
                                <option value="customary">Customary</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Witness Information Section -->
                <div class="space-y-4">
                    <h2 class="text-xl font-semibold text-gray-800">Witness Information</h2>
                    <div class="space-y-4">
                        <!-- Witness 1 -->
                        <div class="border rounded-md p-4">
                            <h3 class="text-lg font-medium text-gray-800 mb-4">Witness 1</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="witness1Name" class="block text-sm font-medium text-gray-700">Full Name</label>
                                    <input type="text" id="witness1Name" name="witness1Name" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label for="witness1Address" class="block text-sm font-medium text-gray-700">Address</label>
                                    <input type="text" id="witness1Address" name="witness1Address" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>
                        <!-- Witness 2 -->
                        <div class="border rounded-md p-4">
                            <h3 class="text-lg font-medium text-gray-800 mb-4">Witness 2</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="witness2Name" class="block text-sm font-medium text-gray-700">Full Name</label>
                                    <input type="text" id="witness2Name" name="witness2Name" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label for="witness2Address" class="block text-sm font-medium text-gray-700">Address</label>
                                    <input type="text" id="witness2Address" name="witness2Address" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>
                        <!-- Witness 3 -->
                        <div class="border rounded-md p-4">
                            <h3 class="text-lg font-medium text-gray-800 mb-4">Witness 3</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="witness3Name" class="block text-sm font-medium text-gray-700">Full Name</label>
                                    <input type="text" id="witness3Name" name="witness3Name" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label for="witness3Address" class="block text-sm font-medium text-gray-700">Address</label>
                                    <input type="text" id="witness3Address" name="witness3Address" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
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
                            <label for="declaration" class="font-medium text-gray-700">We hereby declare that all the information provided above is true and correct to the best of our knowledge.</label>
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