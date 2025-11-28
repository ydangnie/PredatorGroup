<div class="bg-gray-900 min-h-screen text-white p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <h1 class="text-3xl font-bold text-yellow-400">Danh sách sản phẩm</h1>
            <a href="{{ route('admin.san-pham.create') }}"
                class="bg-yellow-500 hover:bg-yellow-600 text-black font-bold py-3 px-6 rounded-lg transition flex items-center gap-2">
                <i class="fas fa-plus"></i> Thêm sản phẩm mới
            </a>
        </div>

        <!-- Bộ lọc -->
        <div class="bg-gray-800 rounded-xl p-5 mb-6 shadow-xl">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <input type="text" wire:model.live.debounce.500ms="search" placeholder="Tìm tên sản phẩm..."
                    class="input-yellow">

                <select wire:model.live="category_id" class="input-yellow">
                    <option value="">Tất cả danh mục</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>

                <select wire:model.live="brand_id" class="input-yellow">
                    <option value="">Tất cả thương hiệu</option>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                    @endforeach
                </select>

                <div class="text-right">
                    <span class="text-yellow-400 font-bold">{{ $products->total() }} sản phẩm</span>
                </div>
            </div>
        </div>

        <!-- Bảng sản phẩm -->
        <div class="bg-gray-800 rounded-xl shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-700">
                        <tr>
                            <th class="px-6 py-4 text-left">Ảnh</th>
                            <th class="px-6 py-4 text-left">Tên sản phẩm</th>
                            <th class="px-6 py-4 text-left">Danh mục</th>
                            <th class="px-6 py-4 text-left">Thương hiệu</th>
                            <th class="px-6 py-4 text-center">Giá</th>
                            <th class="px-6 py-4 text-center">Kho</th>
                            <th class="px-6 py-4 text-center">Trạng thái</th>
                            <th class="px-6 py-4 text-center">Biến thể</th>
                            <th class="px-6 py-4 text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @forelse($products as $p)
                            <tr class="hover:bg-gray-700 transition">
                                <td class="px-6 py-4">
                                    @if ($p->images && count($p->images) > 0)
                                        <img src="{{ filter_var($p->images[0], FILTER_VALIDATE_URL) ? $p->images[0] : asset('storage/' . $p->images[0]) }}"
                                            width="150" loading="lazy"
                                            class="w-16 h-16 object-cover rounded-lg shadow">
                                    @else
                                        <div class="bg-gray-600 border-2 border-dashed rounded-xl w-16 h-16"></div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-medium">{{ Str::limit($p->name, 40) }}</td>
                                <td class="px-6 py-4 text-gray-400">{{ $p->category?->name ?? '—' }}</td>
                                <td class="px-6 py-4 text-gray-400">{{ $p->brand?->name ?? '—' }}</td>
                                <td class="px-6 py-4 text-center font-bold text-yellow-400">
                                    {{ number_format($p->lowest_price) }}₫
                                </td>
                                <td
                                    class="px-6 py-4 text-center {{ $p->total_stock > 0 ? 'text-green-400' : 'text-red-400' }}">
                                    {{ $p->total_stock }}
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <button wire:click="toggleActive({{ $p->id }})" wire:loading.attr="disabled"
                                        class="relative inline-flex items-center h-6 rounded-full w-11 transition-colors focus:outline-none
                                            {{ $p->is_active ? 'bg-emerald-500' : 'bg-gray-600' }}">
                                        <span
                                            class="inline-block w-4 h-4 transform transition-transform bg-white rounded-full shadow-lg
                                            {{ $p->is_active ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                    </button>
                                    <span
                                        class="ml-2 text-sm {{ $p->is_active ? 'text-emerald-400' : 'text-gray-500' }}">
                                        {{ $p->is_active ? 'Đang bán' : 'Tạm ẩn' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if ($p->has_variations)
                                        <span class="bg-purple-600 text-xs px-3 py-1 rounded-full">
                                            {{ $p->variations->count() }} biến thể
                                        </span>
                                    @else
                                        <span class="text-gray-500">Không</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-3">
                                        <a href="{{ route('admin.san-pham.edit', $p) }}"
                                            class="text-blue-400 hover:text-blue-300">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button wire:click="delete({{ $p->id }})"
                                            wire:confirm="Xóa sản phẩm này? Dữ liệu sẽ mất vĩnh viễn!"
                                            class="text-red-400 hover:text-red-300">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-12 text-gray-500">
                                    <i class="fas fa-box-open text-6xl mb-4 block"></i>
                                    Chưa có sản phẩm nào
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Phân trang đẹp -->
            <div class="mt-8">
                {{ $products->links('components.pagination') }}
            </div>
        </div>
    </div>
</div>

{{-- Toast thông báo (nếu dùng filament hoặc tự làm) --}}
@push('scripts')
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('toast', (message, type = 'success') => {
                // Dùng toast library bạn thích (toastify, sweetalert, alpine...)
                alert(message); // tạm thời
            });
        });
    </script>
@endpush
