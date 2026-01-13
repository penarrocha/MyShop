<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" id="loginForm" data-recaptcha-required="{{ ($recaptchaRequired ?? false) ? 1 : 0 }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('messages.password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                type="password"
                name="password"
                required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('messages.remember_me') }}</span>
            </label>
        </div>

        <input type="hidden" name="recaptcha_token" id="recaptcha_token">
        @error('recaptcha_token')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
        <div class="flex items-center justify-between mt-4">
            <div class="flex flex-col text-sm">
                @if (Route::has('password.request'))
                <a class="underline text-gray-600 hover:text-gray-900"
                    href="{{ route('password.request') }}">
                    {{ __('messages.forgot_password') }}
                </a>
                @endif

                @if (Route::has('register'))
                <a class="underline text-gray-600 hover:text-gray-900 mt-2"
                    href="{{ route('register') }}">
                    {{ __('messages.register') }}
                </a>
                @endif
            </div>

            <x-primary-button class="ms-3">
                {{ __('messages.login') }}
            </x-primary-button>
        </div>

        {{--
        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
        {{ __('messages.forgot_password') }}
        </a>
        @endif

        <x-primary-button class="ms-3">
            {{ __('messages.login') }}
        </x-primary-button>
        </div>
        --}}

    </form>
</x-guest-layout>