@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto p-6 bg-white rounded-xl shadow">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">{{ __('messages.Admin Details') }}</h2>

        <div class="space-y-4">
            <p><span class="font-semibold text-gray-700">{{ __('messages.ID') }}:</span> {{ $user->id }}</p>
            <p><span class="font-semibold text-gray-700">{{ __('messages.Name') }}:</span> {{ $user->name }}</p>
            <p><span class="font-semibold text-gray-700">{{ __('messages.Email') }}:</span> {{ $user->email }}</p>
            <p><span class="font-semibold text-gray-700">{{ __('messages.Phone') }}:</span> {{ $user->phone ?? '-' }}</p>
            <p><span class="font-semibold text-gray-700">{{ __('messages.Unique Identifier') }}:</span>
                {{ $user->unique_identifier ?? '-' }}</p>
        </div>

        <div class="mt-6">
            <a href="{{ route('users.index') }}" class="text-indigo-600 hover:underline">←
                {{ __('messages.Back to list') }}</a>
        </div>
    </div>
@endsection
