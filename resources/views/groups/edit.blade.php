@extends('layouts.app')

@section('content')
    <div class="max-w-xl mx-auto px-4 py-6 bg-white rounded-xl shadow">
        <h2 class="text-xl font-bold text-gray-800 mb-4">{{ __('messages.edit_group') }}</h2>

        @if ($errors->any())
            <div class="mb-4 text-red-600">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('groups.update', $group->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block font-medium text-gray-700 mb-1">{{ __('messages.name') }}</label>
                <input type="text" name="name" value="{{ $group->name }}" class="w-full border rounded-md p-2"
                    required>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.current_group_image') }}</label>

                {{-- Image Preview --}}
                <div class="relative w-full h-44 rounded-lg overflow-hidden shadow-sm border border-gray-200 mb-3">
                    <img src="{{ asset('storage/' . $group->image) }}" alt="Current Group Image"
                        class="w-full h-full object-cover transition duration-300 hover:scale-105">
                </div>

                {{-- Upload Input --}}
                <label class="block">
                    <span class="text-sm text-gray-600 mb-1 block">{{ __('messages.change_image') }}</span>
                    <input type="file" name="image" accept="image/*"
                        class="block w-full text-sm text-gray-800 bg-white border border-gray-300 rounded-md shadow-sm file:bg-indigo-600 file:text-white file:border-none file:px-4 file:py-2 file:rounded file:cursor-pointer hover:file:bg-indigo-700 transition">
                </label>
            </div>

            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md shadow">
                {{ __('messages.update') }}
            </button>
            <a href="{{ route('groups.index') }}"
                class="ml-4 text-gray-600 hover:underline">{{ __('messages.cancel') }}</a>
        </form>
    </div>
@endsection
