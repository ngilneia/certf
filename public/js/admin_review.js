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
            statusBadge.textContent = data.status.charAt(0).toUpperCase() + data.status.slice(1);
            statusBadge.className = `px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${
                data.status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                data.status === 'approved' ? 'bg-green-100 text-green-800' :
                'bg-red-100 text-red-800'
            }`;
            
            // Display application details
            const formData = JSON.parse(data.form_data || '{}');
            const detailsHtml = Object.entries(formData)
                .map(([key, value]) => `
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">${key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">${value}</dd>
                    </div>
                `).join('');
            document.getElementById('application-details').innerHTML = detailsHtml;
            
            // Display uploaded documents
            const documentsHtml = Object.entries(data.documents || {})
                .map(([key, path]) => `
                    <li class="px-4 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                <span class="ml-2 text-sm text-gray-600">${key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())}</span>
                            </div>
                            <a href="/uploads/${path}" target="_blank" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">View Document</a>
                        </div>
                    </li>
                `).join('');
            document.getElementById('documents-list').innerHTML = documentsHtml || '<li class="px-4 py-4 text-sm text-gray-500">No documents uploaded</li>';
            
            // Handle approval/rejection
            const approveBtn = document.getElementById('approve-btn');
            const rejectBtn = document.getElementById('reject-btn');
            
            if (data.status === 'pending') {
                approveBtn.addEventListener('click', () => handleApproval(applicationId));
                rejectBtn.addEventListener('click', () => handleRejection(applicationId));
            } else {
                approveBtn.disabled = true;
                rejectBtn.disabled = true;
                approveBtn.classList.add('opacity-50', 'cursor-not-allowed');
                rejectBtn.classList.add('opacity-50', 'cursor-not-allowed');
            }
        })
        .catch(error => {
            console.error('Error fetching application details:', error);
            alert('An error occurred while fetching application details. Please try again.');
        });
});

function handleApproval(applicationId) {
    const comments = document.getElementById('review-comments').value;
    
    fetch(`/api/admin/applications/${applicationId}/approve`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        },
        body: JSON.stringify({ comments })
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            throw new Error(data.error);
        }
        alert('Application approved successfully!');
        window.location.reload();
    })
    .catch(error => {
        console.error('Error approving application:', error);
        alert(error.message || 'An error occurred while approving the application. Please try again.');
    });
}

function handleRejection(applicationId) {
    const comments = document.getElementById('review-comments').value;
    if (!comments.trim()) {
        alert('Please provide comments explaining the reason for rejection');
        return;
    }
    
    fetch(`/api/admin/applications/${applicationId}/reject`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        },
        body: JSON.stringify({ comments })
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            throw new Error(data.error);
        }
        alert('Application rejected successfully!');
        window.location.reload();
    })
    .catch(error => {
        console.error('Error rejecting application:', error);
        alert(error.message || 'An error occurred while rejecting the application. Please try again.');
    });
}