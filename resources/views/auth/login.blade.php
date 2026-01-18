<x-guest-layout>
    <!-- Login Card -->
    <div class="w-full max-w-md bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 sm:p-8 border border-gray-200 dark:border-gray-700">
        <!-- Header -->
        <div class="flex flex-col items-center mb-6">
            <div class="mb-4">
                <img src="{{ asset('logo.jpg') }}" alt="ACRN Logo" class="h-20 w-20 rounded-full shadow-lg ring-2 ring-orange-600/20 object-cover">
            </div>
            <div class="text-center">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">Login to Your Account</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Enter your credentials to access the ROPA dashboard</p>
                <div class="flex items-center justify-center gap-2 mt-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                    <span class="text-xs font-semibold text-orange-600 dark:text-orange-500">ACRN Data Protection 2026</span>
                </div>
            </div>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email Address')" class="text-gray-700 dark:text-gray-300 font-medium mb-1.5"/>
                <x-text-input id="email"
                    class="block mt-1 w-full border-gray-300 dark:border-gray-600 
                           focus:border-orange-500 focus:ring-orange-500 
                           rounded-lg shadow-sm transition-colors"
                    type="email"
                    name="email"
                    :value="old('email')"
                    placeholder="Enter your email address"
                    required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-red-600 text-sm" />
            </div>

            <!-- Password -->
            <div>
                <x-input-label for="password" :value="__('Password')" class="text-gray-700 dark:text-gray-300 font-medium mb-1.5"/>
                <x-text-input id="password"
                    class="block mt-1 w-full border-gray-300 dark:border-gray-600 
                           focus:border-orange-500 focus:ring-orange-500 
                           rounded-lg shadow-sm transition-colors"
                    type="password"
                    name="password"
                    placeholder="Enter your password"
                    required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-red-600 text-sm" />
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember_me" 
                        type="checkbox"
                        class="rounded border-gray-300 text-orange-600 shadow-sm 
                               focus:ring-orange-500 focus:ring-offset-0"
                        name="remember">
                    <label for="remember_me" class="ml-2 text-sm text-gray-600 dark:text-gray-300 cursor-pointer">
                        {{ __('Remember me') }}
                    </label>
                </div>

                @if (Route::has('password.request'))
                    <a class="text-sm text-orange-600 hover:text-orange-700 dark:text-orange-500 dark:hover:text-orange-400 
                              font-medium transition-colors"
                        href="{{ route('password.request') }}">
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>

            <!-- Login Button -->
            <div class="pt-2">
                <x-primary-button class="w-full bg-orange-600 hover:bg-orange-700 
                                         text-white font-semibold py-2.5 
                                         focus:ring-orange-500 focus:ring-offset-2
                                         transition-all duration-200 shadow-md hover:shadow-lg">
                    <span class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        {{ __('Log in') }}
                    </span>
                </x-primary-button>
            </div>

            <!-- Register Link -->
            <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                <p class="text-center text-sm text-gray-600 dark:text-gray-400">
                    {{ __("Don't have an account?") }}
                    <a href="{{ route('register') }}"
                        class="font-semibold text-orange-600 hover:text-orange-700 dark:text-orange-500 dark:hover:text-orange-400 
                               transition-colors underline underline-offset-2">
                        {{ __('Register here') }}
                    </a>
                </p>
            </div>
        </form>
    </div>
</x-guest-layout>
