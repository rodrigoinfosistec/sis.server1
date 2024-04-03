@auth
    {{-- block access to register --}}
    <script>
        window.location.href = "/home";
    </script>
@endauth

<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <h1 class="h3 mb-3 fw-normal fst-italic text-black">
                
            </h1>

            <div class="form-floating">
                <x-input type="email" class="form-control" 
                    style="
                    margin-bottom: -1px;
                    border-bottom-right-radius: 0;
                    border-bottom-left-radius: 0;
                " id="email" placeholder="name@example.com" name="email" :value="old('email')" required autofocus autocomplete="username"/>

                <x-label for="email">
                    E-mail
                </x-label>
            </div>

            <div class="form-floating">
                <x-input type="password" class="form-control"
                    style="
                    border-top-left-radius: 0;
                    border-top-right-radius: 0;
                " id="password" placeholder="Password" name="password" required autocomplete="current-password"/>

                <x-label for="password">
                    Senha
                </x-label>
            </div>

            <div class="block mt-4">
                <label for="remember_me" class="flex items-center">
                    <x-checkbox id="remember_me" name="remember" />
                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400 text-black">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <x-button class="ml-4">
                    {{ __('Log in') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
