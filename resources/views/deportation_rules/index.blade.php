@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto py-8 px-4">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">{{ __('messages.deportation_rules_title') }}</h2>

        {{-- Success Message --}}
        @if (session('success'))
            <div class="mb-4 p-3 rounded bg-green-50 text-green-700 border border-green-200">
                {{ session('success') }}
            </div>
        @endif

        {{-- Add Rule Form --}}
        <div class="bg-white rounded shadow p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">{{ __('messages.add_rule') }}</h3>
            <form action="{{ route('admin.deportation_rules.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">{{ __('messages.min_production') }}</label>
                        <input type="number" name="min_production" required
                            class="w-full px-3 py-2 border border-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">{{ __('messages.max_production_optional') }}</label>
                        <input type="number" name="max_production"
                            class="w-full px-3 py-2 border border-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">{{ __('messages.adjustment') }}</label>
                        <input type="number" name="adjustment" required
                            class="w-full px-3 py-2 border border-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
                <div class="mt-5 text-right">
                    <button type="submit"
                        class="inline-block bg-indigo-600 text-white px-5 py-2 rounded hover:bg-indigo-700 transition">
                        {{ __('messages.add_rule_button') }}
                    </button>
                </div>
            </form>
        </div>


        {{-- Existing Rules Table --}}
        <div class="bg-white rounded-2xl shadow p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-5">{{ __('messages.current_rules') }}</h3>

            <div class="overflow-x-auto">
                <table class="w-full text-sm border border-gray-200 rounded-lg overflow-hidden">
                    <thead class="bg-indigo-50 text-gray-700 text-xs uppercase tracking-wide">
                        <tr>
                            <th class="px-4 py-3 text-left">{{ __('messages.min') }}</th>
                            <th class="px-4 py-3 text-left">{{ __('messages.max') }}</th>
                            <th class="px-4 py-3 text-left">{{ __('messages.adjustment') }}</th>
                            <th class="px-4 py-3 text-left">{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($rules as $rule)
                            <tr class="hover:bg-gray-50 transition">
                                <form method="POST" action="{{ route('admin.deportation_rules.update', $rule) }}">
                                    @csrf
                                    <td class="px-4 py-2 align-middle">
                                        <input type="number" name="min_production" value="{{ $rule->min_production }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-indigo-200 focus:outline-none" />
                                    </td>
                                    <td class="px-4 py-2 align-middle">
                                        <input type="number" name="max_production" value="{{ $rule->max_production }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-indigo-200 focus:outline-none" />
                                    </td>
                                    <td class="px-4 py-2 align-middle">
                                        <input type="number" name="adjustment" value="{{ $rule->adjustment }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-indigo-200 focus:outline-none" />
                                    </td>
                                    <td class="px-4 py-2 align-middle flex gap-2 items-center">
                                        <button type="submit"
                                            class="bg-blue-600 hover:bg-blue-700 text-white text-xs px-3 py-1.5 rounded-md shadow-sm transition">
                                            {{ __('messages.update') }}
                                        </button>
                                </form>
                                <form id="delete-rule-form-{{ $rule->id }}" method="POST"
                                    action="{{ route('admin.deportation_rules.destroy', $rule) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDelete('{{ $rule->id }}')"
                                        class="bg-red-500 hover:bg-red-600 text-white text-xs px-3 py-1.5 rounded-md shadow-sm transition">
                                        {{ __('messages.delete') }}
                                    </button>
                                </form>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>


    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function confirmDelete(ruleId) {
            Swal.fire({
                title: '{{ __('messages.sure_delete') }}',
                text: '{{ __('messages.confirm_delete_text') }}',
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: '{{ __('messages.cancel') }}',
                confirmButtonText: '{{ __('messages.confirm') }}',
                reverseButtons: true,
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'bg-red-600 hover:bg-red-700 text-white font-medium px-4 py-2 rounded mr-2',
                    cancelButton: 'bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium px-4 py-2 rounded'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-rule-form-' + ruleId).submit();
                }
            });
        }
    </script>
@endsection
