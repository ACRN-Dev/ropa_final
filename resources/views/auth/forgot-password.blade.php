<x-guest-layout>

    <!-- Reset Password Card -->
    <div class="w-full max-w-sm bg-white dark:bg-gray-800 rounded-xl shadow-md p-4 sm:p-6 mx-auto mt-12">

        <!-- Header -->
        <div class="flex flex-col items-center mb-4">
            <img src="{{ asset('logo.jpg') }}" alt="ROPA Logo" class="h-16 w-16 rounded-full shadow-md ring-2 ring-black mb-3">
            <h1 class="text-xl font-bold text-black dark:text-white">Reset Your Password</h1>
            <p class="text-gray-600 dark:text-gray-300 mt-1 text-sm text-center">
                Forgot your ROPA password? No problem. Enter your email and we’ll send you a link to reset it.
            </p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-3" :status="session('status')" />

        <!-- Reset Password Form -->
        <form method="POST" action="{{ route('password.email') }}" class="space-y-3">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" class="text-black dark:text-white"/>
                <x-text-input id="email"
                    class="block mt-1 w-full border-black focus:border-black focus:ring-black"
                    type="email"
                    name="email"
                    :value="old('email')"
                    required autofocus />
                <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-600" />
            </div>

            <!-- Submit Button -->
            <div class="flex justify-center mt-4">
                <x-primary-button class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white focus:ring-green-500">
                    {{ __('Email Password Reset Link') }}
                </x-primary-button>
            </div>
        </form>

        <!-- Back to Login -->
        <div class="flex justify-center mt-6">
            <a href="{{ route('login') }}"
               class="inline-flex items-center px-4 py-2 bg-black border border-transparent rounded-md
                      font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-800
                      focus:bg-gray-800 active:bg-gray-900 focus:outline-none focus:ring-2
                      focus:ring-offset-2 focus:ring-green-500 transition ease-in-out duration-150">
                <i class="fas fa-arrow-left mr-2"></i> Back to Login
            </a>
        </div>

    </div>

</x-guest-layout>
