@if ($paginator->hasPages())
    <div class="pagination-wrap" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;padding:12px 0 0;border-top:1px solid #E5E7EB;margin-top:16px">
        <div style="font-size:13px;color:#6B7280">
            @if ($paginator->total() > 0)
                Menampilkan <span style="font-weight:600;color:#374151">{{ $paginator->firstItem() }}</span>
                -
                <span style="font-weight:600;color:#374151">{{ $paginator->lastItem() }}</span>
                dari <span style="font-weight:600;color:#374151">{{ $paginator->total() }}</span>
                hasil
            @endif
        </div>
        <div class="pagination" style="margin:0;display:flex;gap:4px;flex-wrap:wrap">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="disabled" style="padding:6px 12px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:12px;color:#D1D5DB;opacity:.4;pointer-events:none">&laquo;</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" style="padding:6px 12px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:12px;text-decoration:none;color:#6B7280;transition:all .15s">&laquo;</a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="disabled" style="padding:6px 12px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:12px;color:#D1D5DB;opacity:.4;pointer-events:none">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="active" style="padding:6px 12px;border:1.5px solid #1E3A5F;border-radius:6px;font-size:12px;color:#fff;background:#1E3A5F;font-weight:600" aria-current="page">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" style="padding:6px 12px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:12px;text-decoration:none;color:#6B7280;transition:all .15s">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" style="padding:6px 12px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:12px;text-decoration:none;color:#6B7280;transition:all .15s">&raquo;</a>
            @else
                <span class="disabled" style="padding:6px 12px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:12px;color:#D1D5DB;opacity:.4;pointer-events:none">&raquo;</span>
            @endif
        </div>
    </div>
@endif
