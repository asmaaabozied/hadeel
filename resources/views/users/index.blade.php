@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">{{ __('messages.Admins') }}</h2>

        <div class="overflow-x-auto bg-white rounded-xl shadow">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold text-gray-800"></h2>
                <button onclick="openUserModal()"
                    class="inline-block bg-indigo-600 text-white text-sm font-medium px-4 py-2 rounded hover:bg-indigo-700 transition">
                    + {{ __('messages.Add Admin') }}
                </button>
            </div>

            <table class="min-w-full text-sm text-left text-gray-700">
                <thead class="bg-gray-100 uppercase text-xs font-semibold">
                    <tr>
                        <th class="px-6 py-3">{{ __('messages.ID') }}</th>
                        <th class="px-6 py-3">{{ __('messages.Name') }}</th>
                        <th class="px-6 py-3">{{ __('messages.Phone') }}</th>
                        <th class="px-6 py-3">{{ __('messages.Unique Identifier') }}</th>
                        <th class="px-6 py-3">{{ __('messages.Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($users as $user)
                        <tr>
                            <td class="px-6 py-4">{{ $user->id }}</td>
                            <td class="px-6 py-4">{{ $user->name }}</td>
                            <td class="px-6 py-4">{{ $user->phone ?? '-' }}</td>
                            <td class="px-6 py-4">{{ $user->unique_identifier ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('admins.show', $user->id) }}"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-white bg-emerald-500 rounded-lg hover:bg-emerald-600 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ __('messages.View') }}
                                    </a>

                                    <button
                                        onclick="openEditAdminModal({{ $user->id }}, '{{ $user->name }}', '{{ $user->phone }}', '{{ $user->email }}')"
                                        class="inline-flex items-center gap-1 px-3 py-1 text-xs font-medium text-white bg-blue-600 rounded hover:bg-blue-700 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M11 5h6M6 5h.01M6 9h.01M6 13h.01M6 17h.01M9 17h6M15 13h-6M15 9h-6M15 5h-6M9 5v12m6-12v12" />
                                        </svg>
                                        {{ __('messages.Edit') }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $users->links() }}
        </div>

        {{-- Add User Modal --}}
        <div id="userModal"
            class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm transition-opacity duration-200">
            <div
                class="bg-white dark:bg-gray-900 w-full max-w-lg mx-auto rounded-2xl shadow-2xl p-6 relative animate-fadeIn">
                <button onclick="closeUserModal()"
                    class="absolute top-4 right-4 text-gray-400 hover:text-black dark:hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <h3 class="text-xl font-semibold text-black dark:text-white mb-6 text-center">
                    {{ __('messages.Add New Admin') }}
                </h3>

                <form method="POST" action="{{ route('admins.store') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-medium">{{ __('messages.Name') }} <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" required class="w-full rounded-lg px-4 py-2" />
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium">{{ __('messages.Email') }} <span
                                class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email" required class="w-full rounded-lg px-4 py-2" />
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium">{{ __('messages.Phone') }}</label>
                        <input type="text" name="phone" id="phone" class="w-full rounded-lg px-4 py-2" />
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium">{{ __('messages.Password') }} <span
                                class="text-red-500">*</span></label>
                        <input type="password" name="password" id="password" required
                            class="w-full rounded-lg px-4 py-2" />
                    </div>

                    <div>
                        <label for="password_confirmation"
                            class="block text-sm font-medium">{{ __('messages.Confirm Password') }} <span
                                class="text-red-500">*</span></label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="w-full rounded-lg px-4 py-2" />
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <button type="button" onclick="closeUserModal()"
                            class="px-4 py-2 rounded-lg border text-sm font-medium text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                            {{ __('messages.Cancel') }}
                        </button>
                        <button type="submit"
                            class="px-5 py-2 rounded-lg bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 transition">
                            {{ __('messages.Save Admin') }}
                        </button>
                    </div>

                </form>
            </div>
        </div>

        {{-- Edit Admin Modal --}}
        <div id="editUserModal"
            class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm transition-opacity duration-200">
            <div
                class="bg-white dark:bg-gray-900 w-full max-w-lg mx-auto rounded-2xl shadow-2xl p-6 relative animate-fadeIn">
                <button onclick="closeEditUserModal()"
                    class="absolute top-4 right-4 text-gray-400 hover:text-black dark:hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <h3 class="text-xl font-semibold text-black dark:text-white mb-6 text-center">
                    {{ __('messages.Edit Admin') }}
                </h3>

                <form method="POST" action="" id="editUserForm" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="edit_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('messages.Name') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="edit_name" required
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition" />
                    </div>

                    <div>
                        <label for="edit_email" class="block text-sm font-medium">{{ __('messages.Email') }}</label>
                        <input type="email" name="email" id="edit_email" required
                            class="w-full rounded-lg px-4 py-2" />
                    </div>


                    <div>
                        <label for="edit_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('messages.Phone') }}
                        </label>
                        <input type="text" name="phone" id="edit_phone"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition" />
                    </div>

                    <div>
                        <label for="edit_password"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('messages.New Password') }}
                        </label>
                        <input type="password" name="password" id="edit_password"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition" />
                    </div>

                    <div>
                        <label for="edit_password_confirmation"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('messages.Confirm New Password') }}
                        </label>
                        <input type="password" name="password_confirmation" id="edit_password_confirmation"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition" />
                    </div>


                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <button type="button" onclick="closeEditUserModal()"
                            class="px-4 py-2 rounded-lg border text-sm font-medium text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                            {{ __('messages.Cancel') }}
                        </button>
                        <button type="submit"
                            class="px-5 py-2 rounded-lg bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 transition">
                            {{ __('messages.Update Admin') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
        function openUserModal() {
            document.getElementById('userModal').classList.remove('hidden');
            document.getElementById('userModal').classList.add('flex');
        }

        function closeUserModal() {
            document.getElementById('userModal').classList.add('hidden');
            document.getElementById('userModal').classList.remove('flex');
        }

        function openEditAdminModal(id, name, phone, email) {
            const modal = document.getElementById('editUserModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            document.getElementById('edit_name').value = name;
            document.getElementById('edit_phone').value = phone;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_password').value = ''; // clear
            document.getElementById('edit_password_confirmation').value = ''; // clear

            document.getElementById('editUserForm').action = `/admins/${id}`;
        }



        function closeEditUserModal() {
            const modal = document.getElementById('editUserModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
@endsection
