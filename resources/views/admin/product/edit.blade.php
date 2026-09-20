@extends ('admin.home')

@section ('content')
    <div class="px-10 py-7">
        <form action="{{ route('product#update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="flex items-center gap-4 pb-3">
                {{-- Back --}}
                <a
                    href="{{ route('product#list') }}"
                    class="flex items-center justify-center w-10 h-10 text-gray-600 transition bg-white border border-gray-200 rounded-lg hover:bg-gray-100"
                >
                    <i class="text-sm fa-solid fa-arrow-left"></i>
                </a>
                <div class="">
                    <h1 class="text-2xl font-medium text-gray-800">Edit Product</h1>
                </div>
            </div>

            <div class="flex justify-between gap-7">
                <div class="w-full p-5 overflow-visible bg-white rounded-lg shadow-sm">
                    <div>
                        <h1 class="mb-5 text-lg font-medium text-gray-800">Product Information</h1>

                        <p class="mt-4 text-sm">Name</p>

                        <input
                            type="text"
                            name="title"
                            value="{{ old('title', $product->name) }}"
                            placeholder="Enter product name"
                            class="w-full px-4 py-2.5 mt-2 text-sm rounded-lg shadow-sm border focus:outline-none focus:border-lime-400 focus:ring-1 focus:ring-lime-400 @error('title') outline outline-1 outline-red-500 @enderror"
                        />

                        @error ('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <p class="mt-4 text-sm">Description</p>

                        <textarea
                            name="description"
                            rows="8"
                            placeholder="Enter product description"
                            class="resize-none w-full px-4 py-2.5 mt-2 text-sm rounded-lg shadow-sm border focus:outline-none focus:border-lime-400 focus:ring-1 focus:ring-lime-400 @error('description') outline outline-1 outline-red-500 @enderror"
                            >{{ old('description', $product->description) }}</textarea
                        >

                        @error ('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <div class="relative inline-block w-full mt-4">
                            <input
                                type="hidden"
                                name="categoryId"
                                id="category_id"
                                value="{{ old('categoryId', $product->category_id) }}"
                            />

                            <p class="mb-1 text-sm">Category</p>

                            <button
                                type="button"
                                id="categoryButton"
                                class="inline-flex items-center justify-between w-full px-4 py-2.5 mt-2 text-sm rounded-lg shadow-sm border focus:outline-none focus:border-lime-400 focus:ring-1 focus:ring-lime-400 @error('categoryId') outline outline-1 outline-red-500 @enderror"
                            >
                                @php
                                    $selectedCategoryId = old('categoryId', $product->category_id);
                                    $selectedCategory = $categories->firstWhere('id', $selectedCategoryId);
                                @endphp

                                <span class="text-gray-700" id="selectedLabel">
                                    {{ $selectedCategory?->name ?? 'Select Category' }}
                                </span>

                                <svg
                                    class="w-5 h-5 ml-2 text-gray-400"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l1.414 1.414-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </button>

                            @error ('categoryId')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            <div
                                id="categoryMenu"
                                class="absolute left-0 z-10 hidden w-full overflow-y-auto bg-white border rounded-md shadow-sm max-h-64 top-20"
                            >
                                @foreach ($categories as $category)
                                    <button
                                        type="button"
                                        class="block w-full px-4 py-2.5 text-sm text-left hover:bg-indigo-50 category-item"
                                        data-id="{{ $category->id }}"
                                        data-name="{{ $category->name }}"
                                    >
                                        {{ $category->name }}
                                    </button>

                                @endforeach
                            </div>
                        </div>

                        {{-- Product Variants --}}
                        <div class="flex items-center justify-between mt-4">
                            <div>
                                <h2 class="text-lg font-medium text-gray-800">Product Variants</h2>

                                <p class="text-sm text-gray-500">Update storage, color, price, stock and images.</p>
                            </div>

                            <button
                                type="button"
                                id="addVariant"
                                class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium rounded-full bg-lime-400 text-gray-900 transition duration-200 hover:bg-lime-500"
                            >
                                <i class="text-sm fa-solid fa-plus"></i>
                                Add Variant
                            </button>
                        </div>

                        <div id="variants-container" class="mt-5 space-y-4">
                            @php
                                // Build variant data

                                // If validation failed, use old('variants').
                                // Otherwise use variants from database.

                                $variantData = old('variants');

                                if ($variantData === null) {
                                    $variantData = $product->variants
                                        ->map(function ($variant) {
                                            return [
                                                'id' => $variant->id,
                                                'capacity' => $variant->capacity,
                                                'color' => $variant->color,
                                                'price' => $variant->price,
                                                'stock' => $variant->stock,
                                                '_model' => $variant,
                                            ];
                                        })
                                        ->toArray();
                                }
                            @endphp

                            @foreach ($variantData as $index => $variantDataItem)
                                @php
                                    // Existing variant
                                    $variantId = $variantDataItem['id'] ?? null;

                                    $existingVariant = $variantId ? $product->variants->firstWhere('id', $variantId) : null;

                                    // Values
                                    $capacity = $variantDataItem['capacity'] ?? '';
                                    $color = $variantDataItem['color'] ?? '';
                                    $price = $variantDataItem['price'] ?? '';
                                    $stock = $variantDataItem['stock'] ?? '';
                                @endphp

                                <div
                                    class="p-4 bg-white border border-gray-200 rounded-xl variant-row"
                                    data-index="{{ $index }}"
                                >
                                    {{-- Existing Variant ID --}}
                                    @if ($existingVariant)
                                        <input
                                            type="hidden"
                                            name="variants[{{ $index }}][id]"
                                            value="{{ $existingVariant->id }}"
                                        />
                                    @endif

                                    {{-- Header --}}
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="flex items-center justify-center w-8 h-8 text-sm font-semibold text-gray-800 rounded-full bg-lime-400 variant-number"
                                            >
                                                {{ $index + 1 }}
                                            </div>

                                            <div>
                                                <p class="text-sm font-medium text-gray-800 variant-title">
                                                    Variant {{ $index + 1 }}
                                                </p>

                                                <p class="text-xs text-gray-500">Configure this product variation</p>
                                            </div>
                                        </div>

                                        <button
                                            type="button"
                                            class="flex items-center justify-center w-8 h-8 text-red-500 rounded-full remove-variant bg-red-50"
                                        >
                                            <i class="text-sm fa-solid fa-trash"></i>
                                        </button>
                                    </div>

                                    {{-- Fields --}}
                                    <div class="grid grid-cols-1 gap-5 mt-5 md:grid-cols-2">
                                        {{-- Capacity --}}
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700"> Capacity </label>

                                            <input
                                                type="text"
                                                name="variants[{{ $index }}][capacity]"
                                                value="{{ $capacity }}"
                                                placeholder="Enter capacity (eg. 128 GB)"
                                                class="w-full px-4 py-2.5 mt-2 text-sm border rounded-lg shadow-sm focus:outline-none focus:border-lime-400 focus:ring-1 focus:ring-lime-400 @error("variants.$index.capacity") outline outline-1 outline-red-500 @enderror"
                                            />

                                            @error ("variants.$index.capacity")
                                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        {{-- Color --}}
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700"> Color </label>

                                            <input
                                                type="text"
                                                name="variants[{{ $index }}][color]"
                                                value="{{ $color }}"
                                                placeholder="Enter color (eg. Black)"
                                                class="w-full px-4 py-2.5 mt-2 text-sm border rounded-lg shadow-sm focus:outline-none focus:border-lime-400 focus:ring-1 focus:ring-lime-400 @error("variants.$index.color") outline outline-1 outline-red-500 @enderror"
                                            />

                                            @error ("variants.$index.color")
                                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        {{-- Price --}}
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700"> Price </label>

                                            <input
                                                type="text"
                                                name="variants[{{ $index }}][price]"
                                                value="{{ $price }}"
                                                placeholder="Enter product price"
                                                class="w-full px-4 py-2.5 mt-2 text-sm border rounded-lg shadow-sm focus:outline-none focus:border-lime-400 focus:ring-1 focus:ring-lime-400 @error("variants.$index.price") outline outline-1 outline-red-500 @enderror"
                                            />

                                            @error ("variants.$index.price")
                                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        {{-- Stock --}}
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700"> Stock </label>

                                            <input
                                                type="text"
                                                name="variants[{{ $index }}][stock]"
                                                value="{{ $stock }}"
                                                placeholder="Enter stock quantity"
                                                class="w-full px-4 py-2.5 mt-2 text-sm border rounded-lg shadow-sm focus:outline-none focus:border-lime-400 focus:ring-1 focus:ring-lime-400 @error("variants.$index.stock") outline outline-1 outline-red-500 @enderror"
                                            />

                                            @error ("variants.$index.stock")
                                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Images --}}
                                    <div class="mt-5">
                                        <p class="text-sm font-medium text-gray-700">Product Images</p>

                                        <div
                                            class="flex items-center h-24 gap-3 mt-2 overflow-x-auto thumbnail-container"
                                        >
                                            {{-- Existing Images --}}
                                            @if ($existingVariant)
                                                @foreach ($existingVariant->images as $image)
                                                    <div
                                                        class="relative flex-shrink-0 w-20 h-20 existing-image"
                                                        data-image-id="{{ $image->id }}"
                                                    >
                                                        <img
                                                            src="{{ $image->image }}"
                                                            class="object-cover w-20 h-20 border rounded-lg"
                                                            alt="Product image"
                                                        />

                                                        <button
                                                            type="button"
                                                            class="absolute flex items-center justify-center w-5 h-5 rounded-full bg-lime-400 -top-2 -right-2 remove-existing-image"
                                                            data-image-id="{{ $image->id }}"
                                                        >
                                                            <i class="text-[8px] fa-solid fa-x"></i>
                                                        </button>
                                                    </div>

                                                @endforeach

                                            @endif

                                            {{-- Upload New Images --}}
                                            <label
                                                class="flex items-center justify-center flex-shrink-0 w-20 h-20 border-2 border-dashed rounded-lg cursor-pointer upload-box"
                                            >
                                                <i class="text-xl text-black/70 fa-solid fa-plus"></i>

                                                <input
                                                    type="file"
                                                    name="variants[{{ $index }}][images][]"
                                                    multiple
                                                    accept="image/*"
                                                    class="hidden image-input"
                                                />
                                            </label>
                                        </div>

                                        {{-- Deleted Existing Images --}}
                                        <div class="deleted-images"></div>

                                        {{-- Image Validation --}}
                                        @error ("variants.$index.images")
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror

                                        @error ("variants.$index.images.*")
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                            @endforeach
                        </div>
                    </div>

                    <div class="flex justify-end pt-3">
                        <button
                            type="submit"
                            class="px-6 py-3 text-sm duration-200 rounded-full bg-lime-400 hover:bg-lime-500"
                        >
                            <i class="pr-5 text-sm fa-solid fa-check"></i>
                            Update Product
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection
