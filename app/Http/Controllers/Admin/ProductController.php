<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    // Product list
    public function list()
    {
        $products = Product::with('category')->latest()->get();

        $productCount = $products->count();

        return view('admin.product.list', compact('products', 'productCount'));
    }

    // Product create page
    public function createPage()
    {
        $categories = Category::query()
            ->select(['id', 'name'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.product.create', compact('categories'));
    }

    // Product create
    public function create(Request $request, CloudinaryService $cloudinary)
    {
        $this->validateProductCreate($request);

        DB::transaction(function () use ($request, $cloudinary) {
            $product = Product::create([
                'name' => $request->title,
                'description' => $request->description,
                'category_id' => $request->categoryId,
            ]);

            foreach ($request->variants as $variantData) {
                $variant = ProductVariant::create([
                    'product_id' => $product->id,
                    'capacity' => $variantData['capacity'],
                    'color' => $variantData['color'],
                    'price' => $variantData['price'],
                    'stock' => $variantData['stock'],
                ]);

                $this->storeVariantImages($variant, $variantData['images'] ?? [], $cloudinary);
            }
        });

        return redirect()->route('product#list')->with('success', 'Product created successfully!');
    }

    // Product edit page
    public function edit(int $id)
    {
        $product = Product::with('variants.images')->findOrFail($id);

        $categories = Category::orderBy('created_at', 'desc')->get();

        return view('admin.product.edit', compact('product', 'categories'));
    }

    // Product update
    public function update(Request $request, int $id, CloudinaryService $cloudinary)
    {
        $this->validateProductUpdate($request);

        $product = Product::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Update product
        |--------------------------------------------------------------------------
        */

        $product->update([
            'name' => $request->title,
            'description' => $request->description,
            'category_id' => $request->categoryId,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Update variants
        |--------------------------------------------------------------------------
        */

        foreach ($request->variants as $index => $variantData) {
            /*
            |--------------------------------------------------------------------------
            | Existing variant
            |--------------------------------------------------------------------------
            */

            if (!empty($variantData['id'])) {
                $variant = ProductVariant::query()
                    ->where('id', $variantData['id'])
                    ->where('product_id', $product->id)
                    ->firstOrFail();

                $variant->update([
                    'capacity' => $variantData['capacity'],
                    'color' => $variantData['color'],
                    'price' => $variantData['price'],
                    'stock' => $variantData['stock'],
                ]);
            }
            /*
            |--------------------------------------------------------------------------
            | New variant
            |--------------------------------------------------------------------------
            */ else {
                $variant = $product->variants()->create([
                    'capacity' => $variantData['capacity'],
                    'color' => $variantData['color'],
                    'price' => $variantData['price'],
                    'stock' => $variantData['stock'],
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Delete selected images from Cloudinary
            |--------------------------------------------------------------------------
            */

            $deleteImages = $variantData['deleteImages'] ?? [];

            if (!is_array($deleteImages)) {
                $deleteImages = [$deleteImages];
            }

            foreach ($deleteImages as $imageId) {
                $oldImage = ProductImage::query()
                    ->where('id', $imageId)
                    ->where('product_variant_id', $variant->id)
                    ->first();

                if (!$oldImage) {
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Delete image from Cloudinary
                |--------------------------------------------------------------------------
                */

                if ($oldImage->image_public_id) {
                    $cloudinary->delete($oldImage->image_public_id);
                }

                /*
                |--------------------------------------------------------------------------
                | Delete database record
                |--------------------------------------------------------------------------
                */

                $oldImage->delete();
            }

            /*
            |--------------------------------------------------------------------------
            | Upload new images to Cloudinary
            |--------------------------------------------------------------------------
            */

            $files = $request->file("variants.{$index}.images", []);

            foreach ($files as $file) {
                if (!$file->isValid()) {
                    continue;
                }

                $uploaded = $cloudinary->upload($file, 'techverse/products');

                $variant->images()->create([
                    'image' => $uploaded['url'],
                    'image_public_id' => $uploaded['public_id'],
                ]);
            }
        }

        return to_route('product#list')->with('success', 'Product Updated Successfully!');
    }

    // Product delete
    public function delete(int $id, CloudinaryService $cloudinary)
    {
        $product = Product::query()->with('variants.images')->findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Delete variants and images
        |--------------------------------------------------------------------------
        */

        foreach ($product->variants as $variant) {
            foreach ($variant->images as $image) {
                /*
                |--------------------------------------------------------------------------
                | Delete image from Cloudinary
                |--------------------------------------------------------------------------
                */

                if ($image->image_public_id) {
                    $cloudinary->delete($image->image_public_id);
                }

                /*
                |--------------------------------------------------------------------------
                | Delete image record
                |--------------------------------------------------------------------------
                */

                $image->delete();
            }

            /*
            |--------------------------------------------------------------------------
            | Delete variant
            |--------------------------------------------------------------------------
            */

            $variant->delete();
        }

        /*
        |--------------------------------------------------------------------------
        | Delete product
        |--------------------------------------------------------------------------
        */

        $product->delete();

        return to_route('product#list')->with('success', 'Product deleted successfully!');
    }

    // Validation for product create
    private function validateProductCreate(Request $request): void
    {
        $request->validate(
            [
                'title' => ['required', 'string', 'max:255'],

                'description' => ['required', 'string'],

                'categoryId' => ['required', 'exists:categories,id'],

                'variants' => ['required', 'array'],

                'variants.*.capacity' => ['required', 'string'],

                'variants.*.color' => ['required', 'string'],

                'variants.*.price' => ['required', 'numeric', 'min:0'],

                'variants.*.stock' => ['required', 'integer', 'min:0'],

                'variants.*.images' => ['required', 'array', 'min:2'],

                'variants.*.images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:30720'],
            ],
            [
                'variants.*.capacity.required' => 'Capacity is required.',

                'variants.*.color.required' => 'Color is required.',

                'variants.*.price.required' => 'Price is required.',

                'variants.*.stock.required' => 'Stock is required.',

                'variants.*.images.required' => 'At least two image is required.',

                'variants.*.images.*.image' => 'Image Only (jpg,jpeg,png,webp)',
            ],
        );
    }

    // Validation for product update
    private function validateProductUpdate(Request $request): void
    {
        $request->validate(
            [
                'title' => ['required', 'string', 'max:255'],

                'description' => ['required', 'string'],

                'categoryId' => ['required', 'exists:categories,id'],

                'variants' => ['required', 'array', 'min:1'],

                'variants.*.id' => ['nullable', 'integer', 'exists:product_variants,id'],

                'variants.*.capacity' => ['required', 'string'],

                'variants.*.color' => ['required', 'string'],

                'variants.*.price' => ['required', 'numeric', 'min:0'],

                'variants.*.stock' => ['required', 'integer', 'min:0'],

                'variants.*.images' => ['nullable', 'array'],

                'variants.*.images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:30720'],

                'variants.*.deleteImages' => ['nullable', 'array'],

                'variants.*.deleteImages.*' => ['integer', 'exists:product_images,id'],
            ],
            [
                'variants.*.capacity.required' => 'Capacity is required.',

                'variants.*.color.required' => 'Color is required.',

                'variants.*.price.required' => 'Price is required.',

                'variants.*.stock.required' => 'Stock is required.',
            ],
        );
    }

    // Store variant images in Cloudinary
    private function storeVariantImages(ProductVariant $variant, array $images, CloudinaryService $cloudinary): void
    {
        foreach ($images as $image) {
            if (!$image->isValid()) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Upload to Cloudinary
            |--------------------------------------------------------------------------
            */

            $uploaded = $cloudinary->upload($image, 'techverse/products');

            /*
            |--------------------------------------------------------------------------
            | Save Cloudinary information
            |--------------------------------------------------------------------------
            */

            ProductImage::create([
                'product_variant_id' => $variant->id,
                'image' => $uploaded['url'],
                'image_public_id' => $uploaded['public_id'],
            ]);
        }
    }
}
