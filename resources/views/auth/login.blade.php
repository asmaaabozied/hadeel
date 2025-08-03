@extends('layout-guest')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gray-100 px-4">
        <div class="w-full max-w-4xl bg-white rounded-2xl shadow-lg overflow-hidden grid grid-cols-1 md:grid-cols-2">
            <!-- Left Image -->
            <div class="hidden md:block bg-cover bg-center"
                style="background-image: url('{{ asset('images/login-banner.jpg') }}');">
            </div>

            <!-- Login Form -->
            <div class="p-8 sm:p-12">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Welcome Back 👋</h2>

                @if ($errors->any())
                    <div class="mb-4 text-sm text-red-600">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login-post') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring focus:ring-yellow-400 focus:border-yellow-400">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" name="password" required
                            class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring focus:ring-yellow-400 focus:border-yellow-400">
                    </div>

                    <div class="flex justify-between items-center text-sm">
                        <label class="flex items-center">
                            <input type="checkbox" name="remember" class="mr-2">
                            Remember me
                        </label>
                        <!-- <a href="#" class="text-yellow-500 hover:underline">Forgot password?</a> -->
                    </div>

                    <button type="submit"
                        class="w-full bg-primary hover:bg-primary-dark text-white font-semibold py-2 rounded-lg transition duration-200">
                        Login
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
