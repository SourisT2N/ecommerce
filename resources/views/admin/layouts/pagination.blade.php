@if ($paginator->hasPages())
    <!-- Pagination -->
    @php
        $totalDisplay = 5;
        $pageCurrent = $paginator->currentPage();
        $pageNext = round($pageCurrent + ($totalDisplay - 1)/2);
        $pagePrevious = round($pageCurrent - ($totalDisplay - 1)/2);
        $urlPrevious = route($nameRoute,array_merge($query,['page' => $pageCurrent - 1]));
        $urlNext = route($nameRoute,array_merge($query,['page' => ($pageCurrent + 1)]));
    @endphp
    <div class="pull-right pagination">
        <ul class="pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li aria-disabled="true" aria-label="« Previous" class="page-item disabled">
                    <span aria-hidden="true" class="page-link">‹</span>
                </li>
            @else
                <li aria-disabled="true" aria-label="« Previous" class="page-item">
                    <a href="{{ $urlPrevious }}" rel="prev" aria-label="« Previous" class="page-link">‹</a>
                </li>
                @endif
            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @php
                            $params = array_merge($query,['page' => $page]);
                            $url = route($nameRoute,$params);
                        @endphp
                        @if ($page == $paginator->currentPage())
                            <li aria-current="page" class="page-item active"><span class="page-link">{{ $page }}</span></li>
                        @elseif (($page <= $pageNext && $page >= $pagePrevious) || $page == $paginator->lastPage() || $page == 1)
                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @elseif ($page == ($paginator->lastPage() - 1) || $page == 2)
                            <li class="page-item"><span class="page-link">...</span></li>
                        @endif
                    @endforeach
                @endif
            @endforeach
            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a href="{{ $urlNext }}"  rel="next" aria-label="Next »" class="page-link">
                        ›
                    </a>
                </li>
            @else
                <li aria-disabled="true" aria-label="Next »" class="page-item disabled">
                    <span aria-hidden="true" class="page-link">›</span>
                </li>
            @endif
        </ul>
    </div>
    <!-- Pagination -->
@endif