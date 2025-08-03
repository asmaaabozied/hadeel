@extends('layout-guest')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gray-50 px-4">
        <div class="max-w-md w-full p-6 bg-white shadow rounded">
            <h2 class="text-lg font-semibold mb-4 text-center">{{ __('messages.Check Your Weekly Deportation') }}</h2>

            @if (session('error'))
                <div class="bg-red-100 text-red-700 p-2 rounded mb-3">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="bg-red-100 text-red-700 p-2 rounded mb-3">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('user.deportation.view') }}">
                @csrf

                <label class="block text-sm font-medium text-gray-700 mb-1">
                    {{ __('messages.Unique Identifier') }}
                </label>
                <input type="text" name="unique_identifier" placeholder="{{ __('messages.Enter your ID') }}" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm dark:bg-gray-800 dark:border-gray-600 dark:text-white dark:placeholder-gray-500" />

                <button type="submit"
                    class="mt-4 bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 text-sm w-full">
                    {{ __('messages.View Deportation') }}
                </button>
            </form>
        </div>
    </div>
@endsection
