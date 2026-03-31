@if ($paginator->hasPages())
    <nav class="pagination-nav" role="navigation" aria-label="Страницы">
        <p class="pagination-meta">
            @if ($paginator->firstItem())
                Записи {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} из {{ $paginator->total() }}
            @endif
        </p>
        <ul class="pagination-list">
            @if ($paginator->onFirstPage())
                <li><span class="pagination-link pagination-link--disabled">&lsaquo;</span></li>
            @else
                <li><a class="pagination-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">&lsaquo;</a></li>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li><span class="pagination-ellipsis">{{ $element }}</span></li>
                @endif
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li><span class="pagination-current">{{ $page }}</span></li>
                        @else
                            <li><a class="pagination-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li><a class="pagination-link" href="{{ $paginator->nextPageUrl() }}" rel="next">&rsaquo;</a></li>
            @else
                <li><span class="pagination-link pagination-link--disabled">&rsaquo;</span></li>
            @endif
        </ul>
    </nav>
@endif
