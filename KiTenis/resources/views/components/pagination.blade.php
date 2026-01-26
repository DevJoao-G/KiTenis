@props(['paginator'])

@if ($paginator->hasPages())
    <div class="d-flex flex-column align-items-center gap-2 mt-5">

        {{-- Info de resultados --}}
        <small class="text-muted">
            Mostrando
            <strong>{{ $paginator->firstItem() }}</strong>
            a
            <strong>{{ $paginator->lastItem() }}</strong>
            de
            <strong>{{ $paginator->total() }}</strong>
            resultados
        </small>

        {{-- Navegação --}}
        <nav aria-label="Paginação">
            <ul class="pagination pagination-sm mb-0">

                {{-- Primeira --}}
                <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $paginator->url(1) }}" aria-label="Primeira">
                        «
                    </a>
                </li>

                {{-- Anterior --}}
                <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" aria-label="Anterior">
                        ‹
                    </a>
                </li>

                {{-- Páginas --}}
                @foreach ($paginator->getUrlRange(
                    max(1, $paginator->currentPage() - 1),
                    min($paginator->lastPage(), $paginator->currentPage() + 1)
                ) as $page => $url)
                    <li class="page-item {{ $page == $paginator->currentPage() ? 'active' : '' }}">
                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                    </li>
                @endforeach

                {{-- Próxima --}}
                <li class="page-item {{ !$paginator->hasMorePages() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" aria-label="Próxima">
                        ›
                    </a>
                </li>

                {{-- Última --}}
                <li class="page-item {{ !$paginator->hasMorePages() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}" aria-label="Última">
                        »
                    </a>
                </li>

            </ul>
        </nav>
    </div>
@endif
