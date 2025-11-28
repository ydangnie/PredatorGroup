<div class="max-w-7xl mx-auto bg-gray-900 text-white min-h-screen p-6">
    <div class="bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-2xl border border-gray-700 p-8">

        <h1 class="text-4xl font-extrabold mb-10 text-yellow-400 drop-shadow-md">
            {{ $product ? 'Sửa sản phẩm #' . $product->name : 'Thêm sản phẩm mới' }}
        </h1>

        <form wire:submit="save" class="space-y-10">

            <!-- Tên sản phẩm -->
            <div>
                <label class="block text-lg font-semibold text-gray-200 mb-2">Tên sản phẩm *</label>
                <input type="text" wire:model.live="name"
                    class="w-full px-5 py-4 bg-gray-700 border border-gray-600 rounded-xl text-white placeholder-gray-400 focus:ring-4 focus:ring-yellow-500 focus:border-yellow-500 transition"
                    placeholder="Nhập tên sản phẩm..." required>
                <div class="text-sm text-gray-400 mt-2">
                    Slug: <span id="slug-preview" class="text-yellow-300 font-bold">{{ Str::slug($name) }}</span>
                </div>
            </div>

            <!-- Danh mục & Thương hiệu -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="block text-lg font-semibold text-gray-200 mb-2">Danh mục *</label>
                    <select wire:model="category_id"
                        class="w-full px-5 py-4 bg-gray-700 border border-gray-600 rounded-xl text-white focus:ring-4 focus:ring-yellow-500 focus:border-yellow-500 transition"
                        required>
                        <option value="">Chọn danh mục</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-lg font-semibold text-gray-200 mb-2">Thương hiệu *</label>
                    <select wire:model="brand_id"
                        class="w-full px-5 py-4 bg-gray-700 border border-gray-600 rounded-xl text-white focus:ring-4 focus:ring-yellow-500 focus:border-yellow-500 transition"
                        required>
                        <option value="">Chọn thương hiệu</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Checkbox có biến thể -->
            <div class="flex items-center gap-4">
                <input type="checkbox" wire:model.live="has_variations" id="has_variations"
                    class="w-7 h-7 text-yellow-500 bg-gray-700 border-gray-600 rounded focus:ring-yellow-500">
                <label class="text-xl font-semibold text-gray-100" for="has_variations">
                    Sản phẩm có nhiều biến thể (màu sắc, size, dây...)
                </label>
            </div>
            <!-- THÊM MỚI: Nút bật/tắt hiển thị sản phẩm -->
            <div class="flex items-center gap-4 bg-gray-800/50 p-6 rounded-xl border border-gray-700 mt-6">
                <input type="checkbox" wire:model.live="is_active" id="is_active"
                    class="w-8 h-8 text-emerald-500 bg-gray-700 border-gray-600 rounded focus:ring-emerald-500 focus:ring-4 transition shadow-lg">
                <label for="is_active" class="text-xl font-semibold text-gray-100 cursor-pointer select-none">
                    <div class="flex items-center gap-3">
                        <span>{{ $is_active ? 'Đang hiển thị' : 'Tạm ẩn' }}</span>
                        <span class="text-emerald-400 animate-pulse">{{ $is_active ? '✓' : '○' }}</span>
                    </div>
                    <div class="text-sm text-gray-400 mt-1">
                        {{ $is_active ? 'Khách hàng có thể xem và mua sản phẩm này' : 'Sản phẩm sẽ bị ẩn khỏi website' }}
                    </div>
                </label>
            </div>
            <!-- Giá & Kho (khi KHÔNG có biến thể) -->
            @if (!$has_variations)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-lg font-semibold text-gray-200 mb-2">Giá bán (₫)</label>
                        <input type="number" wire:model="price"
                            class="w-full px-5 py-4 bg-gray-700 border border-gray-600 rounded-xl text-white focus:ring-4 focus:ring-yellow-500 focus:border-yellow-500 transition"
                            placeholder="0">
                    </div>
                    <div>
                        <label class="block text-lg font-semibold text-gray-200 mb-2">Số lượng tồn kho</label>
                        <input type="number" wire:model="stock"
                            class="w-full px-5 py-4 bg-gray-700 border border-gray-600 rounded-xl text-white focus:ring-4 focus:ring-yellow-500 focus:border-yellow-500 transition"
                            placeholder="0">
                    </div>
                </div>
            @endif

            <!-- Ảnh sản phẩm -->
            <div>
                <label class="block text-lg font-semibold text-gray-200 mb-3">Ảnh sản phẩm</label>
                <input type="file" wire:model="images" multiple
                    class="block w-full text-sm text-gray-400
                              file:mr-5 file:py-3 file:px-6 file:rounded-full file:border-0
                              file:text-sm file:font-bold file:bg-yellow-500 file:text-black
                              hover:file:bg-yellow-600 cursor-pointer">

                <textarea wire:model.live="imageUrls" rows="4"
                    class="mt-4 w-full px-5 py-4 bg-gray-700 border border-gray-600 rounded-xl text-white placeholder-gray-400 focus:ring-4 focus:ring-yellow-500 focus:border-yellow-500 transition resize-none"
                    placeholder="Dán link ảnh (mỗi dòng 1 link)..."></textarea>
            </div>

            <!-- Bảng biến thể -->
            @if ($has_variations)
                <div class="bg-gray-800/90 border border-gray-700 rounded-2xl p-8">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-bold text-yellow-400">Biến thể sản phẩm</h3>
                        <button type="button" wire:click="addVariation"
                            class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-6 rounded-xl transition shadow-lg">
                            + Thêm biến thể
                        </button>
                    </div>

                    <div class="overflow-x-auto rounded-xl border border-gray-700">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-700 text-gray-200">
                                <tr>
                                    <th class="px-5 py-4 text-left">Màu sắc</th>
                                    <th class="px-5 py-4 text-left">Size</th>
                                    <th class="px-5 py-4 text-left">Dây/Chất liệu</th>
                                    <th class="px-5 py-4 text-center">Giá</th>
                                    <th class="px-5 py-4 text-center">Kho</th>
                                    <th class="px-5 py-4 text-center">Ảnh riêng</th>
                                    <th class="px-5 py-4 text-center">Hành động</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700">
                                @forelse($variations as $index => $variation)
                                    <tr class="hover:bg-gray-700/50 transition">
                                        <td><input type="text" wire:model="variations.{{ $index }}.option1"
                                                class="w-full px-4 py-3 bg-gray-600 border border-gray-500 rounded-lg text-white focus:ring-2 focus:ring-yellow-500">
                                        </td>
                                        <td><input type="text" wire:model="variations.{{ $index }}.option2"
                                                class="w-full px-4 py-3 bg-gray-600 border border-gray-500 rounded-lg text-white focus:ring-2 focus:ring-yellow-500">
                                        </td>
                                        <td><input type="text" wire:model="variations.{{ $index }}.option3"
                                                class="w-full px-4 py-3 bg-gray-600 border border-gray-500 rounded-lg text-white focus:ring-2 focus:ring-yellow-500">
                                        </td>
                                        <td><input type="number" wire:model="variations.{{ $index }}.price"
                                                class="w-full px-4 py-3 bg-gray-600 border border-gray-500 rounded-lg text-white text-center focus:ring-2 focus:ring-yellow-500">
                                        </td>
                                        <td><input type="number" wire:model="variations.{{ $index }}.stock"
                                                class="w-full px-4 py-3 bg-gray-600 border border-gray-500 rounded-lg text-white text-center focus:ring-2 focus:ring-yellow-500">
                                        </td>
                                        <td class="text-center space-y-2">
                                            <input type="file"
                                                wire:model="variations.{{ $index }}.image_file"
                                                class="text-xs text-gray-400">
                                            <input type="text" wire:model="variations.{{ $index }}.image_url"
                                                class="w-full px-3 py-2 bg-gray-600 text-xs rounded border border-gray-500 text-white"
                                                placeholder="Hoặc dán link">
                                        </td>
                                        <td class="text-center">
                                            <button type="button" wire:click="removeVariation({{ $index }})"
                                                class="text-red-400 hover:text-red-300 font-bold text-lg">×</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-10 text-gray-500">
                                            Chưa có biến thể nào. Nhấn nút "+ Thêm biến thể" để bắt đầu.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Nút hành động -->
            <div class="flex gap-6 pt-8">
                <button type="submit"
                    class="bg-yellow-500 hover:bg-yellow-600 text-black font-bold text-xl py-4 px-12 rounded-xl shadow-lg transform hover:scale-105 transition">
                    {{ $product ? 'Cập nhật sản phẩm' : 'Tạo sản phẩm mới' }}
                </button>
                <a href="{{ route('admin.san-pham.index') }}"
                    class="bg-gray-700 hover:bg-gray-600 text-white font-bold text-xl py-4 px-12 rounded-xl transition">
                    Hủy bỏ
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('slug-updated', (slug) => {
                const el = document.getElementById('slug-preview');
                if (el) el.textContent = slug;
            });
        });
    </script>
@endpush
