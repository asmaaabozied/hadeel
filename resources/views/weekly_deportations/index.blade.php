@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-8">
        <h2 class="text-3xl font-bold text-gray-800 dark:text-white mb-8">{{ __('messages.Weekly Deportations') }}</h2>

        @forelse($groupedDeportations as $sheetId => $group)
            @php $sheet = $group->first()->sheet; @endphp

            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm rounded-xl mb-8">
                <div
                    class="flex flex-wrap justify-between items-center px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                    <div>
                        <h3 class="text-lg font-semibold text-indigo-700 dark:text-indigo-400">
                            {{ __('messages.Week:') }}
                            {{ \Carbon\Carbon::parse($sheet->week_start_date)->format('M d, Y') }} —
                            {{ \Carbon\Carbon::parse($sheet->week_end_date)->format('M d, Y') }}
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ __('messages.Sheet ID:') }} #{{ $sheet->id }}
                        </p>
                    </div>

                    <button onclick="document.getElementById('deportation-{{ $sheetId }}').classList.toggle('hidden')"
                        class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 transition">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                        {{ __('messages.Toggle Sheet') }}
                    </button>
                </div>

                <div id="deportation-{{ $sheetId }}" class="hidden overflow-x-auto">
                    <table dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}"
                        class="min-w-full text-sm text-gray-700 dark:text-gray-200">
                        <thead
                            class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-xs uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-3">{{ __('messages.Actions') }}</th>
                                <th class="px-6 py-3 cursor-pointer" onclick="sortTable(this, {{ $sheetId }}, 1)">
                                    {{ __('messages.Name') }} <span class="sort-arrow">▲</span>
                                </th>
                                <th class="px-6 py-3 cursor-pointer" onclick="sortTable(this, {{ $sheetId }}, 2)">
                                    {{ __('messages.Phone') }} <span class="sort-arrow">▲</span>
                                </th>
                                <th class="px-6 py-3 cursor-pointer" onclick="sortTable(this, {{ $sheetId }}, 3)">
                                    {{ __('messages.Production') }} <span class="sort-arrow">▲</span>
                                </th>
                                <th class="px-6 py-3 cursor-pointer" onclick="sortTable(this, {{ $sheetId }}, 4)">
                                    {{ __('messages.Consumption') }} <span class="sort-arrow">▲</span>
                                </th>
                                <th class="px-6 py-3 cursor-pointer" onclick="sortTable(this, {{ $sheetId }}, 5)">
                                    {{ __('messages.Deportation') }} <span class="sort-arrow">▲</span>
                                </th>
                                <th class="px-6 py-3 cursor-pointer" onclick="sortTable(this, {{ $sheetId }}, 6)">
                                    {{ __('messages.Note') }} <span class="sort-arrow">▲</span>
                                </th>

                                <th class="px-6 py-3">{{ __('messages.Note Type') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach ($group as $deport)
                                @php
                                    $sheetId = $deport->sheet_id;
                                    $userId = $deport->user_id;
                                    $key = $sheetId . '-' . $userId;
                                @endphp
                                <tr id="deportation-row-{{ $deport->id }}"
                                    class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                    <td class="px-6 py-3">
                                        <button onclick="deleteDeportation({{ $deport->id }})"
                                            class="inline-flex items-center px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M6 8a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 0a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 0a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1z"
                                                    clip-rule="evenodd" />
                                                <path fill-rule="evenodd"
                                                    d="M4 3a1 1 0 000 2h12a1 1 0 100-2H4zm2 4a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            {{ __('messages.Delete') }}
                                        </button>
                                    </td>
                                    <td class="px-6 py-3 text-lg font-bold">{{ $deport->user->name }}</td>
                                    <td class="px-6 py-3">{{ $deport->user->phone }}</td>
                                    <td class="px-6 py-3">{{ (int) $deport->production }}</td>
                                    <td class="px-6 py-3">{{ (int) $deport->consumption }}</td>
                                    <td
                                        class="px-6 py-3 font-semibold {{ $deport->deportation_note < 0 ? 'text-red-600 dark:text-red-400' : 'text-indigo-600 dark:text-indigo-400' }}">
                                        {{ number_format($deport->deportation_note, 2) }}
                                    </td>
                                    <td
                                        class="px-6 py-3 {{ ($notes[$key] ?? 0) < 0 ? 'text-red-600 dark:text-red-400 font-semibold' : '' }}">
                                        {{ $notes[$key] ?? '—' }}
                                    </td>

                                    <td class="px-6 py-3">{{ $noteTypes[$key] ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <p class="text-gray-500 dark:text-gray-400 text-center">{{ __('messages.No deportation data available yet.') }}
            </p>
        @endforelse
    </div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function deleteDeportation(id) {
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
                fetch(`/deportations/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById(`deportation-row-${id}`).remove();
                            // Swal.fire('{{ __('messages.Deleted!') }}',
                            //     '{{ __('messages.Deportation has been deleted.') }}', 'success');
                        } else {
                            Swal.fire('{{ __('messages.Error') }}',
                                '{{ __('messages.Failed to delete deportation.') }}', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Delete error:', error);
                        Swal.fire('{{ __('messages.Error') }}', '{{ __('messages.An error occurred.') }}',
                            'error');
                    });
            }
        });
    }
</script>

<script>
    function sortTable(th, sheetId, columnIndex) {
        const table = th.closest('table');
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));

        const isAsc = th.getAttribute('data-sort') === 'asc';
        th.setAttribute('data-sort', isAsc ? 'desc' : 'asc');

        const arrow = th.querySelector('.sort-arrow');
        if (arrow) arrow.textContent = isAsc ? '▼' : '▲';

        rows.sort((a, b) => {
            const valA = a.children[columnIndex]?.innerText.trim() || '';
            const valB = b.children[columnIndex]?.innerText.trim() || '';

            const numA = parseFloat(valA.replace(',', ''));
            const numB = parseFloat(valB.replace(',', ''));

            const isNumeric = !isNaN(numA) && !isNaN(numB);

            if (isNumeric) {
                return isAsc ? numA - numB : numB - numA;
            }

            return isAsc ?
                valA.localeCompare(valB, 'ar') :
                valB.localeCompare(valA, 'ar');
        });

        rows.forEach(row => tbody.appendChild(row));
    }
</script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('[id^="deportation-"]').forEach(wrapper => {
            const sheetId = wrapper.id.replace('deportation-', '');
            const ths = wrapper.querySelectorAll('thead th');
            const nameTh = ths[1]; // index 1 = Name column

            if (nameTh) {
                nameTh.setAttribute('data-sort', 'asc'); // next toggle will be ascending
                sortTable(nameTh, sheetId, 1); // sort by Name
            }
        });
    });
</script>
