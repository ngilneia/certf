document.addEventListener('DOMContentLoaded', function() {
    // Check if we're in edit mode
    const urlParams = new URLSearchParams(window.location.search);
    const editId = urlParams.get('id');

    // Get the form element
    const form = document.getElementById('certificateForm');

    if (editId) {
        // Fetch application details for editing
        fetch(`/api/certificates/applications/${editId}`, {
            headers: {
                'Content-Type': 'application/json'
            },
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert('Error: ' + data.error);
                return;
            }

            // Populate form fields
            document.getElementById('applicant_name').value = data.applicant_name;
            document.getElementById('applicant_phone').value = data.applicant_phone;
            document.getElementById('applicant_email').value = data.applicant_email;

            // Populate form_data fields
            const formData = JSON.parse(data.form_data || '{}');
            document.getElementById('father_name').value = formData.father_name || '';
            document.getElementById('address_line1').value = formData.address_line1 || '';
            document.getElementById('address_line2').value = formData.address_line2 || '';
            document.getElementById('city').value = formData.city || '';
            document.getElementById('district').value = formData.district || '';
            document.getElementById('state').value = formData.state || '';
            document.getElementById('pincode').value = formData.pincode || '';
            document.getElementById('additional_info').value = formData.additional_info || '';

            // Trigger certificate type change to update fee and document fields
            const certificateTypeSelect = document.getElementById('certificate_type_id');
            certificateTypeSelect.dispatchEvent(new Event('change'));
        })
        .catch(error => {
            console.error('Error fetching application details:', error);
            alert('An error occurred while fetching application details. Please try again.');
        });
    }

    // Handle form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Create FormData object for file uploads
        const formData = new FormData();
        formData.append('certificate_type_id', document.getElementById('certificate_type_id').value);
        formData.append('applicant_name', document.getElementById('applicant_name').value);
        formData.append('applicant_phone', document.getElementById('applicant_phone').value);
        formData.append('applicant_email', document.getElementById('applicant_email').value);
        
        // Add form_data as an object
        const formDataObj = {
            father_name: document.getElementById('father_name').value,
            address_line1: document.getElementById('address_line1').value,
            address_line2: document.getElementById('address_line2').value,
            city: document.getElementById('city').value,
            district: document.getElementById('district').value,
            state: document.getElementById('state').value,
            pincode: document.getElementById('pincode').value,
            additional_info: document.getElementById('additional_info').value
        };
        formData.append('form_data', JSON.stringify(formDataObj));
        
        // Add file uploads with validation
        const fileInputs = document.querySelectorAll('input[type="file"]');
        const maxFileSize = 2 * 1024 * 1024; // 2MB
        const allowedTypes = ['application/pdf', 'image/jpeg', 'image/png'];

        try {
            for (const input of fileInputs) {
                if (input.files.length > 0) {
                    const file = input.files[0];
                    if (file.size > maxFileSize) {
                        alert(`File ${file.name} is too large. Maximum size is 2MB`);
                        return;
                    }
                    if (!allowedTypes.includes(file.type)) {
                        alert(`File ${file.name} has invalid type. Allowed types are PDF, JPG, and PNG`);
                        return;
                    }
                    formData.append(input.name, file);
                }
            }

            // Determine the URL and method based on edit mode
            const url = editId ? `/api/certificates/applications/${editId}` : '/api/certificates/apply';
            const method = editId ? 'PUT' : 'POST';

            // Validate required fields
            const requiredFields = [
                { id: 'certificate_type_id', label: 'Certificate Type' },
                { id: 'applicant_name', label: 'Applicant Name' },
                { id: 'applicant_phone', label: 'Phone Number' },
                { id: 'father_name', label: "Father's Name" },
                { id: 'address_line1', label: 'Address Line 1' },
                { id: 'city', label: 'City/Village' },
                { id: 'district', label: 'District' },
                { id: 'state', label: 'State' },
                { id: 'pincode', label: 'Pincode' }
            ];

            for (const field of requiredFields) {
                const element = document.getElementById(field.id);
                if (!element.value.trim()) {
                    alert(`Please enter ${field.label}`);
                    element.focus();
                    return;
                }
            }

            // Validate phone number format
            const phoneRegex = /^[0-9]{10}$/;
            const phone = document.getElementById('applicant_phone').value;
            if (!phoneRegex.test(phone)) {
                alert('Please enter a valid 10-digit phone number');
                document.getElementById('applicant_phone').focus();
                return;
            }

            // Validate email format if provided
            const email = document.getElementById('applicant_email').value;
            if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                alert('Please enter a valid email address');
                document.getElementById('applicant_email').focus();
                return;
            }

            // Validate pincode format
            const pincodeRegex = /^[0-9]{6}$/;
            const pincode = document.getElementById('pincode').value;
            if (!pincodeRegex.test(pincode)) {
                alert('Please enter a valid 6-digit pincode');
                document.getElementById('pincode').focus();
                return;
            }

            // Send the request
            fetch(url, {
                method: method,
                credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        throw new Error(data.error || 'Application submission failed');
                    });
                }
                return response.json();
            })
            .then(data => {
                alert(editId ? 'Application updated successfully!' : 'Application submitted successfully!');
                window.location.href = '/applications';
            })
            .catch(error => {
                console.error('Error submitting application:', error);
                alert(error.message || 'An error occurred while submitting your application. Please try again.');
            });
        } catch (error) {
            console.error('Error validating files:', error);
            alert('Error validating files: ' + error.message);
        }
    });

    // Handle certificate type change
    const certificateTypeSelect = document.getElementById('certificate_type_id');
    if (certificateTypeSelect) {
        certificateTypeSelect.addEventListener('change', function() {
            const selectedType = this.value;
            if (!selectedType) {
                document.getElementById('certificate_fee').textContent = '₹0';
                document.getElementById('documents_section').classList.add('hidden');
                return;
            }

            // Fetch certificate type details
            fetch(`/api/certificates/types/${selectedType}`, {
                headers: {
                    'Content-Type': 'application/json'
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error('Error fetching certificate type:', data.error);
                    return;
                }

                // Update fee display
                document.getElementById('certificate_fee').textContent = `₹${data.fee}`;

                // Update required documents section
                const documentsSection = document.getElementById('documents_section');
                const documentsContainer = documentsSection.querySelector('.documents-container') || documentsSection;
                documentsContainer.innerHTML = '';

                const requiredDocs = JSON.parse(data.required_documents || '[]');
                if (requiredDocs.length > 0) {
                    requiredDocs.forEach(doc => {
                        const div = document.createElement('div');
                        div.className = 'mb-4';
                        div.innerHTML = `
                            <label class="block text-sm font-medium text-gray-700 mb-1">${doc} <span class="text-red-500">*</span></label>
                            <input type="file" name="document_${doc.toLowerCase().replace(/\s+/g, '_')}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" 
                                   required>
                        `;
                        documentsContainer.appendChild(div);
                    });
                    documentsSection.classList.remove('hidden');
                } else {
                    documentsSection.classList.add('hidden');
                }
            })
            .catch(error => {
                console.error('Error fetching certificate type:', error);
                alert('Failed to load certificate type details. Please try again.');
            });
        });
    }
});