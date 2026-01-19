<nav class="bg-white shadow px-6 py-4 flex justify-between items-center">
    <a href="{{ route('home') }}" class="text-xl font-bold">
        KiTenis
    </a>

    <div class="flex items-center gap-4">
        @auth
            <a href="{{ route('account') }}" class="text-sm">
                Minha Conta
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm text-red-600">
                    Sair
                </button>
            </form>
        @else
            <a href="{{ route('login') }}" class="text-sm">
                Entrar
            </a>
        @endauth
    </div>
</nav>
