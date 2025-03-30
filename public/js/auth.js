/**
 * Authentication utilities for the Certificate Management System
 */

const Auth = {
    /**
     * Initialize authentication setup
     */
    init: function() {
        this.setupAuthHeaders();
        
        // Add event listener for all API requests
        const originalFetch = window.fetch;
        window.fetch = function() {
            return originalFetch.apply(this, arguments)
                .then(response => Auth.handleAuthResponse(response));
        };
    },
    
    /**
     * Check if user is authenticated
     * @returns {boolean} True if authenticated, false otherwise
     */
    isAuthenticated: function() {
        return document.cookie.includes('PHPSESSID=');
    },
    
    /**
     * Redirect to login page if not authenticated
     */
    requireAuth: function() {
        if (!this.isAuthenticated()) {
            window.location.href = '/login';
        }
    },
    
    /**
     * Set up fetch request defaults
     */
    setupAuthHeaders: function() {
        window.fetch = new Proxy(window.fetch, {
            apply: function(fetch, that, args) {
                const [url, options = {}] = args;
                if (!options.credentials) {
                    options.credentials = 'same-origin';
                }
                return fetch.apply(that, [url, options]);
            }
        });
    },
    
    /**
     * Remove authentication token and clear session
     */
    removeToken: function() {
        this.deleteCookie('PHPSESSID');
    },
    
    /**
     * Handle API response for authentication errors
     * @param {Response} response - The fetch Response object
     * @returns {Promise} The response or redirects on auth error
     */
    handleAuthResponse: function(response) {
        if (response.status === 401) {
            this.removeToken();
            window.location.href = '/login';
            return Promise.reject('Authentication failed');
        }
        return response.ok ? response : Promise.reject('Request failed');
    },
    
    /**
     * Get a cookie value by name
     * @param {string} name - The cookie name
     * @returns {string|null} The cookie value or null if not found
     */
    getCookie: function(name) {
        const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
        return match ? match[2] : null;
    },
    
    /**
     * Set a cookie
     * @param {string} name - The cookie name
     * @param {string} value - The cookie value
     * @param {number} days - Days until expiry
     */
    setCookie: function(name, value, days) {
        let expires = '';
        if (days) {
            const date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = '; expires=' + date.toUTCString();
        }
        document.cookie = name + '=' + value + expires + '; path=/; secure; samesite=strict';
    },
    
    /**
     * Delete a cookie
     * @param {string} name - The cookie name
     */
    deleteCookie: function(name) {
        this.setCookie(name, '', -1);
    },
    
    /**
     * Handle session expiration
     */
    handleSessionExpired: function() {
        window.location.href = '/login';
    }
};

// Initialize authentication when the script loads
document.addEventListener('DOMContentLoaded', function() {
    Auth.init();
    
    // Check authentication for protected pages
    const currentPath = window.location.pathname;
    const publicPaths = ['/', '/login', '/register', '/forgot-password'];
    
    if (!publicPaths.includes(currentPath)) {
        Auth.requireAuth();
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    
    loginForm.addEventListener('submit', async function(event) {
        event.preventDefault();
        
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        // Basic validation
        if (!email || !password) {
            showNotification('Please fill in all fields', 'error');
            return;
        }

        try {
            const response = await fetch('/api/auth/login.php', {  // Changed from /api/auth/login
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    email: email,
                    password: password
                })
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Login failed');
            }

            if (data.success) {
                showNotification('Login successful', 'success');
                // Redirect after successful login
                setTimeout(() => {
                    window.location.href = '/dashboard.html';
                }, 1000);
            } else {
                showNotification(data.message || 'Invalid credentials', 'error');
            }
        } catch (error) {
            showNotification(error.message || 'An error occurred during login', 'error');
            console.error('Login error:', error);
        }
    });
});

function showNotification(message, type) {
    Toastify({
        text: message,
        duration: 3000,
        gravity: "top",
        position: "right",
        backgroundColor: type === 'error' ? "#ff4444" : "#00C851",
        stopOnFocus: true
    }).showToast();
}