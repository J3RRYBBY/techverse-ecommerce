@extends ('layouts.admin.app')

@section ('content')
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
                            <div class="grid items-center grid-cols-3 px-8 text-sm font-medium border-b">
                                <div class="px-6 py-3">{{ $admin->name }}</div>
                                <div class="px-6 py-3">{{ $admin->role }}</div>
                                <div class="px-6 py-3">
                                    @if (Auth::user()->role === 'superadmin')
                                        <a
                                            href="{{ route('admin#editAdmin', $admin->id) }}"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-gray-700 transition bg-gray-100 rounded-lg hover:bg-lime-100 hover:text-gray-800"
                                        >
                                            <x-feathericon-edit class="w-5 h-5" />
                                        </a>
                                    @endif

                                    @if ($admin->role !== 'superadmin')
                                        <button
                                            onclick="confirmDelete({{ $admin->id }})"
                                            class="inline-flex items-center justify-center text-red-600 transition bg-red-100 rounded-lg w-9 h-9 hover:bg-red-200"
                                        >
                                            <x-heroicon-o-trash class="w-5 h-5" />
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
                    confirmButtonColor: '#a3e635',
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
