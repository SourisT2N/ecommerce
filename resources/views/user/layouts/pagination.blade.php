@if($paginator->hasPages())
    <!-- Pagination -->
    @php
        $totalDisplay = 5;
        $pageCurrent = $paginator->currentPage();
        $pageNext = round($pageCurrent + ($totalDisplay - 1)/2);
        $pagePrevious = round($pageCurrent - ($totalDisplay - 1)/2);
    @endphp
    <div class="pagination" style="display: block;text-align: center">
        <ul class="pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li><span href="#"> << </span></li>
            @else
                <li><a href="#"> << </a></li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="active"><span>{{ $page }}</span></li>
                        @elseif (($page >= $pagePrevious && $page <= $pageNext) || $page == $paginator->lastPage() || $page == 1)
                            <li><a href="{{ $url }}">{{ $page }}</a></li>
                        @elseif ($page == $paginator->lastPage() - 1 || $page == 2)
                            <li><span>...</span></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li><a href="#"> >> </a></li>
            @else
                <li><span href="#"> >> </span></li>
            @endif
        </ul>
    </div>
    <!-- Pagination -->
@endif
