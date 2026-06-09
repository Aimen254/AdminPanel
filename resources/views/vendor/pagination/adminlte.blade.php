@if ($paginator->hasPages())
<div class="card-footer clearfix">
  <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">

    {{-- Row count info --}}
    @if(method_exists($paginator, 'total'))
    <small class="text-muted">
      Showing
      <strong>{{ $paginator->firstItem() }}</strong>
      &ndash;
      <strong>{{ $paginator->lastItem() }}</strong>
      of
      <strong>{{ number_format($paginator->total()) }}</strong>
      entries
    </small>
    @endif

    {{-- Page buttons --}}
    <ul class="pagination pagination-sm m-0">

      {{-- Previous --}}
      @if ($paginator->onFirstPage())
        <li class="page-item disabled" aria-disabled="true">
          <span class="page-link">&laquo;</span>
        </li>
      @else
        <li class="page-item">
          <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo;</a>
        </li>
      @endif

      {{-- Page numbers --}}
      @foreach ($elements as $element)
        @if (is_string($element))
          <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
        @endif

        @if (is_array($element))
          @foreach ($element as $page => $url)
            @if ($page == $paginator->currentPage())
              <li class="page-item active" aria-current="page">
                <span class="page-link">{{ $page }}</span>
              </li>
            @else
              <li class="page-item">
                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
              </li>
            @endif
          @endforeach
        @endif
      @endforeach

      {{-- Next --}}
      @if ($paginator->hasMorePages())
        <li class="page-item">
          <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">&raquo;</a>
        </li>
      @else
        <li class="page-item disabled" aria-disabled="true">
          <span class="page-link">&raquo;</span>
        </li>
      @endif

    </ul>
  </div>
</div>
@endif
