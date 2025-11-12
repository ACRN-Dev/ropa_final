<x-guest-layout>

    
        <!-- Login Card -->
        <div class="w-full max-w-sm bg-white dark:bg-gray-800 rounded-xl shadow-md p-4 sm:p-6">
            
            <!-- Header -->
            <div class="flex flex-col items-center mb-4">
                <img src="{{ asset('logo.jpg') }}" alt="ROPA Logo" class="h-16 w-16 rounded-full shadow-md ring-2 ring-black mb-3">
                <h1 class="text-xl font-bold text-black dark:text-white">Login to Your Account</h1>
                <p class="text-gray-600 dark:text-gray-300 mt-1 text-sm text-center">Enter your credentials to access the ROPA dashboard</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-3" :status="session('status')" />

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-3">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email')" class="text-black dark:text-white"/>
                    <x-text-input id="email"
                        class="block mt-1 w-full border-black focus:border-black focus:ring-black"
                        type="email"
                        name="email"
                        :value="old('email')"
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
                        required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-1 text-red-600" />
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input id="remember_me" type="checkbox"
                        class="rounded border-gray-300 text-black shadow-sm focus:ring-black"
                        name="remember">
                    <label for="remember_me" class="ml-2 text-sm text-gray-600 dark:text-gray-300">
                        {{ __('Remember me') }}
                    </label>
                </div>

                <!-- Login Actions -->
                <div class="flex flex-col sm:flex-row sm:justify-between items-center mt-2">
                    @if (Route::has('password.request'))
                        <a class="underline text-sm text-black hover:text-gray-700 dark:text-gray-300 dark:hover:text-white mb-2 sm:mb-0"
                            href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif

                   <x-primary-button class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white focus:ring-green-500">
    {{ __('Log in') }}
</x-primary-button>

                </div>

                <!-- Register Link -->
                <div class="mt-4 text-center">
                    <a href="{{ route('register') }}"
                        class="underline text-sm text-black hover:text-gray-700 dark:text-gray-300 dark:hover:text-white">
                        {{ __("Don't have an account? Register here") }}
                    </a>
                </div>

            </form>
        </div>

    

</x-guest-layout>
