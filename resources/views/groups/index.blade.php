@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">{{ __('messages.groups_title') }}</h2>
            <button onclick="openAddModal()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md shadow">
                + {{ __('messages.add_group') }}
            </button>
        </div>

        @if (session('success'))
            <div id="success-alert"
                class="mb-4 flex items-center justify-between gap-2 p-4 rounded-lg bg-emerald-100 text-emerald-800 shadow transition-all duration-300 ease-in-out">
                <span class="text-sm font-medium">{{ session('success') }}</span>
                <button onclick="document.getElementById('success-alert').remove()"
                    class="text-emerald-700 hover:text-emerald-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <script>
                setTimeout(() => {
                    const alert = document.getElementById('success-alert');
                    if (alert) {
                        alert.classList.add('opacity-0', 'translate-y-2');
                        setTimeout(() => alert.remove(), 300);
                    }
                }, 3000);
            </script>
        @endif

        @if ($errors->any())
            <div id="error-alert"
                class="mb-4 p-4 bg-rose-100 text-rose-800 rounded-lg shadow flex flex-col gap-1 transition-all duration-300">
                <strong class="font-semibold">{{ __('messages.fix_errors') }}</strong>
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>

            <script>
                setTimeout(() => {
                    const alert = document.getElementById('error-alert');
                    if (alert) {
                        alert.classList.add('opacity-0', 'translate-y-2');
                        setTimeout(() => alert.remove(), 300);
                    }
                }, 4000);
            </script>
        @endif

        <div class="grid gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
            @foreach ($groups as $group)
                <div class="bg-white rounded-lg shadow overflow-hidden flex flex-col h-full group relative">

                    <div class="absolute top-2 right-2 z-10 flex gap-2">
                        <div class="backdrop-blur-sm bg-white/70 shadow-md rounded-full">
                            <button
                                onclick="openEditModal({{ $group->id }}, '{{ addslashes($group->name) }}', '{{ asset('storage/' . $group->image) }}')"
                                class="inline-flex items-center justify-center w-8 h-8 text-indigo-600 hover:text-indigo-700 transition"
                                title="{{ __('messages.edit') }}">
                                ✏️
                            </button>
                        </div>

                        <div class="backdrop-blur-sm bg-white/70 shadow-md rounded-full">
                            <form action="{{ route('groups.destroy', $group->id) }}" method="POST"
                                onsubmit="return confirm('{{ __('messages.delete_confirm') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center justify-center w-8 h-8 text-red-600 hover:text-red-700 transition">
                                    🗑️
                                </button>
                            </form>
                        </div>
                    </div>

                    <a href="{{ route('groups.sheets', $group->id) }}"
                        class="flex flex-col h-full transition-all duration-150 group-hover:ring-2 ring-indigo-400 relative">

                        <div class="relative w-full h-40 overflow-hidden">
                            <img src="{{ asset('storage/' . $group->image) }}" alt="{{ $group->name }}"
                                class="w-full h-full object-cover rounded-t-lg transition duration-300">

                            <div
                                class="absolute inset-0 bg-black/40 backdrop-blur-sm opacity-0 group-hover:opacity-100 flex items-center justify-center text-center px-2 transition duration-300">
                                <span
                                    class="text-white text-sm sm:text-base md:text-lg font-bold tracking-wide leading-snug">
                                    {{ __('messages.view_group_sheets') }}
                                </span>
                            </div>
                        </div>

                        <div class="p-3 flex flex-col flex-grow">
                            <h3 class="text-base font-semibold mb-1 text-gray-800">{{ $group->name }}</h3>
                        </div>
                    </a>
                </div>
            @endforeach

            {{-- Modal --}}
            <div id="groupModal"
                class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm hidden items-center justify-center px-4">
                <div class="bg-white w-full max-w-xl max-h-[90vh] overflow-y-auto rounded-xl shadow-lg p-6 relative">
                    <button onclick="closeGroupModal()"
                        class="absolute top-2 right-3 text-gray-500 hover:text-black text-xl">&times;</button>

                    <h2 id="modalTitle" class="text-xl font-bold text-gray-800 mb-4">{{ __('messages.edit_group') }}</h2>

                    <form id="groupForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="_method" name="_method" value="PUT">
                        <input type="hidden" name="group_id" id="modal_group_id">

                        <div class="mb-4">
                            <label class="block font-medium text-gray-700 mb-1">{{ __('messages.name') }}</label>
                            <input type="text" name="name" id="modal_group_name" class="w-full border rounded-md p-2"
                                required>
                        </div>

                        <div class="mb-6">
                            <label class="block font-medium text-gray-700 mb-1">{{ __('messages.image') }}</label>
                            <div class="relative w-full h-44 rounded-lg overflow-hidden shadow-sm border border-gray-200 mb-3 hidden"
                                id="imagePreviewContainer">
                                <img id="modal_group_image_preview" src="" alt="Group Image"
                                    class="w-full h-full object-cover transition duration-300 hover:scale-105">
                            </div>

                            <label class="block">
                                <input type="file" name="image" accept="image/*"
                                    class="block w-full text-sm text-gray-800 bg-white border border-gray-300 rounded-md shadow-sm file:bg-indigo-600 file:text-white file:border-none file:px-4 file:py-2 file:rounded file:cursor-pointer hover:file:bg-indigo-700 transition"
                                    required>
                            </label>
                        </div>

                        <div class="flex justify-end gap-2">
                            <button type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md shadow">
                                <span id="submitButtonText">{{ __('messages.update') }}</span>
                            </button>
                            <button type="button" onclick="closeGroupModal()"
                                class="text-gray-600 hover:underline px-4 py-2 text-sm">
                                {{ __('messages.cancel') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="mt-6">{{ $groups->links() }}</div>
    </div>
@endsection


<script>
    function openEditModal(id, name, imageUrl) {
        const form = document.getElementById('groupForm');
        const method = document.getElementById('_method');

        document.getElementById('modalTitle').innerText = 'Edit Group';
        document.getElementById('submitButtonText').innerText = 'Update';
        method.value = 'PUT';

        form.action = `/groups/${id}`;
        document.getElementById('modal_group_id').value = id;
        document.getElementById('modal_group_name').value = name;

        document.getElementById('modal_group_image_preview').src = imageUrl;
        document.getElementById('imagePreviewContainer').classList.remove('hidden');

        openGroupModal();
    }

    function openAddModal() {
        const form = document.getElementById('groupForm');
        const method = document.getElementById('_method');

        document.getElementById('modalTitle').innerText = 'Add Group';
        document.getElementById('submitButtonText').innerText = 'Create';
        method.value = 'POST';

        form.action = `/groups`; // must match your store route
        document.getElementById('modal_group_name').value = '';
        document.getElementById('modal_group_id').value = '';
        document.getElementById('imagePreviewContainer').classList.add('hidden');

        openGroupModal();
    }

    function openGroupModal() {
        const modal = document.getElementById('groupModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeGroupModal() {
        const modal = document.getElementById('groupModal');
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }
</script>
