<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'delivery')</title>

    <!-- Styles -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/tailwind.css') }}">
    <script src="{{ asset('js/tailwind-browser.min.js') }}"></script>

    <style>
        /* Scrollbar track */
        ::-webkit-scrollbar {
            width: 32px;
            /* Increased from 20px */
            height: 32px;
            /* Increased from 20px */
        }

        /* Scrollbar handle */
        ::-webkit-scrollbar-thumb {
            background: #111827;
            border: 8px solid #e5e7eb;
            /* Increased from 4px */
            border-radius: 16px;
            /* Increased from 10px */
        }

        /* Scrollbar track background */
        ::-webkit-scrollbar-track {
            background: #e5e7eb;
        }

        /* Firefox */
        * {
            scrollbar-width: auto;
            /* Changed from 'thin' to 'auto' for thicker scrollbar */
            scrollbar-color: #111827 #e5e7eb;
        }

        /* Wrap the scrollable content in this container */
        .scroll-container {
            direction: ltr;
            /* Keep content direction LTR */
            unicode-bidi: bidi-override;
            /* Respect logical override */
        }

        /* Force scrollbar to left */
        .scroll-container.force-left-scrollbar {
            direction: rtl;
        }

        .scroll-container.force-left-scrollbar>* {
            direction: ltr;
        }
    </style>

    @stack('styles')
</head>

<body x-data="main"
    class="antialiased relative font-inter bg-white dark:bg-black text-black dark:text-white text-sm font-normal vertical"
    :class="[$store.app.sidebar ? 'toggle-sidebar' : '', $store.app.rightsidebar ? 'right-sidebar' : '', $store.app.menu, $store
        .app.layout
    ]">

    <!-- Start Menu Sidebar Olverlay -->
    <div x-cloak class="fixed inset-0 bg-[black]/60 z-40 lg:hidden" :class="{ 'hidden': !$store.app.sidebar }"
        @click="$store.app.toggleSidebar()"></div>
    <!-- End Menu Sidebar Olverlay -->

    <div class="main-container navbar-sticky flex" :class="[$store.app.navbar]">

        @include('layouts.nav')

        <div class="main-content flex-1">

            @include('layouts.topbar')

            @php
                $scrollbarClass = app()->getLocale() === 'ar' ? '' : 'force-left-scrollbar';
            @endphp

            <div class="h-[calc(100vh-73px)] overflow-y-auto overflow-x-auto scroll-container {{ $scrollbarClass }}">
                <div x-data="sales" class="p-4 sm:p-7 min-h-[calc(100vh-145px)]">
                    @yield('content')
                </div>
                @include('layouts.footer')
            </div>


        </div>

    </div>


    <!-- Alpine js -->
    <script src="{{ asset('js/alpine-col.min.js') }}"></script>
    <script src="{{ asset('js/alpine-persist.min.js') }}"></script>
    <script src="{{ asset('js/alpine.min.js') }}" defer></script>

    <!-- Custom js -->
    <script src="{{ asset('js/custom.js') }}"></script>


    @stack('scripts')

    <div id="toast-container" class="fixed top-5 right-5 z-50 space-y-2"></div>
</body>

</html>
