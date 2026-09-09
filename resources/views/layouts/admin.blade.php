{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth antialiased">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel')) - Admin</title>
    <meta name="robots" content="noindex, nofollow">

    <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/admin.js'])

    @if (request()->routeIs('admin.content.create', 'admin.content.edit'))
        {{-- TinyMCE loaded only on content edit/create pages --}}
        <script src="{{ asset('plugins/tinymce/tinymce.min.js') }}" defer></script>
    @endif

    <style>
        [x-cloak] {
            display: none !important;
        }

        .sidebar-transition {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .backdrop-blur-glass {
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
    </style>
</head>

<body class="font-['figtree'] bg-neutral-50">
    <a href="#admin-main"
        class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-[110] focus:rounded-lg focus:bg-primary-600 focus:px-4 focus:py-2.5 focus:text-sm focus:font-medium focus:text-white focus:shadow-lg focus:outline-none focus:ring-2 focus:ring-primary-400 focus:ring-offset-2">
        Skip to main content
    </a>

    <div x-data="adminLayout()" class="min-h-screen">
        {{-- Sidebar Backdrop (Mobile) --}}
        <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 bg-neutral-900/50 lg:hidden z-40"
            @click="sidebarOpen = false">
        </div>

        {{-- Sidebar --}}
        <aside x-show="sidebarOpen || !isMobile()" aria-label="Admin navigation"
            x-transition:enter="transition-transform ease-in-out duration-300"
            x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transition-transform ease-in-out duration-300"
            x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
            class="fixed inset-y-0 left-0 z-50 flex w-80 flex-col border-r border-neutral-200 bg-white shadow-xl sidebar-transition transform lg:translate-x-0 lg:z-40">

            {{-- Sidebar Header --}}
            <div class="flex h-16 shrink-0 items-center justify-between border-b border-neutral-200 px-6">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3">
                    <x-application-logo class="h-8 w-auto text-primary-600" />
                    <span class="text-xl font-bold text-neutral-900">Admin</span>
                </a>
                <button @click="sidebarOpen = false"
                    class="lg:hidden p-2 rounded-md text-neutral-400 hover:text-neutral-600 hover:bg-neutral-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Sidebar Content --}}
            <div class="flex-1 overflow-y-auto">
                {{-- Quick Actions --}}
                <div class="p-6 border-b border-neutral-100">
                    <h3 class="text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-4">Quick Actions</h3>
                    <div class="space-y-2">
                        <a href="{{ route('admin.content.create') }}"
                            class="group flex items-center w-full px-3 py-2 text-sm font-medium text-neutral-700 rounded-lg hover:bg-primary-50 hover:text-primary-600 transition-all duration-200">
                            <svg class="w-5 h-5 mr-3 text-neutral-400 group-hover:text-primary-500" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            New Content
                        </a>
                        <a href="{{ route('admin.leads.export') }}"
                            class="group flex items-center w-full px-3 py-2 text-sm font-medium text-neutral-700 rounded-lg hover:bg-primary-50 hover:text-primary-600 transition-all duration-200">
                            <svg class="w-5 h-5 mr-3 text-neutral-400 group-hover:text-primary-500" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Export Leads
                        </a>
                        <a href="{{ route('admin.leads.index') }}?status=new"
                            class="group flex items-center w-full px-3 py-2 text-sm font-medium text-neutral-700 rounded-lg hover:bg-primary-50 hover:text-primary-600 transition-all duration-200">
                            <div class="relative mr-3">
                                <svg class="w-5 h-5 text-neutral-400 group-hover:text-primary-500" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                                @if (($newLeads ?? 0) > 0)
                                    <span
                                        class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">
                                        {{ $newLeads }}
                                    </span>
                                @endif
                            </div>
                            New Leads
                        </a>
                    </div>
                </div>

                {{-- Main Navigation --}}
                <nav class="p-6">
                    <h3 class="text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-4">Navigation</h3>
                    <div class="space-y-1">
                        <x-cms.admin-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" icon="dashboard">
                            Dashboard
                        </x-cms.admin-nav-link>
                        <x-cms.admin-nav-link :href="route('admin.content.index')" :active="request()->routeIs('admin.content.*')" icon="content">
                            Content
                        </x-cms.admin-nav-link>
                        <x-cms.admin-nav-link :href="route('admin.leads.index')" :active="request()->routeIs('admin.leads.*')" icon="leads">
                            Leads
                            @if (($newLeads ?? 0) > 0)
                                <span class="ml-auto inline-flex items-center justify-center min-w-[1.25rem] h-5 px-1.5 text-xs font-bold bg-red-500 text-white">{{ $newLeads }}</span>
                            @endif
                        </x-cms.admin-nav-link>
                        @if (($followUpLeads ?? 0) > 0)
                            <a href="{{ route('admin.leads.index') }}?status=new&amp;priority=high"
                               class="flex items-center gap-2 px-3 py-2 text-xs font-medium text-amber-800 bg-amber-50 border border-amber-200">
                                {{ $followUpLeads }} need follow-up
                            </a>
                        @endif
                        <x-cms.admin-nav-link :href="route('admin.testimonials.index')" :active="request()->routeIs('admin.testimonials.*')" icon="testimonials">
                            Testimonials
                        </x-cms.admin-nav-link>
                        <x-cms.admin-nav-link :href="route('admin.ctas.index')" :active="request()->routeIs('admin.ctas.*')" icon="content">
                            CTAs
                        </x-cms.admin-nav-link>
                        <x-cms.admin-nav-link :href="route('admin.analytics.index')" :active="request()->routeIs('admin.analytics.*')" icon="analytics">
                            Analytics
                        </x-cms.admin-nav-link>
                        <x-cms.admin-nav-link :href="route('admin.activity.index')" :active="request()->routeIs('admin.activity.*')" icon="analytics">
                            Activity Log
                        </x-cms.admin-nav-link>
                        <x-cms.admin-nav-link :href="route('admin.redirects.index')" :active="request()->routeIs('admin.redirects.*')" icon="content">
                            Redirects
                        </x-cms.admin-nav-link>
                        <x-cms.admin-nav-link :href="route('admin.settings.index')" :active="request()->routeIs('admin.settings.*')" icon="settings">
                            Settings
                        </x-cms.admin-nav-link>
                    </div>
                </nav>

                {{-- Recent Activity --}}
                <div class="p-6 border-t border-neutral-100">
                    <h3 class="text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-4">Recent Activity</h3>
                    <div class="space-y-3">
                        @forelse($recentActivities ?? [] as $activity)
                            <div class="flex items-start space-x-3 text-sm">
                                <div class="w-2 h-2 mt-2 bg-primary-500 rounded-full flex-shrink-0"></div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-neutral-700 truncate" title="{{ $activity->description }}">
                                        {{ \Illuminate\Support\Str::limit($activity->description, 72) }}
                                    </p>
                                    <p class="text-neutral-400 text-xs">
                                        {{ $activity->created_at->diffForHumans() }}
                                        @if ($activity->causer)
                                            • by {{ $activity->causer->name }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-neutral-500 italic">No recent activity</p>
                        @endforelse
                    </div>
                    <a href="{{ route('admin.activity.index') }}" class="mt-4 inline-block text-xs font-semibold text-primary-600 hover:text-primary-700">View all activity →</a>
                </div>
            </div>
        </aside>

        {{-- Main Content Area (offset for fixed sidebar on lg+) --}}
        <div class="flex min-h-screen w-full min-w-0 flex-col lg:pl-80">
            {{-- Top Navigation --}}
            <header
                class="sticky top-0 z-30 border-b border-neutral-200 bg-white/90 backdrop-blur-glass supports-[backdrop-filter]:bg-white/80">
                <div class="flex items-center justify-between h-16 px-6">
                    {{-- Left: Menu Toggle & Breadcrumb --}}
                    <div class="flex items-center space-x-4">
                        <button @click="sidebarOpen = !sidebarOpen" type="button" aria-label="Toggle menu"
                            class="lg:hidden p-2 rounded-md text-neutral-500 hover:text-neutral-700 hover:bg-neutral-100 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>

                        {{-- Breadcrumb --}}
                        <nav class="flex" aria-label="Breadcrumb">
                            <ol class="flex items-center space-x-2 text-sm">
                                <li>
                                    <a href="{{ route('admin.dashboard') }}"
                                        class="text-neutral-500 hover:text-neutral-700 transition-colors">
                                        Dashboard
                                    </a>
                                </li>
                                @hasSection('header')
                                    <li class="flex items-center">
                                        <svg class="w-4 h-4 text-neutral-400 mx-2" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                        <span class="text-neutral-900 font-medium">@yield('header')</span>
                                    </li>
                                @endif
                            </ol>
                        </nav>
                    </div>

                    {{-- Right: Search, Notifications, User Menu --}}
                    <div class="flex items-center space-x-4">
                        {{-- Search content (GET → index with ?search=) --}}
                        <form method="GET" action="{{ route('admin.content.index') }}" role="search"
                            class="relative hidden md:block">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-neutral-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <label for="admin-search" class="sr-only">Search content</label>
                            <input id="admin-search" type="search" name="search"
                                value="{{ request('search') }}" placeholder="Search content..."
                                class="pl-10 pr-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent text-sm w-64 transition-all">
                        </form>

                        {{-- Notifications --}}
                        <x-dropdown align="right" width="80">
                            <x-slot name="trigger">
                                <button type="button" aria-label="Notifications"
                                    class="relative p-2 text-neutral-500 hover:text-neutral-700 rounded-lg hover:bg-neutral-100 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                    @if (($newLeads ?? 0) > 0)
                                        <span
                                            class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full"></span>
                                    @endif
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <div class="p-4 border-b border-neutral-100">
                                    <h3 class="text-sm font-semibold text-neutral-900">Notifications</h3>
                                </div>
                                <div class="max-h-96 overflow-y-auto">
                                    @if (($newLeads ?? 0) > 0)
                                        <a href="{{ route('admin.leads.index') }}?status=new"
                                            class="flex items-center px-4 py-3 hover:bg-neutral-50 transition-colors border-b border-neutral-100">
                                            <div class="w-2 h-2 bg-red-500 rounded-full mr-3"></div>
                                            <div>
                                                <p class="text-sm font-medium text-neutral-900">{{ $newLeads ?? 0 }}
                                                    new leads</p>
                                                <p class="text-xs text-neutral-500">Require attention</p>
                                            </div>
                                        </a>
                                    @endif
                                </div>
                            </x-slot>
                        </x-dropdown>

                        {{-- User Menu --}}
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="flex items-center space-x-3 text-sm focus:outline-none">
                                    <div class="flex items-center space-x-2">
                                        <div
                                            class="h-8 w-8 rounded-full bg-primary-100 border-2 border-neutral-200 flex items-center justify-center text-primary-700 font-bold text-sm select-none">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </div>
                                        <div class="hidden md:block text-left">
                                            <div class="font-medium text-neutral-900">
                                                {{ Str::limit(Auth::user()->name, 20) }}</div>
                                            <div class="text-xs text-neutral-500 capitalize">
                                                {{ Auth::user()->role }}</div>
                                        </div>
                                    </div>
                                    <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile.edit')"
                                    class="flex items-center space-x-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span>Profile</span>
                                </x-dropdown-link>

                                @if (!auth()->user()->has2FA())
                                    <x-dropdown-link :href="route('2fa.setup')"
                                        class="flex items-center space-x-2 text-yellow-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                        <span>Enable 2FA</span>
                                    </x-dropdown-link>
                                @endif

                                <x-dropdown-link :href="route('home')" target="_blank"
                                    rel="noopener noreferrer" class="flex items-center space-x-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                    <span>View Site</span>
                                </x-dropdown-link>

                                <div class="border-t border-neutral-100"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();"
                                        class="flex items-center space-x-2 text-red-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        <span>Log Out</span>
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </div>
            </header>

            {{-- Main Content --}}
            <main id="admin-main" tabindex="-1"
                class="flex-1 overflow-y-auto outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-primary-500/30"
                aria-label="Admin content">
                <div class="p-4 sm:p-6">
                    <x-cms.flash-messages />
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    {{-- Alpine.js Admin Layout Logic --}}
    <script>
        function adminLayout() {
            return {
                sidebarOpen: window.innerWidth >= 1024,

                init() {
                    this.$watch('sidebarOpen', value => {
                        if (value && this.isMobile()) {
                            document.body.style.overflow = 'hidden';
                        } else {
                            document.body.style.overflow = '';
                        }
                    });
                },

                isMobile() {
                    return window.innerWidth < 1024;
                },

                closeSidebar() {
                    if (this.isMobile()) {
                        this.sidebarOpen = false;
                    }
                }
            }
        }
    </script>

    @if (request()->routeIs('admin.content.create', 'admin.content.edit'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                if (typeof tinymce !== 'undefined') {
                    tinymce.init({
                        selector: '.tinymce-editor',
                        height: 300,
                        autoresize: true,
                        plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table code help wordcount',
                        toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image table | removeformat | fullscreen code',
                        content_css: [
                            '{{ asset('plugins/tinymce/skins/ui/oxide/content.min.css') }}',
                            '{{ asset('plugins/tinymce/skins/content/default/content.min.css') }}',
                        ],
                        skin: window.matchMedia('(prefers-color-scheme: dark)').matches ? 'oxide-dark' : 'oxide',
                        content_style: `
                            body {
                                font-family: Figtree, sans-serif; /* Match form font */
                                line-height: 1.5; /* Match form line-height */
                                color: #525252; /* Match form text color (neutral-600) */
                                font-size: 14px; /* Match form text-sm */
                            }
                            p { margin-bottom: 1em; }
                        `,
                        menubar: 'file edit view insert format tools table help',
                        editor_deselector: 'textarea[readonly]',
                        setup: function(editor) {
                            editor.on('init', function() {
                                editor.getContainer().classList.add('rounded-md', 'border',
                                    'border-neutral-300', 'shadow-sm');
                            });
                        }
                    });
                }
            });
        </script>
    @endif

    @stack('scripts')
</body>

</html>
