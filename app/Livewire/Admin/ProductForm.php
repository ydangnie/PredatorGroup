<?php

namespace App\Livewire\Admin;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class ProductForm extends Component
{
    use WithFileUploads;

    public ?Product $product = null;

    public $name = '';
    public $description = '';
    public $content = '';
    public $category_id = '';
    public $brand_id = '';
    public $price = null;
    public $stock = 0;
    public $images = [];
    public $imageUrls = '';
    public $has_variations = false;
    public $is_active = true;

    public $variations = [];

    protected $rules = [
        'name' => 'required|min:3|max:255',
        'category_id' => 'required|exists:categories,id',
        'brand_id' => 'required|exists:brands,id',
        'images.*' => 'image|max:5120',
        'variations.*.option1' => 'required_if:has_variations,true|string',
        'variations.*.option2' => 'required_if:has_variations,true|string',
        'variations.*.option3' => 'required_if:has_variations,true|string',
        'variations.*.price' => 'required_if:has_variations,true|numeric|min:0',
        'variations.*.stock' => 'required_if:has_variations,true|integer|min:0',
    ];

    public function mount()
    {
        if ($this->product) {
            $product = $this->product;
            $this->name = $product->name;
            $this->description = $product->description ?? '';
            $this->content = $product->content ?? '';
            $this->category_id = $product->category_id;
            $this->brand_id = $product->brand_id;
            $this->price = $product->price;
            $this->stock = $product->stock;
            $this->has_variations = $product->has_variations;
            $this->imageUrls = is_array($product->images) ? implode("\n", $product->images) : '';
            $this->is_active = $product->is_active;
            foreach ($product->variations as $v) {
                $this->variations[] = [
                    'id' => $v->id,
                    'option1' => $v->option1,
                    'option2' => $v->option2,
                    'option3' => $v->option3,
                    'price' => $v->price,
                    'stock' => $v->stock,
                    'image_file' => null,
                    'image_url' => $v->image ?? '',
                ];
            }
        }
    }

    public function updatedName($value)
    {
        $this->dispatch('slug-updated', Str::slug($value));
    }

    public function addVariation()
    {
        $this->variations[] = [
            'option1' => '',
            'option2' => '',
            'option3' => '',
            'price' => 0,
            'stock' => 0,
            'image_file' => null,
            'image_url' => ''
        ];
    }

    // HÀM XÓA BIẾN THỂ ĐÃ ĐƯỢC BỔ SUNG + XÓA ẢNH NẾU CÓ
    public function removeVariation($index)
    {
        $variation = $this->variations[$index] ?? null;

        // Nếu là biến thể cũ (có ID) → xóa trong DB luôn
        if ($variation && !empty($variation['id'])) {
            $dbVariation = ProductVariation::find($variation['id']);
            if ($dbVariation) {
                // Xóa ảnh nếu có
                if ($dbVariation->image && str_starts_with($dbVariation->image, 'products/')) {
                    Storage::disk('public')->delete($dbVariation->image);
                }
                $dbVariation->delete();
            }
        }

        // Xóa khỏi mảng Livewire
        unset($this->variations[$index]);
        $this->variations = array_values($this->variations);

        $this->dispatch('toast', ['message' => 'Đã xóa biến thể!', 'type' => 'success']);
    }

    public function save()
    {
        $this->validate();

        // Xử lý ảnh
        $allImages = [];
        foreach ($this->images as $img) {
            $allImages[] = $img->store('products', 'public');
        }
        if ($this->imageUrls) {
            $urls = array_filter(array_map('trim', explode("\n", $this->imageUrls)));
            $allImages = array_merge($allImages, $urls);
        }

        // Tạo slug unique
        $baseSlug = Str::slug($this->name);
        $slug = $baseSlug;
        $count = 1;
        while (Product::where('slug', $slug)->where('id', '!=', $this->product->id)->exists()) {
            $slug = $baseSlug . '-' . $count++;
        }

        // QUAN TRỌNG: Dùng $this->product để updateOrCreate chính xác
        $product = Product::updateOrCreate(
            ['id' => $this->product->id],
            [
                'name'           => $this->name,
                'slug'           => $slug,
                'description'    => $this->description,
                'content'        => $this->content,
                'category_id'    => $this->category_id,
                'brand_id'       => $this->brand_id,
                'price'          => $this->has_variations ? null : $this->price,
                'stock'          => $this->has_variations ? 0 : $this->stock,
                'images'         => $allImages,
                'has_variations' => $this->has_variations,
                'created_by'     => Auth::id(),
                'is_active'      => $this->is_active
            ]
        );

        if ($this->has_variations) {
            $existingIds = [];

            foreach ($this->variations as $item) {
                $imagePath = null;
                if (!empty($item['image_file']) && $item['image_file']) {
                    $imagePath = $item['image_file']->store('products', 'public');
                } elseif (!empty($item['image_url'])) {
                    $imagePath = trim($item['image_url']);
                }

                $data = [
                    'product_id' => $product->id,
                    'option1'    => $item['option1'] ?? null,
                    'option2'    => $item['option2'] ?? null,
                    'option3'    => $item['option3'] ?? null,
                    'price'      => $item['price'],
                    'stock'      => $item['stock'],
                    'image'      => $imagePath,
                    'sku'        => 'SP' . str_pad($product->id, 4, '0', STR_PAD_LEFT)
                        . '-' . strtoupper(substr($item['option1'] ?? 'X', 0, 3))
                        . '-' . ($item['option2'] ?? 'XX')
                        . '-' . strtoupper(substr($item['option3'] ?? 'X', 0, 2)),
                ];

                if (!empty($item['id'])) {
                    ProductVariation::where('id', $item['id'])->update($data);
                    $existingIds[] = $item['id'];
                } else {
                    $variation = ProductVariation::create($data);
                    $existingIds[] = $variation->id;
                }
            }
        }

        session()->flash('message', $this->product ? 'Cập nhật sản phẩm thành công!' : 'Tạo sản phẩm thành công!');
        return redirect()->route('admin.san-pham.index');
    }

    public function render()
    {
        return view('livewire.admin.product-form', [
            'categories' => Category::all(),
            'brands' => Brand::all(),
        ]);
    }
}
