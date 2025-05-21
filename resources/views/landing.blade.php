<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NikelCo - Vehicle Management System</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .hero-animation {
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 50%, #3b82f6 100%);
            background-size: 200% 200%;
            animation: gradientBG 10s ease infinite;
        }
        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .step-icon {
            transition: all 0.3s ease;
        }
        .step-item:hover .step-icon {
            background-color: #2563eb;
            color: white;
        }
    </style>
</head>
<body class="antialiased font-sans">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <!-- Logo -->
                <div class="flex items-center space-x-2">
                    <div class="flex items-center justify-center bg-blue-600 text-white rounded-lg w-10 h-10">
                        <i class="fas fa-car-side text-xl"></i>
                    </div>
                    <div class="text-xl font-bold text-gray-800 hidden md:block">NikelCo Fleet</div>
                </div>
                
                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button type="button" class="text-gray-500 hover:text-gray-600 focus:outline-none" id="mobile-menu-button">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-gray-700 hover:text-blue-600 font-medium transition">Features</a>
                    <a href="#how-it-works" class="text-gray-700 hover:text-blue-600 font-medium transition">How It Works</a>
                    <a href="#contact" class="text-gray-700 hover:text-blue-600 font-medium transition">Contact</a>
                    
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md font-medium hover:bg-blue-700 transition">
                                Dashboard <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 font-medium transition">Login</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md font-medium hover:bg-blue-700 transition">
                                    Register
                                </a>
                            @endif
                        @endif
                    @endif
                </div>
            </div>
            
            <!-- Mobile Menu -->
            <div class="md:hidden hidden pb-4" id="mobile-menu">
                <div class="flex flex-col space-y-3 mt-3">
                    <a href="#features" class="text-gray-700 hover:text-blue-600 font-medium transition">Features</a>
                    <a href="#how-it-works" class="text-gray-700 hover:text-blue-600 font-medium transition">How It Works</a>
                    <a href="#contact" class="text-gray-700 hover:text-blue-600 font-medium transition">Contact</a>
                    
                    @if (Route::has('login'))
                        <div class="pt-2 border-t border-gray-200">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="block bg-blue-600 text-white px-4 py-2 rounded-md font-medium hover:bg-blue-700 transition text-center">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="block text-gray-700 hover:text-blue-600 font-medium transition">Login</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="block bg-blue-600 text-white px-4 py-2 rounded-md font-medium hover:bg-blue-700 transition mt-2 text-center">
                                        Register
                                    </a>
                                @endif
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-animation text-white py-20 md:py-28">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="inline-flex items-center bg-blue-800 bg-opacity-30 rounded-full px-4 py-2 mb-4">
                    <span class="text-sm font-semibold">NikelCo Fleet Management</span>
                </div>
                <h1 class="text-4xl font-extrabold sm:text-5xl lg:text-6xl mb-6">
                    Efficient <span class="text-blue-200">Vehicle</span> Management System
                </h1>
                <p class="max-w-3xl mx-auto text-xl opacity-90">
                    Streamline your company's vehicle operations with our comprehensive fleet management solution. Monitor fuel consumption, maintenance schedules, and booking approvals all in one place.
                </p>
                <div class="mt-10 flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('login') }}" class="bg-white text-blue-600 px-8 py-3 rounded-md font-semibold hover:bg-blue-50 transition shadow-lg transform hover:scale-105">
                        Get Started <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                    <a href="#features" class="bg-transparent border-2 border-white text-white px-8 py-3 rounded-md font-semibold hover:bg-white hover:bg-opacity-10 transition shadow-lg">
                        Learn More
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="bg-white py-12 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div class="p-4">
                    <div class="text-3xl font-bold text-blue-600 mb-2">6+</div>
                    <div class="text-gray-600 font-medium">Mining Sites</div>
                </div>
                <div class="p-4">
                    <div class="text-3xl font-bold text-blue-600 mb-2">100+</div>
                    <div class="text-gray-600 font-medium">Vehicles</div>
                </div>
                <div class="p-4">
                    <div class="text-3xl font-bold text-blue-600 mb-2">24/7</div>
                    <div class="text-gray-600 font-medium">Monitoring</div>
                </div>
                <div class="p-4">
                    <div class="text-3xl font-bold text-blue-600 mb-2">99%</div>
                    <div class="text-gray-600 font-medium">Efficiency</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Comprehensive Fleet Management</h2>
                <p class="max-w-2xl mx-auto text-gray-600">
                    Our system provides complete solutions for all your vehicle management needs across multiple locations.
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white p-6 rounded-xl shadow-md feature-card transition duration-300">
                    <div class="text-blue-600 mb-4">
                        <i class="fas fa-calendar-check text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Online Booking</h3>
                    <p class="text-gray-600 mb-4">
                        Employees can easily book vehicles online with real-time availability checks and automatic approval routing.
                    </p>
                    <ul class="space-y-2 text-gray-600">
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <span>Multi-level approval system</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <span>Real-time availability</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <span>Automatic notifications</span>
                        </li>
                    </ul>
                </div>
                
                <!-- Feature 2 -->
                <div class="bg-white p-6 rounded-xl shadow-md feature-card transition duration-300">
                    <div class="text-blue-600 mb-4">
                        <i class="fas fa-gas-pump text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Fuel Monitoring</h3>
                    <p class="text-gray-600 mb-4">
                        Track fuel consumption across all vehicles and locations with detailed analytics and reporting.
                    </p>
                    <ul class="space-y-2 text-gray-600">
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <span>Fuel efficiency tracking</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <span>Anomaly detection</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <span>Cost analysis</span>
                        </li>
                    </ul>
                </div>
                
                <!-- Feature 3 -->
                <div class="bg-white p-6 rounded-xl shadow-md feature-card transition duration-300">
                    <div class="text-blue-600 mb-4">
                        <i class="fas fa-tools text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Maintenance Scheduling</h3>
                    <p class="text-gray-600 mb-4">
                        Automated maintenance schedules keep your fleet in optimal condition with timely reminders.
                    </p>
                    <ul class="space-y-2 text-gray-600">
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <span>Preventive maintenance</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <span>Service history tracking</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <span>Downtime minimization</span>
                        </li>
                    </ul>
                </div>
                
                <!-- Feature 4 -->
                <div class="bg-white p-6 rounded-xl shadow-md feature-card transition duration-300">
                    <div class="text-blue-600 mb-4">
                        <i class="fas fa-map-marked-alt text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Regional Management</h3>
                    <p class="text-gray-600 mb-4">
                        Manage vehicles across headquarters, branches, and six different mining locations seamlessly.
                    </p>
                    <ul class="space-y-2 text-gray-600">
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <span>Location-based tracking</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <span>Centralized control</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <span>Localized reporting</span>
                        </li>
                    </ul>
                </div>
                
                <!-- Feature 5 -->
                <div class="bg-white p-6 rounded-xl shadow-md feature-card transition duration-300">
                    <div class="text-blue-600 mb-4">
                        <i class="fas fa-car text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Mixed Fleet Support</h3>
                    <p class="text-gray-600 mb-4">
                        Manage both company-owned and rented vehicles with different tracking requirements.
                    </p>
                    <ul class="space-y-2 text-gray-600">
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <span>Owned vs rented tracking</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <span>Cost comparison</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <span>Contract management</span>
                        </li>
                    </ul>
                </div>
                
                <!-- Feature 6 -->
                <div class="bg-white p-6 rounded-xl shadow-md feature-card transition duration-300">
                    <div class="text-blue-600 mb-4">
                        <i class="fas fa-chart-line text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Analytics & Reporting</h3>
                    <p class="text-gray-600 mb-4">
                        Comprehensive dashboards and customizable reports for data-driven decision making.
                    </p>
                    <ul class="space-y-2 text-gray-600">
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <span>Real-time dashboards</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <span>Custom report generation</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <span>Export to multiple formats</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Streamlined Vehicle Request Process</h2>
                <p class="max-w-2xl mx-auto text-gray-600">
                    Our approval workflow ensures proper authorization while maintaining efficiency.
                </p>
            </div>
            
            <div class="relative">
                <!-- Timeline bar -->
                <div class="hidden md:block absolute left-1/2 h-full w-1 bg-blue-200 transform -translate-x-1/2"></div>
                
                <!-- Steps -->
                <div class="space-y-12 md:space-y-0">
                    <!-- Step 1 -->
                    <div class="relative md:flex items-center">
                        <div class="step-item md:w-1/2 md:pr-12 text-right mb-8 md:mb-0">
                            <div class="step-icon inline-flex items-center justify-center bg-blue-100 text-blue-600 rounded-full w-16 h-16 mb-4 ml-auto text-2xl font-bold">
                                1
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Employee Request</h3>
                            <p class="text-gray-600">
                                Employee submits vehicle request with details including purpose, destination, and timeframe.
                            </p>
                        </div>
                        <div class="hidden md:flex items-center justify-center w-16 h-16 mx-auto bg-blue-600 rounded-full text-white text-2xl font-bold z-10">
                            <i class="fas fa-user-edit"></i>
                        </div>
                        <div class="md:w-1/2 md:pl-12"></div>
                    </div>
                    
                    <!-- Step 2 -->
                    <div class="relative md:flex items-center">
                        <div class="md:w-1/2 md:pr-12 text-right"></div>
                        <div class="hidden md:flex items-center justify-center w-16 h-16 mx-auto bg-blue-600 rounded-full text-white text-2xl font-bold z-10">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div class="step-item md:w-1/2 md:pl-12 text-left mt-8 md:mt-0">
                            <div class="step-icon inline-flex items-center justify-center bg-blue-100 text-blue-600 rounded-full w-16 h-16 mb-4 text-2xl font-bold">
                                2
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Supervisor Approval</h3>
                            <p class="text-gray-600">
                                Immediate supervisor reviews and approves/denies the request based on business needs.
                            </p>
                        </div>
                    </div>
                    
                    <!-- Step 3 -->
                    <div class="relative md:flex items-center">
                        <div class="step-item md:w-1/2 md:pr-12 text-right mb-8 md:mb-0">
                            <div class="step-icon inline-flex items-center justify-center bg-blue-100 text-blue-600 rounded-full w-16 h-16 mb-4 ml-auto text-2xl font-bold">
                                3
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Fleet Management</h3>
                            <p class="text-gray-600">
                                Fleet team assigns appropriate vehicle and confirms availability for requested period.
                            </p>
                        </div>
                        <div class="hidden md:flex items-center justify-center w-16 h-16 mx-auto bg-blue-600 rounded-full text-white text-2xl font-bold z-10">
                            <i class="fas fa-car-alt"></i>
                        </div>
                        <div class="md:w-1/2 md:pl-12"></div>
                    </div>
                    
                    <!-- Step 4 -->
                    <div class="relative md:flex items-center">
                        <div class="md:w-1/2 md:pr-12 text-right"></div>
                        <div class="hidden md:flex items-center justify-center w-16 h-16 mx-auto bg-blue-600 rounded-full text-white text-2xl font-bold z-10">
                            <i class="fas fa-check-double"></i>
                        </div>
                        <div class="step-item md:w-1/2 md:pl-12 text-left mt-8 md:mt-0">
                            <div class="step-icon inline-flex items-center justify-center bg-blue-100 text-blue-600 rounded-full w-16 h-16 mb-4 text-2xl font-bold">
                                4
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Trip Completion</h3>
                            <p class="text-gray-600">
                                After trip completion, driver submits fuel and mileage data for record keeping.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Trusted by Our Teams</h2>
                <p class="max-w-2xl mx-auto text-gray-600">
                    What our employees say about the vehicle management system.
                </p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Testimonial 1 -->
                <div class="bg-white p-6 rounded-xl shadow-md">
                    <div class="flex items-center mb-4">
                        <div class="text-yellow-400 mr-2">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <p class="text-gray-600 italic mb-6">
                        "The booking system has simplified our field operations tremendously. Getting vehicle approvals is now a matter of minutes instead of days."
                    </p>
                    <div class="flex items-center">
                        <div class="bg-blue-100 text-blue-600 rounded-full w-12 h-12 flex items-center justify-center mr-4">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">John Smith</h4>
                            <p class="text-gray-600 text-sm">Field Supervisor, Site A</p>
                        </div>
                    </div>
                </div>
                
                <!-- Testimonial 2 -->
                <div class="bg-white p-6 rounded-xl shadow-md">
                    <div class="flex items-center mb-4">
                        <div class="text-yellow-400 mr-2">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>
                    <p class="text-gray-600 italic mb-6">
                        "As a fleet manager, the maintenance tracking features have helped us reduce vehicle downtime by 30% this year alone."
                    </p>
                    <div class="flex items-center">
                        <div class="bg-blue-100 text-blue-600 rounded-full w-12 h-12 flex items-center justify-center mr-4">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">Sarah Johnson</h4>
                            <p class="text-gray-600 text-sm">Fleet Manager</p>
                        </div>
                    </div>
                </div>
                
                <!-- Testimonial 3 -->
                <div class="bg-white p-6 rounded-xl shadow-md">
                    <div class="flex items-center mb-4">
                        <div class="text-yellow-400 mr-2">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <p class="text-gray-600 italic mb-6">
                        "The mobile-friendly interface makes it easy to request vehicles even when I'm out in the field. Great system!"
                    </p>
                    <div class="flex items-center">
                        <div class="bg-blue-100 text-blue-600 rounded-full w-12 h-12 flex items-center justify-center mr-4">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">Michael Brown</h4>
                            <p class="text-gray-600 text-sm">Geologist, Site C</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-blue-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold mb-6">Ready to Streamline Your Fleet Operations?</h2>
            <p class="max-w-3xl mx-auto text-xl opacity-90 mb-8">
                Join hundreds of mining professionals who are already managing their vehicles more efficiently.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('register') }}" class="bg-white text-blue-600 px-8 py-3 rounded-md font-semibold hover:bg-blue-50 transition shadow-lg transform hover:scale-105">
                    Get Started Now
                </a>
                <a href="#contact" class="bg-transparent border-2 border-white text-white px-8 py-3 rounded-md font-semibold hover:bg-white hover:bg-opacity-10 transition shadow-lg">
                    Contact Our Team
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact" class="bg-gray-900 text-white pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
                <!-- Company Info -->
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="flex items-center justify-center bg-blue-600 text-white rounded-lg w-10 h-10">
                            <i class="fas fa-car-side text-xl"></i>
                        </div>
                        <div class="text-xl font-bold">NikelCo Fleet</div>
                    </div>
                    <p class="text-gray-400 mb-4">
                        Comprehensive vehicle management solution for mining operations.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white transition">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Home</a></li>
                        <li><a href="#features" class="text-gray-400 hover:text-white transition">Features</a></li>
                        <li><a href="#how-it-works" class="text-gray-400 hover:text-white transition">How It Works</a></li>
                        <li><a href="{{ route('login') }}" class="text-gray-400 hover:text-white transition">Login</a></li>
                        <li><a href="{{ route('register') }}" class="text-gray-400 hover:text-white transition">Register</a></li>
                    </ul>
                </div>
                
                <!-- Contact -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Contact Us</h3>
                    <ul class="space-y-3 text-gray-400">
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt mt-1 mr-3 text-blue-500"></i>
                            <span>Jl. Tambang Nikel No. 123, Sulawesi Tenggara, Indonesia</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone-alt mr-3 text-blue-500"></i>
                            <span>+62 21 1234 5678</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-envelope mr-3 text-blue-500"></i>
                            <span>fleet@nikelco.com</span>
                        </li>
                    </ul>
                </div>
                
                <!-- Newsletter -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Newsletter</h3>
                    <p class="text-gray-400 mb-4">
                        Subscribe to get updates on new features and improvements.
                    </p>
                    <form class="flex">
                        <input type="email" placeholder="Your email" class="px-4 py-2 rounded-l-md w-full focus:outline-none text-gray-900">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-r-md">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-12 pt-8 text-center text-gray-500 text-sm">
                <p>&copy; 2025 NikelCo Fleet Management System. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });
        
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    targetElement.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
</body>
</html>