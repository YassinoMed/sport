@extends('layouts.app')

@section('content')
    <div class="login-wrapper">
        <!-- Background Design -->
        <div class="login-bg">
            <div class="bg-shape bg-shape-1"></div>
            <div class="bg-shape bg-shape-2"></div>
            <div class="bg-shape bg-shape-3"></div>
        </div>

        <div class="container">
            <div class="row justify-content-center min-vh-100 align-items-center py-5">
                <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">
                    <!-- Logo Section -->
                    <div class="text-center mb-5">
                        <div class="logo-container mb-3">
                            <div class="logo-circle">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/><rect width="8" height="5" x="8" y="11" rx="1"/><path d="M10 11V9a2 2 0 1 1 4 0v2"/></svg>
                            </div>
                        </div>
                        <h1 class="app-title">Welcome Back</h1>
                        <p class="app-subtitle">Sign in to continue to your dashboard</p>
                    </div>

                    <!-- Login Card -->
                    <div class="login-card">
                        <div class="card-body p-4 p-md-5">
                            <form method="POST" action="{{ route('login') }}" class="login-form">
                                @csrf
                                <!-- Honeypot Field (Hidden) -->
                                <div style="display:none;">
                                    <input type="text" name="website_url" value="">
                                </div>

                                <!-- Email Field -->
                                <div class="form-field mb-4">
                                    <label for="email" class="form-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                                        {{ __('Email Address') }}
                                    </label>
                                    <input id="email" type="email"
                                        class="form-control modern-input @error('login') is-invalid @enderror @error('email') is-invalid @enderror"
                                        name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                                        placeholder="Enter your email">
                                    @error('login')
                                        <div class="invalid-feedback">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    @error('email')
                                        <div class="invalid-feedback">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Password Field -->
                                <div class="form-field mb-4">
                                    <label for="password" class="form-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                        {{ __('Password') }}
                                    </label>
                                    <div class="password-field">
                                        <input id="password" type="password"
                                            class="form-control modern-input @error('password') is-invalid @enderror"
                                            name="password" required autocomplete="current-password"
                                            placeholder="Enter your password">
                                        <button type="button" class="password-toggle" onclick="togglePassword()">
                                            <span id="eye-icon-container">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                            </span>
                                        </button>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback d-block">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Remember & Forgot -->
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div class="form-check custom-checkbox">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                            {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="remember">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/><path d="m16 11 2 2 4-4"/></svg>
                                            {{ __('Remember Me') }}
                                        </label>
                                    </div>
                                    @if (Route::has('password.request'))
                                        <a class="forgot-link" href="{{ route('password.request') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" x2="12.01" y1="17" y2="17"/></svg>
                                            {{ __('Forgot Password?') }}
                                        </a>
                                    @endif
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="btn btn-primary w-100 login-btn mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" x2="3" y1="12" y2="12"/></svg>
                                    <span class="btn-text">{{ __('Login') }}</span>
                                    <div class="spinner-border spinner-border-sm text-light ms-2 d-none" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </button>

                                
                            </form>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="login-footer text-center mt-4">
                        <p class="text-white-50 small mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1"><circle cx="12" cy="12" r="10"/><path d="M14.83 14.83a4 4 0 1 1 0-5.66"/></svg>2025 MouradBenYahya. All rights reserved.
                        </p>
                        <div class="footer-links">
                            <a href="#">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/><path d="m9 12 2 2 4-4"/></svg>
                                Privacy Policy
                            </a>
                            <span class="mx-2 text-white-50">•</span>
                            <a href="#">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><line x1="10" x2="8" y1="9" y2="9"/></svg>
                                Terms of Service
                            </a>
                            <span class="mx-2 text-white-50">•</span>
                            <a href="#">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                                Contact
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --primary-color: #667eea;
            --primary-dark: #5a67d8;
            --primary-light: #7c8ff0;
            --text-dark: #2d3748;
            --text-muted: #718096;
            --border-color: #e2e8f0;
            --card-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            --hover-shadow: 0 25px 70px rgba(0, 0, 0, 0.2);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        .login-wrapper {
            min-height: 100vh;
            position: relative;
            background: var(--primary-gradient);
            overflow: hidden;
        }

        /* Background Animation */
        .login-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }

        .bg-shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            opacity: 0.3;
            animation: float 15s infinite ease-in-out;
        }

        .bg-shape-1 {
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.2);
            top: -100px;
            right: -100px;
        }

        .bg-shape-2 {
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.15);
            bottom: -50px;
            left: -50px;
            animation-delay: 5s;
        }

        .bg-shape-3 {
            width: 500px;
            height: 500px;
            background: rgba(255, 255, 255, 0.1);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation-delay: 10s;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0) translateX(0);
            }
            25% {
                transform: translateY(-20px) translateX(10px);
            }
            50% {
                transform: translateY(10px) translateX(-10px);
            }
            75% {
                transform: translateY(-10px) translateX(20px);
            }
        }

        .container {
            position: relative;
            z-index: 1;
        }

        /* Logo Section */
        .logo-container {
            display: inline-block;
            animation: bounceIn 0.8s ease-out;
        }

        .logo-circle {
            width: 90px;
            height: 90px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            border: 3px solid rgba(255, 255, 255, 0.3);
        }

        .logo-circle svg {
            color: white;
        }

        .app-title {
            color: white;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 8px;
            animation: fadeInUp 0.8s ease-out 0.2s both;
        }

        .app-subtitle {
            color: rgba(255, 255, 255, 0.8);
            font-size: 16px;
            animation: fadeInUp 0.8s ease-out 0.4s both;
        }

        /* Login Card */
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(255, 255, 255, 0.5);
            overflow: hidden;
            animation: fadeInUp 0.8s ease-out 0.6s both;
            transition: all 0.3s ease;
        }

        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--hover-shadow);
        }

        /* Form Styles */
        .form-label {
            color: var(--text-dark);
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .form-label svg {
            color: var(--primary-color);
        }

        .modern-input {
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 15px;
            transition: all 0.3s ease;
            background: white;
        }

        .modern-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            outline: none;
        }

        .modern-input.is-invalid {
            border-color: #dc3545;
            background-image: none;
        }

        .modern-input.is-invalid:focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 4px rgba(220, 53, 69, 0.1);
        }

        /* Password Field */
        .password-field {
            position: relative;
        }

        .password-field input {
            padding-right: 45px;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            padding: 5px;
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: var(--primary-color);
        }

        /* Custom Checkbox */
        .custom-checkbox .form-check-input {
            width: 20px;
            height: 20px;
            border: 2px solid var(--border-color);
            border-radius: 6px;
            margin-top: 0;
        }

        .custom-checkbox .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .custom-checkbox .form-check-input:focus {
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        .custom-checkbox .form-check-label {
            margin-left: 6px;
            color: var(--text-dark);
            font-weight: 500;
            display: flex;
            align-items: center;
        }

        .custom-checkbox .form-check-label svg {
            color: var(--primary-color);
        }

        /* Forgot Link */
        .forgot-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
        }

        .forgot-link:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        .forgot-link svg {
            /* font-size replaced by width/height in svg */
        }

        /* Login Button */
        .login-btn {
            background: var(--primary-gradient);
            border: none;
            border-radius: 12px;
            padding: 14px 24px;
            font-size: 16px;
            font-weight: 600;
            color: white;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .login-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.6s ease;
        }

        .login-btn:hover::before {
            left: 100%;
        }

        .login-btn svg {
            /* size handled in svg tag */
        }

        /* Divider */
        .divider-container {
            display: flex;
            align-items: center;
            margin: 25px 0;
        }

        .divider-line {
            flex: 1;
            height: 1px;
            background: var(--border-color);
        }

        .divider-text {
            padding: 0 15px;
            color: var(--text-muted);
            font-size: 13px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Social Buttons */
        .social-btn {
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            background: white;
        }

        .social-btn:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* Sign Up Link */
        .signup-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .signup-link:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        /* Footer */
        .login-footer {
            animation: fadeIn 0.8s ease-out 0.8s both;
        }

        .login-footer p svg {
            /* size handled in svg tag */
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-size: 13px;
            transition: color 0.3s ease;
            display: inline-flex;
            align-items: center;
        }

        .footer-links a:hover {
            color: white;
            text-decoration: underline;
        }

        .footer-links a svg {
            /* size handled in svg tag */
        }

        /* Invalid Feedback */
        .invalid-feedback {
            font-size: 13px;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 4px;
            animation: shake 0.3s ease;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        /* Animations */
        @keyframes bounceIn {
            0% {
                transform: scale(0.3);
                opacity: 0;
            }
            50% {
                transform: scale(1.05);
            }
            70% {
                transform: scale(0.9);
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        /* Responsive Design */
        @media (max-width: 576px) {
            .app-title {
                font-size: 28px;
            }

            .app-subtitle {
                font-size: 14px;
            }

            .login-card {
                margin: 0 15px;
                border-radius: 16px;
            }

            .card-body {
                padding: 20px !important;
            }

            .logo-circle {
                width: 70px;
                height: 70px;
            }

            .logo-circle svg {
                width: 32px;
                height: 32px;
            }

            .bg-shape {
                filter: blur(80px);
            }

            .d-flex.justify-content-between {
                flex-direction: column;
                gap: 12px;
            }

            .forgot-link {
                align-self: flex-start;
            }
        }

        /* Loading State */
        .login-form.loading .login-btn {
            pointer-events: none;
            opacity: 0.8;
        }

        .login-form.loading .btn-text {
            opacity: 0.5;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIconContainer = document.getElementById('eye-icon-container');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIconContainer.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.52 13.52 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>';
            } else {
                passwordInput.type = 'password';
                eyeIconContainer.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>';
            }
        }

        // Form submission loading state
        document.querySelector('.login-form').addEventListener('submit', function() {
            this.classList.add('loading');
            const spinner = this.querySelector('.spinner-border');
            spinner.classList.remove('d-none');
        });
    </script>
    @endpush
@endsection