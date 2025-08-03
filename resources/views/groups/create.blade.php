@extends('layouts.app')

@section('content')
    <div class="max-w-xl mx-auto px-4 py-6 bg-white rounded-xl shadow">
        <h2 class="text-xl font-bold text-gray-800 mb-4">{{ __('messages.add_new_group') }}</h2>

        @if ($errors->any())
            <div class="mb-4 text-red-600">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('groups.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label class="block font-medium text-gray-700 mb-1">{{ __('messages.name') }}</label>
                <input type="text" name="name" class="w-full border rounded-md p-2" required>
            </div>

            <div class="mb-4">
                <label class="block font-medium text-gray-700 mb-1">{{ __('messages.image') }}</label>
                <input type="file" name="image" accept="image/*" class="w-full border rounded-md p-2" required>
            </div>

            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md shadow">
                {{ __('messages.save') }}
            </button>
            <a href="{{ route('groups.index') }}" class="ml-4 text-gray-600 hover:underline">{{ __('messages.cancel') }}</a>
        </form>
    </div>
@endsection
