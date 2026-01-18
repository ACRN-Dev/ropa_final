<x-guest-layout>
    <!-- Register Card -->
    <div class="w-full max-w-md bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 sm:p-8 border border-gray-200 dark:border-gray-700">
        <!-- Header -->
        <div class="flex flex-col items-center mb-6">
            <div class="mb-4">
                <img src="{{ asset('logo.jpg') }}" alt="ACRN Logo" class="h-20 w-20 rounded-full shadow-lg ring-2 ring-orange-600/20 object-cover">
            </div>
            <div class="text-center">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">Create Your Account</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Join the ROPA system and start managing records</p>
                <div class="flex items-center justify-center gap-2 mt-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                    <span class="text-xs font-semibold text-orange-600 dark:text-orange-500">ACRN Data Protection 2026</span>
                </div>
            </div>
        </div>

        <!-- Register Form -->
        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Full Name')" class="text-gray-700 dark:text-gray-300 font-medium mb-1.5" />
                <x-text-input id="name"
                    class="block mt-1 w-full border-gray-300 dark:border-gray-600 
                           focus:border-orange-500 focus:ring-orange-500 
                           rounded-lg shadow-sm transition-colors"
                    type="text"
                    name="name"
                    :value="old('name')"
                    placeholder="Enter your full name"
                    required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-1.5 text-red-600 text-sm" />
            </div>

            <!-- Email -->
            <div>
                <x-input-label for="email" :value="__('Email Address')" class="text-gray-700 dark:text-gray-300 font-medium mb-1.5" />
                <x-text-input id="email"
                    class="block mt-1 w-full border-gray-300 dark:border-gray-600 
                           focus:border-orange-500 focus:ring-orange-500 
                           rounded-lg shadow-sm transition-colors"
                    type="email"
                    name="email"
                    :value="old('email')"
                    placeholder="Enter your email address"
                    required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-red-600 text-sm" />
            </div>

            <!-- Password -->
            <div>
                <x-input-label for="password" :value="__('Password')" class="text-gray-700 dark:text-gray-300 font-medium mb-1.5" />
                <x-text-input id="password"
                    class="block mt-1 w-full border-gray-300 dark:border-gray-600 
                           focus:border-orange-500 focus:ring-orange-500 
                           rounded-lg shadow-sm transition-colors"
                    type="password"
                    name="password"
                    placeholder="Create a password"
                    required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-red-600 text-sm" />
            </div>

            <!-- Confirm Password -->
            <div>
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-gray-700 dark:text-gray-300 font-medium mb-1.5" />
                <x-text-input id="password_confirmation"
                    class="block mt-1 w-full border-gray-300 dark:border-gray-600 
                           focus:border-orange-500 focus:ring-orange-500 
                           rounded-lg shadow-sm transition-colors"
                    type="password"
                    name="password_confirmation"
                    placeholder="Confirm your password"
                    required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5 text-red-600 text-sm" />
            </div>

            <!-- Register Button -->
            <div class="pt-2">
                <x-primary-button class="w-full bg-orange-600 hover:bg-orange-700 
                                         text-white font-semibold py-2.5 
                                         focus:ring-orange-500 focus:ring-offset-2
                                         transition-all duration-200 shadow-md hover:shadow-lg">
                    <span class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                        {{ __('Create Account') }}
                    </span>
                </x-primary-button>
            </div>

            <!-- Login Link -->
            <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                <p class="text-center text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Already have an account?') }}
                    <a href="{{ route('login') }}"
                        class="font-semibold text-orange-600 hover:text-orange-700 dark:text-orange-500 dark:hover:text-orange-400 
                               transition-colors underline underline-offset-2">
                        {{ __('Log in here') }}
                    </a>
                </p>
            </div>
        </form>
    </div>
</x-guest-layout>
