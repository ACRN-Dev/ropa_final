<x-guest-layout>

    <!-- Register Card -->
    <div class="w-full max-w-sm bg-white dark:bg-gray-800 rounded-xl shadow-md p-4 sm:p-6">
        
        <!-- Header -->
        <div class="flex flex-col items-center mb-4">
            <img src="{{ asset('logo.jpg') }}" alt="ROPA Logo" class="h-16 w-16 rounded-full shadow-md ring-2 ring-black mb-3">
            <h1 class="text-xl font-bold text-black dark:text-white">Create Your Account</h1>
            <p class="text-gray-600 dark:text-gray-300 mt-1 text-sm text-center">Join the ROPA system and start managing records</p>
        </div>

        <!-- Register Form -->
        <form method="POST" action="{{ route('register') }}" class="space-y-3">
            @csrf

            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Full Name')" class="text-black dark:text-white" />
                <x-text-input id="name"
                    class="block mt-1 w-full border-black focus:border-black focus:ring-black"
                    type="text"
                    name="name"
                    :value="old('name')"
                    required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-1 text-red-600" />
            </div>

            <!-- Email -->
            <div>
                <x-input-label for="email" :value="__('Email')" class="text-black dark:text-white" />
                <x-text-input id="email"
                    class="block mt-1 w-full border-black focus:border-black focus:ring-black"
                    type="email"
                    name="email"
                    :value="old('email')"
                    required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-600" />
            </div>

            <!-- Password -->
            <div>
                <x-input-label for="password" :value="__('Password')" class="text-black dark:text-white" />
                <x-text-input id="password"
                    class="block mt-1 w-full border-black focus:border-black focus:ring-black"
                    type="password"
                    name="password"
                    required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-1 text-red-600" />
            </div>

            <!-- Confirm Password -->
            <div>
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-black dark:text-white" />
                <x-text-input id="password_confirmation"
                    class="block mt-1 w-full border-black focus:border-black focus:ring-black"
                    type="password"
                    name="password_confirmation"
                    required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-red-600" />
            </div>

            <!-- Register Button -->
            <div class="flex flex-col sm:flex-row sm:justify-between items-center mt-2">
                <a href="{{ route('login') }}"
                   class="underline text-sm text-black hover:text-gray-700 dark:text-gray-300 dark:hover:text-white mb-2 sm:mb-0">
                    {{ __('Already registered? Log in here') }}
                </a>

                <x-primary-button class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white focus:ring-green-500">
                    {{ __('Register') }}
                </x-primary-button>
            </div>
        </form>
    </div>

</x-guest-layout>
