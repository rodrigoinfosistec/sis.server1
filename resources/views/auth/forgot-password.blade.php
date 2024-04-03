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

        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </div>

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                {{ session('status') }}
            </div>
        @endif

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="form-floating">
                <x-input type="email" class="form-control" 
                    id="email" placeholder="name@example.com" name="email" :value="old('email')" required autofocus autocomplete="username"/>

                <x-label for="email">
                    E-mail
                </x-label>
            </div>

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                    Login
                </a>

                <x-button class="ms-4">
                    Enviar e-mail
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
