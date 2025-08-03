<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Custom Tailwind Config with Primary Color -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: '#480ca8',
                            dark: '#3f0894',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

    @stack('head')
</head>

<body class="antialiased text-gray-800 bg-gray-50 font-sans">

    <!-- 🌐 Language Switcher -->
    <div class="flex justify-end px-6 pt-4" x-data="{ open: false }">
        <div class="relative">
            <button @click="open = !open"
                class="text-sm font-medium text-gray-700 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 focus:outline-none">
                🌐 {{ strtoupper(app()->getLocale()) }}
            </button>

            <div x-show="open" @click.outside="open = false" x-transition
                class="absolute mt-2 right-0 w-28 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded shadow-lg z-50">
                <a href="{{ route('lang.switch', 'en') }}"
                    class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 {{ app()->getLocale() === 'en' ? 'font-semibold' : '' }}">
                    English
                </a>
                <a href="{{ route('lang.switch', 'ar') }}"
                    class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 {{ app()->getLocale() === 'ar' ? 'font-semibold' : '' }}">
                    العربية
                </a>
            </div>
        </div>
    </div>

    @yield('content')
    @stack('scripts')

    <!-- Start Footer -->
    <footer class="p-7 flex flex-wrap items-center justify-center sm:justify-between gap-3">
        <div class="copyright-area">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <div class="copyright-inner">
                            © Copyright <a href="https://geeltech.com" target="_blank"
                                style="color: #1595b9; text-decoration: underline;">GEELTECH</a> {{ date('Y') }}.
                            All Right Reserved.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- End Footer -->

</body>

</html>
