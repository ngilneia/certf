/**
 * Logout functionality for the Certificate Management System
 */

document.addEventListener('DOMContentLoaded', function() {
    // Find all logout links and attach event handlers
    const logoutLinks = document.querySelectorAll('a[href="/logout"]');
    
    logoutLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Call the logout API
            fetch('/api/auth/logout', {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json'
                    // Auth.js will automatically add Authorization and CSRF headers
                }
            })
            .then(response => response.json())
            .then(data => {
                // Clear authentication data
                Auth.removeToken();
                localStorage.removeItem('user_role');
                
                // Redirect to login page
                window.location.href = '/login';
            })
            .catch(error => {
                console.error('Logout error:', error);
                // Even if the API call fails, clear local auth data and redirect
                Auth.removeToken();
                localStorage.removeItem('user_role');
                window.location.href = '/login';
            });
        });
    });
});