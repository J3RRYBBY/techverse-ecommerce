@extends ('layouts.admin.app')

@section ('content')
    <div class="px-10 py-7">
        {{-- Header --}}
        <div class="pb-3">
            <h1 class="text-2xl font-medium text-gray-800">Contact Messages</h1>

            <p class="text-sm text-black/60">View messages sent by customers.</p>
        </div>

        {{-- Contact Message Table --}}
        <div class="">
            <div class="min-h-screen py-2 bg-white rounded-lg">
                <div class="px-2">
                    <div
                        class="grid grid-cols-6 px-8 text-sm font-medium tracking-widest uppercase bg-gray-100 rounded-md text-black/70"
                    >
                        <div class="px-6 py-3">Name</div>

                        <div class="px-6 py-3">Email</div>

                        <div class="px-6 py-3">Subject</div>

                        <div class="px-6 py-3">Status</div>

                        <div class="px-6 py-3">Date</div>

                        <div class="px-6 py-3">Action</div>
                    </div>

                    <div class="">
                        @forelse ($contacts as $contact)
                            <div class="grid items-center grid-cols-6 px-8 text-sm font-medium border-b">
                                {{-- Name --}}
                                <div class="px-6 py-3">
                                    <p class="font-medium text-gray-900">{{ $contact->name }}</p>
                                </div>

                                {{-- Email --}}
                                <div class="px-6 py-3 text-sm text-gray-500">{{ $contact->email }}</div>

                                {{-- Subject --}}
                                <div class="px-6 py-3">
                                    <span class="text-sm text-gray-700"> {{ $contact->subject ?? 'No Subject' }} </span>
                                </div>

                                {{-- Status --}}
                                <div class="px-6 py-3">
                                    @if ($contact->status === 'unread')
                                        <span
                                            class="px-3 py-1 text-xs font-medium text-yellow-700 bg-yellow-100 rounded-full"
                                        >
                                            Unread
                                        </span>

                                    @else
                                        <span
                                            class="px-3 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full"
                                        >
                                            Read
                                        </span>

                                    @endif
                                </div>

                                {{-- Date --}}
                                <div class="px-6 py-3 text-sm text-gray-500">
                                    {{ $contact->created_at->format('d M Y') }}
                                </div>

                                {{-- Actions --}}
                                <div class="px-6 py-3">
                                    <div class="flex items-center gap-2">
                                        {{-- View --}}
                                        <a
                                            href="{{ route('admin#contactDetails', $contact->id) }}"
                                            class="inline-flex items-center justify-center text-blue-600 transition bg-blue-100 rounded-lg w-9 h-9 hover:bg-blue-200"
                                        >
                                            <x-heroicon-o-eye class="w-5 h-5" />
                                        </a>

                                        {{-- Delete --}}
                                        <form
                                            action="{{ route('admin#deleteCustomerMessage', $contact->id) }}"
                                            method="POST"
                                            class="delete-contact-form"
                                        >
                                            @csrf
                                            @method ('DELETE')

                                            <button
                                                type="submit"
                                                class="inline-flex items-center justify-center text-red-600 transition bg-red-100 rounded-lg w-9 h-9 hover:bg-red-200"
                                            >
                                                <x-heroicon-o-trash class="w-5 h-5" />
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        @empty
                            <div>
                                <div colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    <i class="mb-3 text-3xl fa-regular fa-envelope"></i>

                                    <p>No contact messages found.</p>
                                </div>
                            </div>

                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Pagination --}}
            @if ($contacts->hasPages())
                <div class="px-6 py-3 border-t">{{ $contacts->links() }}</div>

            @endif
        </div>
    </div>

    {{-- Delete Success --}}
    @if (session('deleteSuccess'))
        <script>
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: @json (session('deleteSuccess')),
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
        </script>

    @endif

    {{-- Delete Confirmation --}}
    <script>
        document.querySelectorAll('.delete-contact-form').forEach((form) => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Delete Message?',
                    text: 'This contact message will be permanently deleted.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, Delete',
                    cancelButtonText: 'Cancel',
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>

@endsection
