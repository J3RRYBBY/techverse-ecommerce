@extends ('admin.home')

@section ('content')
    <div class="px-10 py-7">
        <div class="flex items-center justify-between pb-3">
            <div>
                <h1 class="text-2xl font-medium text-gray-800">Product List</h1>
                <p class="text-sm text-gray-500">Manage your products, variants and inventory.</p>
            </div>

            <a href="{{ route('product#createPage') }}">
                <button class="px-6 py-3 text-sm duration-200 rounded-full bg-lime-400 hover:bg-lime-500">
                    <i class="pr-5 text-sm fa-solid fa-plus"></i>Add Product
                </button>
            </a>
        </div>

        <div class="">
            <div class="min-h-screen py-2 bg-white rounded-lg">
                <div class="px-2">
                    <div
                        class="grid grid-cols-8 px-8 text-sm font-medium tracking-widest uppercase bg-gray-100 rounded-md text-black/70"
                    >
                        <div class="col-span-2 px-6 py-3">Product</div>
                        <div class="px-6 py-3">Category</div>
                        <div class="px-6 py-3">Variants</div>
                        <div class="px-6 py-3">Price</div>
                        <div class="px-6 py-3">Stock</div>
                        <div class="px-6 py-3">Created</div>
                        <div class="px-6 py-3">Action</div>
                    </div>

                    <div>
                        @if ($productCount != 0)
                            @foreach ($products as $product)
                                <div class="grid items-center grid-cols-8 px-8 text-sm font-medium border-b">
                                    <div class="col-span-2 px-6 py-3">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="flex items-center justify-center flex-shrink-0 w-12 h-12 overflow-hidden bg-gray-100 rounded-lg"
                                            >
                                                @php
                                                    $image = $product->variants->first()?->images->first()?->image;
                                                @endphp

                                                @if ($image)
                                                    <img
                                                        src="{{ $image }}"
                                                        alt="{{ $product->name }}"
                                                        class="object-cover w-full h-full"
                                                    />
                                                @else
                                                    <i class="text-lg text-gray-400 fa-solid fa-image"></i>
                                                @endif
                                            </div>
                                            <div class="min-w-0">
                                                <p class="font-medium text-gray-800 truncate max-w-[220px]">{{ $product->name }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="px-8 py-3">
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-gray-600 bg-gray-100 rounded-md"
                                        >
                                            {{ $product->category->name }}
                                        </span>
                                    </div>

                                    <div class="px-5 py-3">
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium text-blue-600 bg-blue-50 rounded-md"
                                        >
                                            <i class="text-[10px] fa-solid fa-layer-group"></i>
                                            {{ $product->variants->count() }} {{
                                                $product->variants->count() == 1
                                                    ? 'variant'
                                                    : 'variants'
                                            }}
                                        </span>
                                    </div>

                                    <div class="px-6 py-3">
                                        <div class="font-medium text-gray-800">
                                            @php
                                                $minPrice = $product->variants->min('price');
                                                $maxPrice = $product->variants->max('price');
                                            @endphp

                                            @if ($minPrice === $maxPrice)
                                                {{ number_format($minPrice) }}
                                            @else
                                                {{ number_format($minPrice) }} - {{ number_format($maxPrice) }}
                                            @endif

                                            <span class="text-xs font-normal">MMK</span>
                                        </div>
                                    </div>

                                    <div class="px-4 py-3">
                                        @php $stock = $product->variants->sum('stock'); @endphp
                                        @if ($stock > 0)
                                            <span
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium text-green-600 bg-green-50 rounded-md"
                                            >
                                                <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                                                {{ $stock }} in stock
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium text-red-600 bg-red-50 rounded-md"
                                            >
                                                <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span> Out of stock
                                            </span>
                                        @endif
                                    </div>

                                    <div class="px-6 py-3">
                                        <div class="text-sm text-gray-600">
                                            {{ $product->created_at->format('d M Y') }}
                                        </div>
                                    </div>
                                    <div class="px-6 py-3">
                                        <a
                                            href="{{ route('product#edit', $product->id) }}"
                                            class="inline-flex items-center justify-center text-gray-600 transition bg-gray-100 rounded-lg w-9 h-9 hover:bg-gray-200"
                                        >
                                            <x-feathericon-edit class="w-5 h-5" />
                                        </a>

                                        <button
                                            onclick="confirmDelete({{ $product->id }})"
                                            class="inline-flex items-center justify-center text-red-600 transition bg-red-100 rounded-lg w-9 h-9 hover:bg-red-200"
                                        >
                                            <x-heroicon-o-trash class="w-5 h-5" />
                                        </button>

                                        <form
                                            id="delete-form-{{ $product->id }}"
                                            action="{{ route('product#delete', $product->id) }}"
                                            method="POST"
                                        >
                                            @csrf
                                        </form>
                                    </div>
                                </div>

                            @endforeach
                        @else
                            <div class="px-6 py-16">
                                <div class="flex flex-col items-center justify-center text-center">
                                    <div
                                        class="flex items-center justify-center w-16 h-16 mb-4 bg-gray-100 rounded-full"
                                    >
                                        <i class="text-xl text-gray-400 fa-solid fa-box-open"></i>
                                    </div>
                                    <h3 class="font-medium text-gray-700">No products found</h3>
                                    <p class="mt-1 text-sm text-gray-400">Start by adding your first product.</p>
                                    <a
                                        href="{{ route('product#createPage') }}"
                                        class="inline-flex items-center gap-2 px-4 py-2 mt-5 text-sm font-medium transition rounded-lg bg-lime-400 hover:bg-lime-500"
                                    >
                                        <i class="text-xs fa-solid fa-plus"></i> Add Product
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: @json (session('success')),
                    confirmButtonColor: '#4f46e5',
                });
            });
        </script>
    @endif

    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'This product will be permanently deleted.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                confirmButtonColor: '#a3e635',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-form-${id}`).submit();
                }
            });
        }
    </script>
@endsection
