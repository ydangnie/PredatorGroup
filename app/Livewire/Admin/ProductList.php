<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Livewire\Component;
use Livewire\WithPagination;

class ProductList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $category_id = '';
    public $brand_id = '';
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'category_id' => ['except' => ''],
        'brand_id' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoryId()
    {
        $this->resetPage();
    }

    public function updatingBrandId()
    {
        $this->resetPage();
    }
    // Thêm method toggleActive
    public function toggleActive($productId)
    {
        $product = Product::findOrFail($productId);
        $product->is_active = !$product->is_active;
        $product->save();

        $this->dispatch('toast', [
            'message' => $product->is_active ? 'Đã bật sản phẩm!' : 'Đã tắt sản phẩm!',
            'type' => $product->is_active ? 'success' : 'warning'
        ]);
    }
    public function render()
    {
        $products = Product::with(['category', 'brand', 'variations'])
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->when($this->category_id, fn($q) => $q->where('category_id', $this->category_id))
            ->when($this->brand_id, fn($q) => $q->where('brand_id', $this->brand_id))
            ->latest()
            ->paginate($this->perPage)
            ->withQueryString();
        return view('livewire.admin.product-list', [
            'products' => $products,
            'categories' => Category::where('is_active', true)->get(),
            'brands' => Brand::where('is_active', true)->get(),
        ]);
    }

    public function delete($id)
    {
        $product = Product::findOrFail($id);
        // Xóa ảnh trong storage nếu là file (tùy chọn)
        // foreach ($product->images as $img) { if (!filter_var($img, FILTER_VALIDATE_URL)) Storage::disk('public')->delete($img); }
        $product->delete();
        $this->dispatch('toast', 'Xóa sản phẩm thành công!', 'success');
    }
}
