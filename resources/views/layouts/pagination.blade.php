@if ($paginator->hasPages())
    @php
        $totalDisplay = 5;
        $pageCurrent = $paginator->currentPage();
        $pageNext = round($pageCurrent + ($totalDisplay - 1)/2);
        $pagePrevious = round($pageCurrent - ($totalDisplay - 1)/2);
        $urlPrevious = route($nameRoute,$parameter??[]).'?'.http_build_query(array_merge($query,['page' => $pageCurrent - 1]));
        $urlNext = route($nameRoute,$parameter??[]).'?'.http_build_query(array_merge($query,['page' => ($pageCurrent + 1)]));
    @endphp
    <nav>
      <ul class="pagination">
        <li>
          @if ($paginator->onFirstPage())
            <span aria-label="Previous">
              <span aria-hidden="true"><i class="ion-ios-arrow-left"></i></span>
            </span>
          @else
            <a href="{{ $urlPrevious }}" aria-label="Previous">
              <span aria-hidden="true"><i class="ion-ios-arrow-left"></i></span>
            </a>
          @endif
        </li>
        @foreach ($elements as $element)
          @if (is_array($element))
            @foreach ($element as $page => $url)
              @php
                  $params = array_merge($query,['page' => $page]);
                  $url = route($nameRoute,$parameter??[]).'?'.http_build_query($params);
              @endphp
              @if ($page == $paginator->currentPage())
                <li class="active"><a href="{{ $url }}">{{ $page }}</a></li>
              @elseif (($page <= $pageNext && $page >= $pagePrevious) || $page == $paginator->lastPage() || $page == 1)
                <li><a href="{{ $url }}">{{ $page }}</a></li>
              @elseif ($page == ($paginator->lastPage() - 1) || $page == 2)
                <li class="disabled"><a href="#">..</a></li>
              @endif
            @endforeach
          @endif
        @endforeach
        <li>
        @if ($paginator->hasMorePages())
        <a href="{{ $urlNext }}" aria-label="Next">
          <span aria-hidden="true"><i class="ion-ios-arrow-right"></i></span>
        </a>
        @else
        <span aria-label="Next">
          <span aria-hidden="true"><i class="ion-ios-arrow-right"></i></span>
        </span>
        @endif
        </li>
      </ul>
  </nav>
@endif