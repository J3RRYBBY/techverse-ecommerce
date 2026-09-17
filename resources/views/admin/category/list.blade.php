@extends ('admin.home')

@section ('content')
    <div class="px-10 py-7">
        <div class="flex items-center justify-between pb-3">
            <div>
                <h1 class="text-2xl font-medium text-gray-800">Category List</h1>

                <p class="text-sm text-black/60">Manage your product categories.</p>
            </div>

            <button
                onclick="openModal('createCategoryModal')"
                class="px-6 py-3 text-sm duration-200 rounded-full bg-lime-400 hover:bg-lime-500"
            >
                <i class="pr-5 text-sm fa-solid fa-plus"></i>Add Category
            </button>

            <!-- Create Overlay -->
            <div
                id="createCategoryModal"
                class="fixed inset-0 z-50 items-start justify-center hidden pt-20 modal-overlay bg-black/50"
            >
                <!-- Modal -->
                <div class="w-full max-w-lg mx-4 overflow-hidden bg-white shadow-2xl rounded-2xl">
                    <!-- Header -->
                    <div class="flex items-center justify-between px-6 py-5 border-b">
                        <div>
                            <h2 class="text-lg font-medium text-gray-800">Add New Category</h2>
                            <p class="text-sm text-gray-500">Create a new product category.</p>
                        </div>

                        <button
                            onclick="closeModal('createCategoryModal')"
                            class="flex items-center justify-center transition rounded-lg w-9 h-9 hover:bg-gray-100"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="w-5 h-5 text-gray-500"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                        </button>
                    </div>

                    <!-- Body -->
                    <form action="{{ route('category#create') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="p-6 space-y-5">
                            <!-- Category Image -->
                            <div>
                                <label class="block mb-2 text-sm">Category Image</label>

                                <label
                                    class="relative flex flex-col items-center justify-center w-full overflow-hidden border rounded-lg cursor-pointer category-image-container h-60"
                                >
                                    <!-- Upload Placeholder -->
                                    <div class="flex flex-col items-center justify-center category-image-placeholder">
                                        <i class="text-3xl text-gray-400 fa-solid fa-image"></i>

                                        <p class="mt-2 text-sm text-gray-500">Click to upload category image</p>

                                        <p class="mt-1 text-xs text-gray-400">PNG, JPG, JPEG, WEBP, AVIF</p>
                                    </div>

                                    <!-- Image Preview -->
                                    <img
                                        src=""
                                        class="hidden object-cover w-full h-auto category-image-preview"
                                        alt="Category preview"
                                    />

                                    <input
                                        type="file"
                                        name="categoryImage"
                                        class="hidden category-image-input"
                                        accept="image/*"
                                    />
                                </label>

                                @error ('categoryImage', 'create')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Category Name -->
                            <div>
                                <label class="block mb-2 text-sm"> Category Name </label>

                                <input
                                    type="text"
                                    name="categoryName"
                                    value="{{ old('categoryName') }}"
                                    placeholder="Enter category name"
                                    class="px-4 py-2.5 text-sm rounded-lg w-full border focus:outline-none focus:border-lime-400 shadow-sm focus:ring-1 focus:ring-lime-400 @error('categoryName') border-none outline outline-1 outline-red-500 @enderror"
                                />

                                @error ('categoryName', 'create')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="flex justify-end gap-3 px-6 py-5 text-sm border-t">
                            <button
                                type="button"
                                onclick="closeModal('createCategoryModal')"
                                class="px-6 py-3 transition border border-gray-200 rounded-full hover:bg-gray-300"
                            >
                                Cancel
                            </button>

                            <button
                                type="submit"
                                class="px-6 py-3 text-black transition rounded-full bg-lime-400 hover:bg-lime-500"
                            >
                                Add Category
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="relative overflow-x-auto">
            <div class="min-h-screen py-2 bg-white rounded-lg">
                <div class="px-2">
                    <div
                        class="grid grid-cols-3 px-8 text-sm font-medium tracking-widest uppercase bg-gray-100 rounded-md text-black/70"
                    >
                        <div class="px-6 py-3">Category</div>
                        <div class="px-6 py-3">Created</div>
                        <div class="px-6 py-3">Action</div>
                    </div>
                    <div>
                        @if ($categoryCount != 0)
                            @foreach ($categories as $item)
                                <div class="grid items-center grid-cols-3 px-8 text-sm font-medium border-b">
                                    <div class="flex items-center gap-4 px-6 py-3">
                                        {{-- Category Image --}}
                                        <div
                                            class="flex items-center justify-center flex-shrink-0 w-12 h-12 overflow-hidden bg-gray-100 rounded-lg"
                                        >
                                            @if ($item->image)
                                                <img
                                                    src="{{ $item->image}}"
                                                    alt="{{ $item->name }}"
                                                    class="object-cover w-full h-full"
                                                />
                                            @else
                                                <i class="text-lg text-gray-400 fa-solid fa-image"></i>
                                            @endif
                                        </div>
                                        {{-- Category Name --}}
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $item->name }}</p>
                                        </div>
                                    </div>
                                    <div class="px-6 py-3">
                                        <div class="text-sm text-gray-600">
                                            {{ $item->created_at->format('d M Y') }}
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 px-6 py-3">
                                        <button
                                            type="button"
                                            onclick="openEditModal(
                                                '{{ $item->name }}',
                                                '{{ route('category#update', $item->id) }}',
                                                '{{ $item->image }}'
                                            )"
                                            class="inline-flex items-center justify-center text-gray-600 transition bg-gray-100 rounded-lg w-9 h-9 hover:bg-gray-200"
                                        >
                                            <x-feathericon-edit class="w-5 h-5" />
                                        </button>

                                        <button
                                            onclick="confirmDelete({{ $item->id }})"
                                            class="inline-flex items-center justify-center text-red-600 transition bg-red-100 rounded-lg w-9 h-9 hover:bg-red-200"
                                        >
                                            <x-heroicon-o-trash class="w-5 h-5" />
                                        </button>

                                        <form
                                            id="delete-form-{{ $item->id }}"
                                            action="{{ route('category#delete', $item->id) }}"
                                            method="POST"
                                        >
                                            @csrf
                                        </form>
                                    </div>
                                </div>
                            @endforeach

                            <!-- Edit Overlay -->
                            <div
                                id="editCategoryModal"
                                class="fixed inset-0 z-50 items-center justify-center hidden modal-overlay bg-black/50 backdrop-blur-sm"
                            >
                                <!-- Modal -->
                                <div class="w-full max-w-lg mx-4 overflow-hidden bg-white shadow-2xl rounded-2xl">
                                    <!-- Header -->
                                    <div class="flex items-center justify-between px-6 py-5 border-b">
                                        <div>
                                            <h2 class="text-lg font-medium text-gray-800">Edit Category</h2>
                                            <p class="text-sm text-gray-500">Update category information.</p>
                                        </div>

                                        <button
                                            onclick="closeModal('editCategoryModal')"
                                            class="flex items-center justify-center transition rounded-lg w-9 h-9 hover:bg-gray-100"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="w-5 h-5 text-gray-500"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"
                                                />
                                            </svg>
                                        </button>
                                    </div>

                                    <!-- Body -->
                                    <form id="editForm" method="POST" enctype="multipart/form-data">
                                        @csrf

                                        <div class="p-6 space-y-5">
                                            <!-- Category Image -->
                                            <div>
                                                <label class="block mb-2 text-sm">Category Image</label>

                                                <label
                                                    class="relative flex items-center justify-center w-full h-40 overflow-hidden border rounded-lg cursor-pointer category-image-container"
                                                >
                                                    <!-- Placeholder -->
                                                    <div
                                                        class="flex flex-col items-center justify-center category-image-placeholder"
                                                    >
                                                        <i class="text-3xl text-gray-400 fa-solid fa-image"></i>

                                                        <p class="mt-2 text-sm text-gray-500">Click to change image</p>

                                                        <p class="mt-1 text-xs text-gray-400">PNG, JPG, JPEG, WEBP, AVIF</p>
                                                    </div>

                                                    <!-- Preview -->
                                                    <img
                                                        src=""
                                                        class="hidden object-cover w-full h-auto category-image-preview"
                                                        alt="Category preview"
                                                    />

                                                    <input
                                                        type="file"
                                                        name="categoryImage"
                                                        class="hidden category-image-input"
                                                        accept="image/*"
                                                    />
                                                </label>

                                                @error ('categoryImage', 'edit')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <!-- Category Name -->
                                            <div>
                                                <label class="block mb-2 text-sm"> Category Name </label>

                                                <input
                                                    type="text"
                                                    id="editCategoryName"
                                                    name="categoryName"
                                                    placeholder="Enter category name"
                                                    value="{{ old('categoryName') }}"
                                                    class="px-4 py-2.5 text-sm rounded-lg w-full border focus:outline-none focus:border-lime-400 shadow-sm focus:ring-1 focus:ring-lime-400 @error('categoryName') border-none outline outline-1 outline-red-500 @enderror"
                                                />

                                                @error ('categoryName', 'edit')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Footer -->
                                        <div class="flex justify-end gap-3 px-6 py-5 text-sm border-t">
                                            <button
                                                type="button"
                                                onclick="closeModal('editCategoryModal')"
                                                class="px-6 py-3 transition border border-gray-200 rounded-full hover:bg-gray-300"
                                            >
                                                Cancel
                                            </button>

                                            <button
                                                type="submit"
                                                class="px-6 py-3 text-black transition rounded-full bg-lime-400 hover:bg-lime-500"
                                            >
                                                Update Category
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @else
                            <div class="px-6 py-16">
                                <div class="flex flex-col items-center justify-center text-center">
                                    <div
                                        class="flex items-center justify-center w-16 h-16 mb-4 bg-gray-100 rounded-full"
                                    >
                                        <i class="text-xl text-gray-400 fa-solid fa-folder-open"></i>
                                    </div>

                                    <h3 class="font-medium text-gray-700">No categories found</h3>

                                    <p class="mt-1 text-sm text-gray-400">Start by adding your first category.</p>

                                    <button
                                        type="button"
                                        onclick="openModal('createCategoryModal')"
                                        class="inline-flex items-center gap-2 px-4 py-2 mt-5 text-sm font-medium transition rounded-lg bg-lime-400 hover:bg-lime-500"
                                    >
                                        <i class="text-xs fa-solid fa-plus"></i>
                                        Add Category
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (session('open_modal'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                openModal(@json (session('open_modal')));
            });
        </script>
    @endif

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

    @if (session('deleteError'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Cannot Delete',
                    text: @json (session('deleteError')),
                });
            });
        </script>
    @endif

    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'This category will be permanently deleted.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-form-${id}`).submit();
                }
            });
        }
    </script>
@endsection
