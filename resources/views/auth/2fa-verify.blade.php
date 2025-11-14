<x-guest-layout>
    <!-- Two-Factor Verification Card -->
    <div class="w-full max-w-sm bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 sm:p-8 mx-auto mt-12">

        <!-- Header -->
        <div class="flex flex-col items-center mb-6">
            <img src="{{ asset('logo.jpg') }}" alt="ROPA Logo" class="h-16 w-16 rounded-full shadow-md ring-2 ring-black mb-3">
            <h1 class="text-xl font-bold text-black dark:text-white">Two-Factor Verification</h1>
            <p class="text-gray-600 dark:text-gray-300 mt-1 text-sm text-center">
                Enter the 4-digit code sent to your email to continue
            </p>
        </div>

        <!-- Session Status / Messages -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        @if(session('error'))
            <div class="bg-red-100 text-red-800 p-3 rounded mb-4 text-sm">
                {{ session('error') }}
            </div>
        @endif

        <!-- 2FA Form -->
        <form method="POST" action="{{ route('2fa.verify.post') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="email" value="{{ session('email') ?? old('email') }}">

            <!-- Two-Factor Code -->
            <div>
                <x-input-label for="two_factor_code" :value="__('4-digit Code')" class="text-black dark:text-white"/>
                <x-text-input id="two_factor_code"
                    class="block mt-1 w-full border-black focus:border-black focus:ring-black"
                    type="text"
                    name="two_factor_code"
                    maxlength="4"
                    required autofocus />
                <x-input-error :messages="$errors->get('two_factor_code')" class="mt-1 text-red-600" />
            </div>
<br>
            <!-- Verify Button -->
            <x-primary-button class="w-full bg-indigo-600 hover:bg-indigo-700 text-white focus:ring-indigo-500">
                {{ __('Verify') }}
            </x-primary-button>
        </form>

        <!-- Resend / Cancel Links -->
        <div class="mt-4 text-center text-sm text-gray-600 dark:text-gray-300">
            <form method="POST" action="{{ route('2fa.resend') }}" class="inline">
                @csrf
                <button type="submit" class="underline hover:text-gray-800 dark:hover:text-white">
                    {{ __('Resend code') }}
                </button>
            </form>
            <span class="mx-2">|</span>
            <a href="{{ route('login') }}" class="underline hover:text-gray-800 dark:hover:text-white">
                {{ __('Cancel') }}
            </a>
        </div>

    </div>
</x-guest-layout>
