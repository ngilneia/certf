<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Certificate - Government Certificate Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">Verify Certificate</h2>
                <p class="mt-2 text-center text-sm text-gray-600">Check the authenticity of your government certificate</p>
            </div>

            <div class="mt-8 bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                <form class="space-y-6" id="verifyForm">
                    <div>
                        <label for="certificate_number" class="block text-sm font-medium text-gray-700">Certificate Number</label>
                        <div class="mt-1">
                            <input type="text" id="certificate_number" name="certificate_number" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Enter certificate number">
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Verify Certificate
                        </button>
                    </div>
                </form>

                <div id="result" class="mt-6 hidden">
                    <div class="rounded-md p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg id="validIcon" class="h-5 w-5 text-green-400 hidden" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <svg id="invalidIcon" class="h-5 w-5 text-red-400 hidden" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 id="resultTitle" class="text-sm font-medium"></h3>
                                <div id="certificateDetails" class="mt-2 text-sm text-gray-500">
                                    <!-- Certificate details will be displayed here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('verifyForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const certificateNumber = document.getElementById('certificate_number').value;
            const resultDiv = document.getElementById('result');
            const validIcon = document.getElementById('validIcon');
            const invalidIcon = document.getElementById('invalidIcon');
            const resultTitle = document.getElementById('resultTitle');
            const certificateDetails = document.getElementById('certificateDetails');

            try {
                const response = await fetch(`/api/certificates/verify/${certificateNumber}`);
                const data = await response.json();

                resultDiv.classList.remove('hidden');
                if (data.valid) {
                    validIcon.classList.remove('hidden');
                    invalidIcon.classList.add('hidden');
                    resultTitle.textContent = 'Valid Certificate';
                    resultTitle.classList.remove('text-red-800');
                    resultTitle.classList.add('text-green-800');

                    certificateDetails.innerHTML = `
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Certificate Type</dt>
                                <dd class="mt-1 text-sm text-gray-900">${data.certificate.certificate_type_name}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Applicant Name</dt>
                                <dd class="mt-1 text-sm text-gray-900">${data.certificate.applicant_name}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Issue Date</dt>
                                <dd class="mt-1 text-sm text-gray-900">${new Date(data.certificate.issued_at).toLocaleDateString()}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Certificate Number</dt>
                                <dd class="mt-1 text-sm text-gray-900">${data.certificate.certificate_number}</dd>
                            </div>
                        </dl>
                    `;
                } else {
                    validIcon.classList.add('hidden');
                    invalidIcon.classList.remove('hidden');
                    resultTitle.textContent = 'Invalid Certificate';
                    resultTitle.classList.remove('text-green-800');
                    resultTitle.classList.add('text-red-800');
                    certificateDetails.innerHTML = '<p>This certificate could not be verified. Please check the certificate number and try again.</p>';
                }
            } catch (error) {
                console.error('Error verifying certificate:', error);
                resultDiv.classList.remove('hidden');
                validIcon.classList.add('hidden');
                invalidIcon.classList.remove('hidden');
                resultTitle.textContent = 'Verification Error';
                resultTitle.classList.remove('text-green-800');
                resultTitle.classList.add('text-red-800');
                certificateDetails.innerHTML = '<p>An error occurred while verifying the certificate. Please try again later.</p>';
            }
        });
    </script>
</body>

</html>