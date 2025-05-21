<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'NikelCo Fleet') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <!-- Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        
        <!-- Animation Library -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            :root {
                --primary-color: #2563eb;
                --primary-hover: #1d4ed8;
                --secondary-color: #f8fafc;
                --accent-color: #f59e0b;
                --text-color: #1e293b;
                --text-light: #64748b;
            }
            
            body {
                background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                min-height: 100vh;
            }
            
            .auth-card {
                background: rgba(255, 255, 255, 0.95);
                border-radius: 16px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
                backdrop-filter: blur(8px);
                -webkit-backdrop-filter: blur(8px);
                border: 1px solid rgba(255, 255, 255, 0.3);
                transition: all 0.3s ease;
                width: 100%;
                max-width: 32rem; /* Increased from lg to xl */
            }
            
            .auth-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
            }
            
            .btn-primary {
                background: var(--primary-color);
                transition: all 0.3s ease;
                position: relative;
                overflow: hidden;
            }
            
            .btn-primary:hover {
                background: var(--primary-hover);
                transform: translateY(-2px);
            }
            
            .btn-primary:active {
                transform: translateY(0);
            }
            
            .btn-primary::after {
                content: '';
                position: absolute;
                top: 50%;
                left: 50%;
                width: 5px;
                height: 5px;
                background: rgba(255, 255, 255, 0.5);
                opacity: 0;
                border-radius: 100%;
                transform: scale(1, 1) translate(-50%);
                transform-origin: 50% 50%;
            }
            
            .btn-primary:focus:not(:active)::after {
                animation: ripple 1s ease-out;
            }
            
            @keyframes ripple {
                0% {
                    transform: scale(0, 0);
                    opacity: 0.5;
                }
                100% {
                    transform: scale(20, 20);
                    opacity: 0;
                }
            }
            
            .input-field {
                transition: all 0.3s ease;
                border: 1px solid #e2e8f0;
            }
            
            .input-field:focus {
                border-color: var(--primary-color);
                box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
            }
            
            .social-btn {
                transition: all 0.3s ease;
            }
            
            .social-btn:hover {
                transform: translateY(-2px);
            }
            
            .divider {
                display: flex;
                align-items: center;
                text-align: center;
                color: var(--text-light);
            }
            
            .divider::before, .divider::after {
                content: '';
                flex: 1;
                border-bottom: 1px solid #e2e8f0;
            }
            
            .divider::before {
                margin-right: 1rem;
            }
            
            .divider::after {
                margin-left: 1rem;
            }
            
            .logo-container {
                transition: all 0.3s ease;
            }
            
            .logo-container:hover {
                transform: scale(1.05);
            }
            
            .footer-link {
                transition: all 0.2s ease;
            }
            
            .footer-link:hover {
                color: var(--primary-color);
                transform: translateY(-1px);
            }
            
            @media (max-width: 640px) {
                .auth-card {
                    width: 95%;
                    padding: 1.5rem;
                }
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
       <div class="min-h-screen flex flex-col sm:justify-center items-center pt-16 sm:pt-12 pb-16 px-6"> <!-- Added pb-16 for bottom padding -->
            <!-- Card Container -->
            <div class="w-full sm:max-w-xl px-8 py-10 auth-card animate__animated animate__fadeIn mb-8"> <!-- Changed to max-w-xl and added mb-8 -->
                {{ $slot }}
            </div>
            
            <!-- Footer Links - Moved outside card and increased margin -->
            <div class="w-full sm:max-w-xl text-center text-gray-600 text-sm mt-8"> <!-- Matched card width -->
                <p class="text-gray-500">&copy; {{ date('Y') }} NikelCo Fleet Management. All rights reserved.</p>
                <div class="mt-3 flex justify-center space-x-4">
                    <a href="#" class="footer-link text-gray-500 hover:text-primary-600">Privacy Policy</a>
                    <a href="#" class="footer-link text-gray-500 hover:text-primary-600">Terms of Service</a>
                    <a href="#" class="footer-link text-gray-500 hover:text-primary-600">Contact Support</a>
                </div>
            </div>
        </div>
    </body>
</html>