<x-guest-layout>
    <!-- Reset Password Card -->
    <div class="w-full max-w-sm bg-white dark:bg-gray-800 rounded-xl shadow-md p-4 sm:p-6 mx-auto mt-10">
        
        <!-- Header -->
        <div class="flex flex-col items-center mb-4">
            <img src="{{ asset('logo.jpg') }}" alt="ROPA Logo" class="h-16 w-16 rounded-full shadow-md ring-2 ring-black mb-3">
            <h1 class="text-xl font-bold text-black dark:text-white text-center">Reset Your Password</h1>
            <p class="text-gray-600 dark:text-gray-300 mt-1 text-sm text-center">Enter your new password to access your ROPA account</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-3" :status="session('status')" />

        <!-- Reset Password Form -->
        <form method="POST" action="{{ route('password.store') }}" class="space-y-3">
            @csrf

            <!-- Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" class="text-black dark:text-white"/>
                <x-text-input id="email"
                    class="block mt-1 w-full border-black focus:border-black focus:ring-black"
                    type="email"
                    name="email"
                    :value="old('email', $request->email)"
                    required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-600" />
            </div>

            <!-- Password -->
            <div>
                <x-input-label for="password" :value="__('Password')" class="text-black dark:text-white"/>
                <x-text-input id="password"
                    class="block mt-1 w-full border-black focus:border-black focus:ring-black"
                    type="password"
                    name="password"
                    required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-1 text-red-600" />
            </div>

            <!-- Confirm Password -->
            <div>
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-black dark:text-white"/>
                <x-text-input id="password_confirmation"
                    class="block mt-1 w-full border-black focus:border-black focus:ring-black"
                    type="password"
                    name="password_confirmation"
                    required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-red-600" />
            </div>

            <!-- Reset Button -->
            <div class="flex flex-col sm:flex-row sm:justify-between items-center mt-2">
                <x-primary-button class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white focus:ring-green-500">
                    {{ __('Reset Password') }}
                </x-primary-button>
            </div>

            <!-- Back to Login -->
            <div class="mt-4 text-center">
                <a href="{{ route('login') }}"
                    class="underline text-sm text-black hover:text-gray-700 dark:text-gray-300 dark:hover:text-white">
                    {{ __('Back to Login') }}
                </a>
            </div>
        </form>
    </div>
</x-guest-layout>
