<x-guest-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <!-- Logo -->
        <div class="flex items-center justify-center mb-8 logo-container">
            <div class="flex items-center space-x-3">
                <div class="flex items-center justify-center bg-gradient-to-br from-blue-600 to-blue-500 text-white rounded-xl w-12 h-12 shadow-md">
                    <i class="fas fa-car-side text-xl"></i>
                </div>
                <div class="text-2xl font-bold text-gray-800 tracking-tight">NikelCo Fleet</div>
            </div>
        </div>

        <!-- Card Container -->
        <div class="w-full sm:max-w-md px-6 py-8 bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Session Status -->
            <x-auth-session-status class="mb-6 p-4 bg-blue-50 text-blue-700 rounded-lg border border-blue-100" :status="session('status')" />

            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Create Account</h1>
                <p class="text-gray-500 mt-2">Get started with your fleet management</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <!-- Name -->
                <div>
                    <x-input-label for="name" :value="__('Name')" class="block text-sm font-medium text-gray-700 mb-1" />
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <i class="fas fa-user"></i>
                        </div>
                        <x-text-input id="name" 
                                    class="input-field block w-full pl-10 pr-3 py-2 rounded-lg leading-5 focus:outline-none" 
                                    type="text" 
                                    name="name" 
                                    :value="old('name')" 
                                    required 
                                    autofocus 
                                    autocomplete="name"
                                    placeholder="Your full name" />
                    </div>
                    <x-input-error :messages="$errors->get('name')" class="mt-2 text-sm text-red-600" />
                </div>

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email')" class="block text-sm font-medium text-gray-700 mb-1" />
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <x-text-input id="email" 
                                    class="input-field block w-full pl-10 pr-3 py-2 rounded-lg leading-5 focus:outline-none" 
                                    type="email" 
                                    name="email" 
                                    :value="old('email')" 
                                    required 
                                    autocomplete="username"
                                    placeholder="your@email.com" />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600" />
                </div>

                <!-- Password -->
                <div>
                    <x-input-label for="password" :value="__('Password')" class="block text-sm font-medium text-gray-700 mb-1" />
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <i class="fas fa-lock"></i>
                        </div>
                        <x-text-input id="password" 
                                    class="input-field block w-full pl-10 pr-3 py-2 rounded-lg leading-5 focus:outline-none"
                                    type="password"
                                    name="password"
                                    required 
                                    autocomplete="new-password"
                                    placeholder="••••••••" />
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600" />
                </div>

                <!-- Confirm Password -->
                <div>
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="block text-sm font-medium text-gray-700 mb-1" />
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <i class="fas fa-lock"></i>
                        </div>
                        <x-text-input id="password_confirmation" 
                                    class="input-field block w-full pl-10 pr-3 py-2 rounded-lg leading-5 focus:outline-none"
                                    type="password"
                                    name="password_confirmation" 
                                    required 
                                    autocomplete="new-password"
                                    placeholder="••••••••" />
                    </div>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-red-600" />
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <x-primary-button class="w-full flex justify-center items-center py-2.5 px-4 rounded-lg shadow-md btn-primary">
                        <span class="font-medium">{{ __('Register') }}</span>
                        <i class="fas fa-user-plus ml-2"></i>
                    </x-primary-button>
                </div>
            </form>

            <!-- Social Login -->
            <div class="mt-8">
                <div class="divider text-sm text-gray-400 mb-6">
                    Or sign up with
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <a href="#" class="w-full inline-flex justify-center items-center py-2 px-4 border border-gray-200 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition social-btn">
                            <i class="fab fa-google text-red-500 mr-2"></i>
                            <span>Google</span>
                        </a>
                    </div>

                    <div>
                        <a href="#" class="w-full inline-flex justify-center items-center py-2 px-4 border border-gray-200 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition social-btn">
                            <i class="fab fa-microsoft text-blue-500 mr-2"></i>
                            <span>Microsoft</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Login Link -->
            <div class="mt-8 text-center text-sm text-gray-500">
                <p>
                    Already have an account?
                    <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500 transition">
                        Sign in here
                    </a>
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>