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
                <h1 class="text-3xl font-bold text-gray-800">Sign in</h1>
                <p class="text-gray-500 mt-2">access your dashboard</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf
                <!-- CSRF token added for security -->
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />

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
                                    autofocus 
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
                                    autocomplete="current-password"
                                    placeholder="••••••••" />
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600" />
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember_me" 
                               type="checkbox" 
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                               name="remember">
                        <label for="remember_me" class="ml-2 block text-sm text-gray-600">
                            {{ __('Remember me') }}
                        </label>
                    </div>

                    @if (Route::has('password.request'))
                        <a class="text-sm text-blue-600 hover:text-blue-500 transition font-medium" 
                           href="{{ route('password.request') }}">
                            {{ __('Forgot password?') }}
                        </a>
                    @endif
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <x-primary-button class="w-full flex justify-center items-center py-2.5 px-4 rounded-lg shadow-md btn-primary">
                        <span class="font-medium">{{ __('Log in') }}</span>
                        <i class="fas fa-arrow-right-to-bracket ml-2"></i>
                    </x-primary-button>
                </div>
            </form>

            <!-- Social Login -->
            <div class="mt-8">
                <div class="divider text-sm text-gray-400 mb-6">
                    Or continue with
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

            <!-- Registration Link -->
            @if (Route::has('register'))
                <div class="mt-8 text-center text-sm text-gray-500">
                    <p>
                        Don't have an account?
                        <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-500 transition">
                            Register here
                        </a>
                    </p>
                </div>
            @endif
        </div>
    </div>
</x-guest-layout>