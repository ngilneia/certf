/**
 * Authentication utilities for the Certificate Management System
 */

const Auth = {
    /**
     * Initialize authentication setup
     */
    init: function() {
        this.setupAuthHeaders();
        this.setupFormValidation();
        this.setupAuthInterceptor();
    },

    /**
     * Set up authentication headers for all requests
     */
    setupAuthHeaders: function() {
        window.fetch = new Proxy(window.fetch, {
            apply: function(fetch, that, args) {
                const [url, options = {}] = args;
                if (!options.credentials) {
                    options.credentials = 'same-origin';
                }
                if (!options.headers) {
                    options.headers = {};
                }
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                if (csrfToken) {
                    options.headers['X-CSRF-TOKEN'] = csrfToken;
                }
                return fetch.apply(that, [url, options]);
            }
        });
    },

    /**
     * Set up form validation and submission handling
     */
    setupFormValidation: function() {
        const loginForm = document.getElementById('loginForm');
        if (!loginForm) return;

        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const submitButton = document.getElementById('submitButton');

        // Real-time validation
        emailInput?.addEventListener('input', () => this.validateEmail(emailInput));
        passwordInput?.addEventListener('input', () => this.validatePassword(passwordInput));

        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            await this.handleLoginSubmit(e);
        });
    },

    /**
     * Set up interceptor for authentication responses
     */
    setupAuthInterceptor: function() {
        const originalFetch = window.fetch;
        window.fetch = function() {
            return originalFetch.apply(this, arguments)
                .then(response => Auth.handleAuthResponse(response));
        };
    },

    /**
     * Validate email input
     */
    validateEmail: function(input) {
        const errorDiv = document.querySelector('.email-error');
        const email = input.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!email) {
            this.showInputError(input, errorDiv, 'Email is required');
            return false;
        } else if (!emailRegex.test(email)) {
            this.showInputError(input, errorDiv, 'Please enter a valid email address');
            return false;
        }

        this.hideInputError(input, errorDiv);
        return true;
    },

    /**
     * Validate password input
     */
    validatePassword: function(input) {
        const errorDiv = document.querySelector('.password-error');
        const password = input.value;

        if (!password) {
            this.showInputError(input, errorDiv, 'Password is required');
            return false;
        } else if (password.length < 6) {
            this.showInputError(input, errorDiv, 'Password must be at least 6 characters');
            return false;
        }

        this.hideInputError(input, errorDiv);
        return true;
    },

    /**
     * Show input error message
     */
    showInputError: function(input, errorDiv, message) {
        input.classList.add('border-red-500');
        errorDiv.textContent = message;
        errorDiv.classList.remove('hidden');
    },

    /**
     * Hide input error message
     */
    hideInputError: function(input, errorDiv) {
        input.classList.remove('border-red-500');
        errorDiv.classList.add('hidden');
    },

    /**
     * Handle login form submission
     */
    handleLoginSubmit: async function(event) {
        const form = event.target;
        const emailInput = form.querySelector('#email');
        const passwordInput = form.querySelector('#password');
        const submitButton = form.querySelector('#submitButton');
        const urlParams = new URLSearchParams(window.location.search);
        const returnUrl = urlParams.get('return_url');

        if (!this.validateEmail(emailInput) || !this.validatePassword(passwordInput)) {
            return;
        }

        try {
            submitButton.disabled = true;
            submitButton.innerHTML = `
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Signing in...
            `;

            const response = await fetch('/api/auth/login.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    email: emailInput.value.trim(),
                    password: passwordInput.value,
                    remember_me: form.querySelector('#remember_me').checked
                })
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Login failed');
            }

            this.showNotification('Login successful', 'success');
            setTimeout(() => {
                const redirectUrl = returnUrl ? decodeURIComponent(returnUrl) : (data.redirect || '/dashboard');
                window.location.href = redirectUrl;
            }, 1000);

        } catch (error) {
            this.showNotification(error.message || 'An unexpected error occurred', 'error');
            submitButton.disabled = false;
            submitButton.innerHTML = `
                <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                    <svg class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                    </svg>
                </span>
                Sign in
            `;
        }
    },

    /**
     * Handle authentication response
     */
    handleAuthResponse: function(response) {
        if (response.status === 401) {
            this.removeToken();
            const currentPath = window.location.pathname;
            const returnUrl = encodeURIComponent(currentPath);
            window.location.href = `/login?return_url=${returnUrl}`;
            return Promise.reject('Session expired. Please log in again.');
        } else if (response.status === 403) {
            return Promise.reject('You do not have permission to access this resource');
        }
        return response;
    },

    /**
     * Show notification message
     */
    showNotification: function(message, type = 'success') {
        Toastify({
            text: message,
            duration: 3000,
            gravity: "top",
            position: "right",
            style: {
                background: type === 'error' ? "#EF4444" : "#10B981",
                borderRadius: "8px",
                boxShadow: "0 2px 4px rgba(0,0,0,0.1)"
            },
            stopOnFocus: true
        }).showToast();
    },

    /**
     * Check if user is authenticated
     */
    isAuthenticated: function() {
        const hasSession = document.cookie.includes('PHPSESSID=');
        if (!hasSession) {
            return false;
        }
        // Additional check for session validity
        return fetch('/api/auth/check-session.php', {
            method: 'GET',
            credentials: 'same-origin'
        })
        .then(response => response.ok)
        .catch(() => false);
    },

    /**
     * Remove authentication token
     */
    removeToken: function() {
        document.cookie = 'PHPSESSID=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
    },

    /**
     * Toggle password visibility
     */
    togglePasswordVisibility: function() {
        const passwordInput = document.getElementById('password');
        const button = document.querySelector('button[onclick="togglePasswordVisibility()"]');
        const type = passwordInput.type === 'password' ? 'text' : 'password';
        const icon = type === 'password' ? 
            `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />` :
            `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />`;

        passwordInput.type = type;
        button.querySelector('svg').innerHTML = icon;
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