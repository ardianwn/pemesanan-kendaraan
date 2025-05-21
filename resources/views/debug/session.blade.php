@php
// Debugging information
$sessionStatus = session()->isStarted() ? 'Started' : 'Not Started';
$hasToken = session()->has('_token') ? 'Yes' : 'No';
$tokenValue = session()->has('_token') ? session()->get('_token') : 'No token found';
$sessionDriver = config('session.driver');
$sessionTable = config('session.table');
@endphp

<x-guest-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <div class="w-full sm:max-w-md px-6 py-8 bg-white rounded-xl shadow-lg overflow-hidden">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Session Debug Info</h1>
            
            <div class="space-y-4">
                <div class="p-4 border rounded-lg">
                    <p><strong>Session Status:</strong> {{ $sessionStatus }}</p>
                </div>
                
                <div class="p-4 border rounded-lg">
                    <p><strong>Session Has CSRF Token:</strong> {{ $hasToken }}</p>
                </div>
                
                <div class="p-4 border rounded-lg">
                    <p><strong>CSRF Token Value:</strong> {{ $tokenValue }}</p>
                </div>
                
                <div class="p-4 border rounded-lg">
                    <p><strong>Session Driver:</strong> {{ $sessionDriver }}</p>
                </div>
                
                <div class="p-4 border rounded-lg">
                    <p><strong>Session Table:</strong> {{ $sessionTable }}</p>
                </div>
                
                <div class="p-4 border rounded-lg">
                    <p><strong>Test Form with CSRF:</strong></p>
                    <form method="POST" action="{{ route('test-csrf') }}" class="mt-4">
                        @csrf
                        <button type="submit" class="w-full py-2 px-4 bg-blue-600 text-white rounded-lg">
                            Test CSRF Token
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="mt-6">
                <a href="{{ route('login') }}" class="block w-full py-2 px-4 bg-gray-200 text-center text-gray-700 rounded-lg">
                    Back to Login
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>
