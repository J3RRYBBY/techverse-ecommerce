@extends ('layouts.admin.app')

@section ('content')
    {{-- <h1 class="text-2xl font-bold">Admin Management</h1>

    <a href="" class="px-4 py-2 text-white rounded bg-lime-500"> Add Admin </a>

    @foreach ($admins as $admin)
        <div class="flex items-center justify-between p-4 mt-3 bg-white rounded shadow">
            <div>
                <h2 class="font-semibold">{{ $admin->name }}</h2>

                <p class="text-gray-500">{{ $admin->email }}</p>
            </div>

            <div class="flex gap-2">
                <a href="" class="px-3 py-2 text-white bg-blue-500 rounded"> Edit </a>

                <form action="" method="POST">
                    @csrf
                    @method ('DELETE')

                    <button type="submit" class="px-3 py-2 text-white bg-red-500 rounded">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    @endforeach --}}

    <div class="px-10 py-7">
        <div class="flex items-center justify-between pb-3">
            <h1 class="text-2xl font-medium text-gray-800">Admin List</h1>

            <a
                href="{{ route('admin#addAdminPage') }}"
                class="px-6 py-3 text-sm duration-200 rounded-full bg-lime-400 hover:bg-lime-500"
            >
                <i class="pr-5 text-sm fa-solid fa-plus"></i>Add Admin
            </a>
        </div>

        <div class="py-2 bg-white rounded-lg">
            <div class="px-2">
                <div
                    class="grid grid-cols-3 px-8 text-sm font-medium tracking-widest uppercase bg-gray-100 rounded-md text-black/70"
                >
                    <div class="px-6 py-3">Name</div>
                    <div class="px-6 py-3">Role</div>
                    <div class="px-6 py-3">Action</div>
                </div>
                <div>
                    @if ($adminCount != 0)
                        @foreach ($admins as $admin)
                            <div
                                class="grid items-center grid-cols-3 px-8 text-sm font-medium border-b"
                            >
                                <div class="px-6 py-3">{{ $admin->name }}</div>
                                <div class="px-6 py-3">{{ $admin->role }}</div>
                                <div class="px-6 py-3">
                                    <button
                                        {{-- onclick="openEditModal('{{ $item->name }}', '{{ route('category#update', $item->id) }}')" --}}
                                        class="text-lg text-blue-600"
                                    >
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </button>

                                    @if ($admin->role !== 'superadmin')
                                        <button
                                            onclick="confirmDelete({{ $admin->id }})"
                                            class="text-lg text-red-600"
                                        >
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>
                                    @endif

                                    <form
                                        id="delete-form-{{ $admin->id }}"
                                        action="{{ route('admin#deleteAdmin', $admin->id) }}"
                                        method="POST"
                                    >
                                        @csrf
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="border-b text-black/70">
                            <p class="px-6 py-3 text-center">There is no data!</p>
                        </div>
                    @endif
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
                text: 'This Admin account will be permanently deleted.',
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
