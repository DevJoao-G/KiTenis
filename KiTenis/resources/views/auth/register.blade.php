<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" class="w-100">
        @csrf

        <!-- Name -->
        <div class="mb-3">
            <label for="register_name" class="form-label">
                Nome
            </label>

            <input
                type="text"
                class="form-control @error('name') is-invalid @enderror"
                id="register_name"
                name="name"
                value="{{ old('name') }}"
                required
                autofocus
                autocomplete="name"
            >

            @error('name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Email -->
        <div class="mb-3">
            <label for="register_email" class="form-label">
                Email
            </label>

            <input
                type="email"
                class="form-control @error('email') is-invalid @enderror"
                id="register_email"
                name="email"
                value="{{ old('email') }}"
                required
                autocomplete="username"
            >

            @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="register_password" class="form-label">
                Senha
            </label>

            <input
                type="password"
                class="form-control @error('password') is-invalid @enderror"
                id="register_password"
                name="password"
                required
                autocomplete="new-password"
            >

            @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-3">
            <label for="register_password_confirmation" class="form-label">
                Confirmar Senha
            </label>

            <input
                type="password"
                class="form-control @error('password_confirmation') is-invalid @enderror"
                id="register_password_confirmation"
                name="password_confirmation"
                required
                autocomplete="new-password"
            >

            @error('password_confirmation')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Actions -->
        <div class="d-flex justify-content-between align-items-center mt-4">
            <a href="{{ route('access') }}" class="small text-decoration-none">
                Já possui conta?
            </a>

            <x-primary-button>
                Criar conta
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
