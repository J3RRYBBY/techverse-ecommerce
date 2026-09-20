<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>E-commerce</title>

        <script>
            if (localStorage.getItem('sidebarCollapsed') === 'true') {
                document.documentElement.classList.add('sidebar-collapsed');
            }

            document.documentElement.classList.add('sidebar-loading');
        </script>

        @vite (['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="font-inter">
        <div class="min-h-screen">
            {{-- side bar --}}
            <aside
                id="sidebar"
                class="fixed top-0 left-0 z-30 flex flex-col justify-between h-screen text-black transition-all duration-300 ease-in-out bg-white border-r w-60"
            >
                <div>
                    <div class="flex items-center justify-between h-16 px-4 py-6">
                        <a href="{{ route('admin#dashboard') }}" class="pl-4 text-2xl truncate sidebar-text font-gugi"
                            >tech<span class="font-bold text-lime-400">V</span>erse</a
                        >
                        <button id="toggle-btn" type="button" class="p-5 cursor-pointer">
                            <x-lucide-sidebar-close id="toggle-close-icon" class="w-5 h-5" />
                            <x-lucide-sidebar-open id="toggle-open-icon" class="hidden w-5 h-5" />
                        </button>
                    </div>

                    <nav class="px-3 pt-4 space-y-2 text-sm">
                        <a
                            href="{{ route('admin#dashboard') }}"
                            class="flex items-center px-6 py-3 space-x-4 transition-colors rounded-lg text-black/70"
                        >
                            <x-heroicon-o-home class="w-6 h-6" />
                            <span class="truncate sidebar-text">Dashboard</span>
                        </a>
                        <a
                            href="{{ route('category#list') }}"
                            class="flex items-center px-6 py-3 space-x-4 transition-colors rounded-lg text-black/70"
                        >
                            <x-heroicon-o-squares-2x2 class="w-6 h-6" />
                            <span class="truncate sidebar-text">Categotries</span>
                        </a>
                        <a
                            href="{{ route('product#list') }}"
                            class="flex items-center px-6 py-3 space-x-4 transition-colors rounded-lg text-black/70"
                        >
                            <x-heroicon-o-shopping-bag class="w-6 h-6" />
                            <span class="truncate sidebar-text">Products List</span>
                        </a>
                        <a
                            href="{{ route('admin#payment') }}"
                            class="flex items-center px-6 py-3 space-x-4 transition-colors rounded-lg text-black/70"
                        >
                            <x-heroicon-o-credit-card class="w-6 h-6" />
                            <span class="truncate sidebar-text">Payment Method</span>
                        </a>
                        <a
                            href="{{ route('admin#salesInfo') }}"
                            class="flex items-center px-6 py-3 space-x-4 transition-colors rounded-lg text-black/70"
                        >
                            <x-heroicon-o-chart-bar class="w-6 h-6" />
                            <span class="truncate sidebar-text">Sales Information</span>
                        </a>
                        <a
                            href="{{ route('admin#orderBoard') }}"
                            class="flex items-center px-6 py-3 space-x-4 transition-colors rounded-lg text-black/70"
                        >
                            <x-heroicon-o-clipboard-document-list class="w-6 h-6" />
                            <span class="truncate sidebar-text">Order Board</span>
                        </a>
                        <a
                            href="{{ route('admin#customerContact') }}"
                            class="flex items-center px-6 py-3 space-x-4 transition-colors rounded-lg text-black/70"
                        >
                            <x-heroicon-o-user class="w-6 h-6" />
                            <span class="truncate sidebar-text">Customer Contact</span>
                        </a>
                    </nav>
                </div>
            </aside>
            {{-- sidebar end --}}

            {{-- navbar --}}
            <main id="main-content" class="flex flex-col min-h-screen transition-all duration-300 ease-in-out ml-60">
                <header class="z-20 flex items-center justify-end h-16 px-10 py-6 bg-white border-b">
                    @if (Auth::check())
                        <el-dropdown class="flex items-center">
                            <button class="flex items-center">
                                <div class="border rounded-full">
                                    <img
                                        src="{{ auth()->user()->profile ?? asset('defaultImage/user.png') }}"
                                        class="object-cover w-10 h-10 rounded-full"
                                        aria-hidden="true"
                                        alt="{{ auth()->user()->name }}"
                                    />
                                </div>
                                <p class="ml-5 text-sm">{{ auth()->user()->name }}</p>
                            </button>

                            <el-menu
                                anchor="bottom end"
                                popover
                                class="m-0 w-56 border border-gray-300 origin-top-right rounded-md bg-gray-50 p-0 outline outline-1 -outline-offset-1 outline-white/10 transition [--anchor-gap:theme(spacing.2)] [transition-behavior:allow-discrete] data-[closed]:scale-95 data-[closed]:transform data-[closed]:opacity-0 data-[enter]:duration-100 data-[leave]:duration-75 data-[enter]:ease-out data-[leave]:ease-in"
                            >
                                <div class="">
                                    <a
                                        href="{{ route('admin#profile') }}"
                                        class="block px-4 py-3 text-sm text-black focus:bg-gray-200"
                                        >Account settings</a
                                    >
                                    @if (Auth::user()->role === 'superadmin')
                                        <a
                                            href="{{ route('admin#management') }}"
                                            class="block px-4 py-3 text-sm text-black focus:bg-gray-200"
                                        >
                                            Admin management
                                        </a>
                                    @endif
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button
                                            type="submit"
                                            class="block w-full px-4 py-3 text-sm text-left text-black focus:bg-gray-200"
                                        >
                                            Sign out
                                        </button>
                                    </form>
                                </div>
                            </el-menu>
                        </el-dropdown>
                    @else
                        <a href="{{ route('register') }}" class="">
                            <div class="px-2 py-1 uppercase">
                                <i class="mr-1 text-xl fa-regular fa-circle-user"></i>
                                Sign Up
                            </div>
                        </a>
                    @endif
                </header>

                <div class="flex-1 overflow-y-auto bg-gray-100">
                    @yield ('content')
                </div>
            </main>
            {{-- navbar end --}}
        </div>
    </body>

    <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>

    <script>
        requestAnimationFrame(() => {
            document.documentElement.classList.remove('sidebar-loading');
        });
    </script>

    @yield ('script-code')
</html>
