@extends ('layouts.admin.app')

@section ('content')
    <div class="px-10 py-7">
        {{-- Header --}}
        <div class="flex flex-col justify-between max-w-5xl gap-4 mb-6 sm:flex-row sm:items-center">
            <div class="flex items-center gap-4">
                <a
                    href="{{ route('admin#customerMessages') }}"
                    class="flex items-center justify-center w-10 h-10 text-gray-600 transition bg-white border border-gray-200 rounded-lg hover:bg-gray-100"
                >
                    <i class="text-sm fa-solid fa-arrow-left"></i>
                </a>

                <div>
                    <h1 class="text-2xl font-medium text-gray-800">Customer Message</h1>

                    <p class="text-sm text-black/60">View customer message details</p>
                </div>
            </div>

            {{-- Delete Button --}}
            <form
                action="{{ route('admin#deleteCustomerMessage', $contact->id) }}"
                method="POST"
                id="deleteContactForm"
            >
                @csrf
                @method ('DELETE')

                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-red-600 transition bg-white border border-red-200 rounded-lg hover:bg-red-50"
                >
                    <i class="fa-regular fa-trash-can"></i>
                    Delete Message
                </button>
            </form>
        </div>

        {{-- Main Card --}}
        <div class="max-w-5xl overflow-hidden bg-white border border-gray-100 shadow-sm rounded-2xl">
            {{-- Card Header --}}
            <div class="px-6 py-5 border-b border-gray-100 sm:px-8">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center text-indigo-600 rounded-lg w-11 h-11 bg-indigo-50">
                        <i class="text-lg fa-regular fa-envelope"></i>
                    </div>

                    <div>
                        <h2 class="font-medium text-gray-800">Message Details</h2>

                        <p class="text-sm text-black/60">Customer contact information</p>
                    </div>
                </div>
            </div>

            {{-- Sender Information --}}
            <div class="px-6 py-6 sm:px-8">
                <div class="grid gap-5 md:grid-cols-2">
                    {{-- Name --}}
                    <div class="p-4 border border-gray-100 rounded-lg bg-gray-50/70">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex items-center justify-center text-gray-500 bg-white border border-gray-100 rounded-lg w-9 h-9"
                            >
                                <i class="text-sm fa-regular fa-user"></i>
                            </div>

                            <div class="min-w-0">
                                <p class="text-xs font-medium tracking-wide text-gray-400 uppercase">Name</p>

                                <p class="mt-1 font-medium text-gray-900 truncate">{{ $contact->name }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="p-4 border border-gray-100 rounded-lg bg-gray-50/70">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex items-center justify-center text-gray-500 bg-white border border-gray-100 rounded-lg w-9 h-9"
                            >
                                <i class="text-sm fa-regular fa-envelope"></i>
                            </div>

                            <div class="min-w-0">
                                <p class="text-xs font-medium tracking-wide text-gray-400 uppercase">Email</p>

                                <p class="mt-1 font-medium text-gray-900 truncate">{{ $contact->email }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Subject --}}
                    <div class="p-4 border border-gray-100 rounded-lg bg-gray-50/70">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex items-center justify-center text-gray-500 bg-white border border-gray-100 rounded-lg w-9 h-9"
                            >
                                <i class="text-sm fa-regular fa-note-sticky"></i>
                            </div>

                            <div class="min-w-0">
                                <p class="text-xs font-medium tracking-wide text-gray-400 uppercase">Subject</p>

                                <p class="mt-1 font-medium text-gray-900 truncate">
                                    {{ $contact->subject ?? 'No Subject' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Sent At --}}
                    <div class="p-4 border border-gray-100 rounded-lg bg-gray-50/70">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex items-center justify-center text-gray-500 bg-white border border-gray-100 rounded-lg w-9 h-9"
                            >
                                <i class="text-sm fa-regular fa-clock"></i>
                            </div>

                            <div>
                                <p class="text-xs font-medium tracking-wide text-gray-400 uppercase">Sent At</p>

                                <p class="mt-1 font-medium text-gray-900">
                                    {{ $contact->created_at->format('d M Y, h:i A') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Message --}}
                <div class="mt-8">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <h3 class="font-medium text-gray-800">Message</h3>

                            <p class="text-xs text-black/60">Customer's message content</p>
                        </div>

                        <div class="flex items-center justify-center text-indigo-600 rounded-lg w-9 h-9 bg-indigo-50">
                            <i class="fa-regular fa-comment-dots"></i>
                        </div>
                    </div>

                    <div class="p-5 text-sm leading-7 text-gray-700 border border-gray-100 rounded-lg bg-gray-50">
                        {{ $contact->message }}
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div
                class="flex flex-col items-center justify-between gap-3 px-6 py-4 border-t border-gray-100 bg-gray-50 sm:flex-row sm:px-8"
            >
                <p class="text-xs text-gray-400">Message ID #{{ $contact->id }}</p>

                <p class="text-xs text-gray-400">Received {{ $contact->created_at->diffForHumans() }}</p>
            </div>
        </div>
    </div>

    {{-- Delete Confirmation --}}
    <script>
        document.getElementById('deleteContactForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const form = this;

            Swal.fire({
                title: 'Delete Message?',
                text: 'This message will be permanently deleted.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    </script>

@endsection
