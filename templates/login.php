<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo $_SESSION['csrf_token']; ?>">
    <title>Login - Government Certificate Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="/css/auth.css" rel="stylesheet">
    <script src="/js/auth.js"></script>
</head>

<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full space-y-8 p-8 bg-white rounded-lg shadow-md">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">Aizawl District</h2>
                <p class="mt-2 text-center text-sm text-gray-600">Certificate Management System</p>
            </div>
            <form class="mt-8 space-y-6" id="loginForm" novalidate>
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="hidden" name="remember" value="true">
                <div class="rounded-md shadow-sm -space-y-px">
                    <div>
                        <label for="email" class="sr-only">Email address</label>
                        <input id="email" name="email" type="email" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Email address">
                    </div>
                    <div>
                        <label for="password" class="sr-only">Password</label>
                        <input id="password" name="password" type="password" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Password">
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember_me" name="remember_me" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="remember_me" class="ml-2 block text-sm text-gray-900">Remember me</label>
                    </div>

                    <div class="text-sm">
                        <a href="/forgot-password" class="font-medium text-indigo-600 hover:text-indigo-500">Forgot your password?</a>
                    </div>
                </div>

                <div>
                    <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Sign in
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            const email = formData.get('email');
            const password = formData.get('password');

            // Clear previous error messages
            const existingError = document.querySelector('[role="alert"]');
            if (existingError) {
                existingError.remove();
            }

            // Client-side validation
            let errors = [];
            if (!email) {
                errors.push('Email is required');
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                errors.push('Invalid email format');
            }
            if (!password) {
                errors.push('Password is required');
            }

            if (errors.length > 0) {
                showError(errors.join('<br>'));
                return;
            }

            try {
                const submitButton = document.querySelector('button[type="submit"]');
                const originalText = submitButton.innerHTML;
                submitButton.disabled = true;
                submitButton.innerHTML = `
                    <svg class="animate-spin h-5 w-5 mr-3" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Signing in...
                `;

                const response = await fetch('/api/auth/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        email: email,
                        password: password,
                        remember_me: formData.get('remember_me') === 'on'
                    })
                });

                const data = await response.json();

                if (!response.ok) {
                    if (response.status === 429) {
                        showError(data.error.lockout || 'Too many login attempts. Please try again later.');
                    } else if (data.error) {
                        showError(typeof data.error === 'string' ? data.error : Object.values(data.error).join('<br>'));
                    } else {
                        showError('Login failed. Please check your credentials and try again.');
                    }
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalText;
                    return;
                }

                // Successful login
                window.location.href = '/dashboard';

            } catch (error) {
                showError('An error occurred. Please try again later.');
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            }

            function showError(message) {
                const errorDiv = document.createElement('div');
                errorDiv.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 animate-shake';
                errorDiv.setAttribute('role', 'alert');
                errorDiv.innerHTML = `
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <span class="block sm:inline">${message}</span>
                    </div>
                `;
                const form = document.getElementById('loginForm');
                const existingError = form.querySelector('[role="alert"]');
                if (existingError) {
                    existingError.remove();
                }
                form.insertBefore(errorDiv, form.firstChild);
                
                // Add shake animation to input fields
                const inputs = document.querySelectorAll('input[type="email"], input[type="password"]');
                inputs.forEach(input => {
                    input.classList.add('border-red-500', 'animate-shake');
                    setTimeout(() => input.classList.remove('animate-shake'), 500);
                });
            }

            try {
                // Show loading state
                const submitButton = document.querySelector('button[type="submit"]');
                const originalButtonText = submitButton.innerHTML;
                submitButton.disabled = true;
                submitButton.innerHTML = '<span class="animate-spin inline-block mr-2">⌛</span> Signing in...';

                const response = await fetch('/api/auth/login', {
                    credentials: 'same-origin',
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': document.querySelector('input[name="csrf_token"]').value
                    },
                    body: JSON.stringify({
                        email,
                        password
                    }),
                });

                const data = await response.json();
                if (response.ok) {
                    // Initialize authentication setup
                    Auth.init();
                    
                    // Redirect based on the response
                    window.location.href = data.redirect || '/dashboard';
                } else {
                    const errors = data.error;
                    let errorMessage = '';

                    if (typeof errors === 'object') {
                        // Extract specific error messages
                        if (errors.password) {
                            errorMessage = errors.password;
                        } else if (errors.email) {
                            errorMessage = errors.email;
                        } else if (errors.lockout) {
                            errorMessage = errors.lockout;
                        } else if (errors.account) {
                            errorMessage = errors.account;
                        } else if (errors.credentials) {
                            errorMessage = errors.credentials;
                        } else {
                            errorMessage = Object.values(errors).join('<br>');
                        }
                    } else {
                        errorMessage = errors || 'Login failed';
                    }

                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 animate-shake';
                    errorDiv.setAttribute('role', 'alert');
                    errorDiv.innerHTML = `
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <span class="block sm:inline">${errorMessage}</span>
                        </div>
                    `;
                    document.getElementById('loginForm').insertBefore(errorDiv, document.getElementById('loginForm').firstChild);
                    
                    // Shake the input fields
                    const inputs = document.querySelectorAll('input[type="email"], input[type="password"]');
                    inputs.forEach(input => {
                        input.classList.add('border-red-500', 'animate-shake');
                        setTimeout(() => input.classList.remove('animate-shake'), 500);
                    });
                }
            } catch (error) {
                // Reset button state
                submitButton.disabled = false;
                submitButton.innerHTML = originalButtonText;
                console.error('Login error:', error);
                const errorDiv = document.createElement('div');
                errorDiv.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 animate-shake';
                errorDiv.setAttribute('role', 'alert');
                errorDiv.innerHTML = `
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <span class="block sm:inline">An error occurred during login. Please try again.</span>
                    </div>
                `;
                document.getElementById('loginForm').insertBefore(errorDiv, document.getElementById('loginForm').firstChild);
            }
        });
    </script>
</body>

</html>