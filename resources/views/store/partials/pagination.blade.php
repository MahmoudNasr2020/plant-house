@if ($paginator->hasPages())
<nav style="display:flex;align-items:center;gap:7px">
    {{-- Prev --}}
    @if ($paginator->onFirstPage())
        <span style="width:36px;height:36px;border:2px solid var(--gp);border-radius:9px;display:flex;align-items:center;justify-content:center;color:#ccc;cursor:not-allowed;font-size:13px">
            <i class="fa fa-chevron-right"></i>
        </span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" style="width:36px;height:36px;border:2px solid var(--gp);border-radius:9px;display:flex;align-items:center;justify-content:center;color:var(--gd);font-size:13px;transition:.2s;text-decoration:none" onmouseover="this.style.background='var(--gd)';this.style.color='#fff'" onmouseout="this.style.background='';this.style.color='var(--gd)'">
            <i class="fa fa-chevron-right"></i>
        </a>
    @endif

    {{-- Pages --}}
    @foreach ($elements as $element)
        @if (is_string($element))
            <span style="padding:0 6px;color:#9aa89e">…</span>
        @endif

        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span style="width:36px;height:36px;background:var(--gd);color:#fff;border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:800">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" style="width:36px;height:36px;border:2px solid var(--gp);border-radius:9px;display:flex;align-items:center;justify-content:center;color:var(--gd);font-size:13px;font-weight:700;text-decoration:none;transition:.2s" onmouseover="this.style.background='var(--gd)';this.style.color='#fff'" onmouseout="this.style.background='';this.style.color='var(--gd)'">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach

    {{-- Next --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" style="width:36px;height:36px;border:2px solid var(--gp);border-radius:9px;display:flex;align-items:center;justify-content:center;color:var(--gd);font-size:13px;transition:.2s;text-decoration:none" onmouseover="this.style.background='var(--gd)';this.style.color='#fff'" onmouseout="this.style.background='';this.style.color='var(--gd)'">
            <i class="fa fa-chevron-left"></i>
        </a>
    @else
        <span style="width:36px;height:36px;border:2px solid var(--gp);border-radius:9px;display:flex;align-items:center;justify-content:center;color:#ccc;cursor:not-allowed;font-size:13px">
            <i class="fa fa-chevron-left"></i>
        </span>
    @endif
</nav>
@endif
