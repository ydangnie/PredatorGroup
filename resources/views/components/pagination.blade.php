{{--  resources/views/components/pagination.blade.php  --}}
@if ($paginator->hasPages())
    <nav class="flex items-center justify-between mt-8">
        <div class="text-sm text-gray-400">
            Hiển thị {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }}
            trong tổng {{ $paginator->total() }} sản phẩm
        </div>

        <div class="flex items-center gap-2">
            {{-- Nút Previous --}}
            @if ($paginator->onFirstPage())
                <span class="px-4 py-2 bg-gray-700 text-gray-500 rounded-lg cursor-not-allowed">‹ Trước</span>
            @else
                <button wire:click="previousPage" wire:loading.attr="disabled"
                        class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition">
                    ‹ Trước
                </button>
            @endif

            {{-- Các số trang --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="px-4 py-2 text-gray-500">...</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="px-4 py-2 bg-yellow-500 text-black font-bold rounded-lg">
                                {{ $page }}
                            </span>
                        @else
                            <button wire:click="gotoPage({{ $page }})"
                                    class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition">
                                {{ $page }}
                            </button>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Nút Next --}}
            @if ($paginator->hasMorePages())
                <button wire:click="nextPage" wire:loading.attr="disabled"
                        class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition">
                    Tiếp ›
                </button>
            @else
                <span class="px-4 py-2 bg-gray-700 text-gray-500 rounded-lg cursor-not-allowed">Tiếp ›</span>
            @endif
        </div>
    </nav>
@endif
