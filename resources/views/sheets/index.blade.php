@extends('layouts.app')

<style>
    .flash-highlight {
        background-color: #ffeaa7;
        /* light yellow */
        transition: background-color 0.5s ease;
    }
</style>

@section('content')
    <div class="max-w-7xl mx-auto px-6 py-8">

        @if (session('success'))
            <div id="success-alert"
                 class="mb-4 p-4 rounded-lg bg-emerald-100 text-emerald-800 shadow transition duration-300">
                {{ session('success') }}
            </div>

            @if (session('error'))
                <div id="error-alert"
                     class="mb-4 p-4 rounded-lg bg-rose-100 text-rose-800 shadow transition duration-300">
                    {{ session('error') }}
                </div>

                <script>
                    setTimeout(() => {
                        const alert = document.getElementById('error-alert');
                        if (alert) {
                            alert.classList.add('opacity-0', 'translate-y-2');
                            setTimeout(() => alert.remove(), 300);
                        }
                    }, 4000); // 4 seconds
                </script>
            @endif


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

        @if (session('error'))
            <div id="error-alert" class="mb-4 p-4 rounded-lg bg-rose-100 text-rose-800 shadow transition duration-300">
                {{ session('error') }}
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


        <h2 class="text-2xl font-bold text-gray-800 mb-4">
            {{ __('messages.Weekly Sheets for Group:') }} {{ $group->name }}
        </h2>

        <a href="{{ route('groups.index') }}" class="text-indigo-600 hover:underline mb-6 inline-block">←
            {{ __('messages.Back to Groups') }}</a>

        @if ($sheets->isEmpty())
            <p class="text-gray-500">{{ __('messages.No sheets found for this group yet.') }}</p>
        @else
            <div class="space-y-6">
                <div class="flex items-center gap-4 mb-4">
                    <button onclick="openUserModal()"
                            class="bg-indigo-600 text-white text-sm font-medium px-4 py-2 rounded hover:bg-indigo-700 transition">
                        + {{ __('messages.Add User') }}
                    </button>


                    <button onclick="openAddSheetModal()"
                            class="bg-green-600 text-white text-sm font-medium px-4 py-2 rounded hover:bg-green-700 transition">
                        + {{ __('messages.Add Weekly Sheet Manually') }}
                    </button>

                </div>

                <div class="mb-6 flex justify-end">
                    <div class="relative w-full max-w-md">
                        <span
                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/>
                            </svg>
                        </span>
                        <input type="text" id="sheetSearch" placeholder="{{ __('messages.Search') }}"
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-right placeholder:text-right"
                               oninput="filterSheetRows()">

                    </div>
                </div>


                @foreach ($sheets as $sheet)
                    <div class="border rounded-lg p-4 bg-white shadow">
                        <div class="flex justify-between items-center">
                            <div>
                                <span class="font-medium text-gray-700">{{ __('messages.Week:') }}</span>
                                {{ \Carbon\Carbon::parse($sheet->week_start_date)->format('M d, Y') }} —
                                {{ \Carbon\Carbon::parse($sheet->week_end_date)->format('M d, Y') }}
                            </div>
                            <div>
                                <button onclick="toggleSheet({{ $sheet->id }})" class="text-indigo-600 hover:underline">
                                    {{ __('messages.Show Sheet') }}
                                </button>

                            </div>
                        </div>


                        <div id="sheet-{{ $sheet->id }}" class="mt-4 hidden">
                            <div id="sheet-content-{{ $sheet->id }}" class="text-gray-400 text-sm italic">
                                {{ __('messages.Loading...') }}
                            </div>
                        </div>


                    </div>
                @endforeach
            </div>
        @endif

        <div id="userModal"
             class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm transition-opacity duration-200">
            <div
                class="bg-white dark:bg-gray-900 w-full max-w-lg mx-auto rounded-2xl shadow-2xl p-6 relative animate-fadeIn">
                <button onclick="closeUserModal()"
                        class="absolute top-4 right-4 text-gray-400 hover:text-black dark:hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                <h3 class="text-xl font-semibold text-black dark:text-white mb-6 text-center">
                    {{ __('messages.Add New User') }}</h3>

                <form method="POST" action="{{ route('users.create', $group->id) }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('messages.Name') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" required
                               class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"/>
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('messages.Phone') }}
                        </label>
                        <input type="text" name="phone" id="phone"
                               class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"/>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <button type="button" onclick="closeUserModal()"
                                class="px-4 py-2 rounded-lg border text-sm font-medium text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                            {{ __('messages.Cancel') }}
                        </button>
                        <button type="submit"
                                class="px-5 py-2 rounded-lg bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 transition">
                            {{ __('messages.Save User') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Sheet Modal -->
    <div id="addSheetModal"
         class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm transition-opacity duration-200">
        <div class="bg-white w-full max-w-md mx-auto rounded-xl shadow-xl p-6 relative animate-fadeIn">
            <button onclick="closeAddSheetModal()"
                    class="absolute top-4 right-4 text-gray-500 hover:text-black transition">
                ✕
            </button>

            <h3 class="text-xl font-semibold text-center text-gray-800 mb-4">
                {{ __('messages.Add Weekly Sheet') }}
            </h3>

            <form method="POST" action="{{ route('sheets.store') }}">
                @csrf
                <input type="hidden" name="group_id" value="{{ $group->id }}">

                <div class="mb-4">
                    <label for="week_start_date" class="block text-sm font-medium text-gray-700">
                        {{ __('messages.Start Date') }}
                    </label>
                    <input type="date" name="week_start_date" id="week_start_date"
                           class="w-full rounded px-3 py-2 border border-gray-300" required>
                </div>

                <div class="mb-4">
                    <label for="week_end_date" class="block text-sm font-medium text-gray-700">
                        {{ __('messages.End Date') }}
                    </label>
                    <input type="date" name="week_end_date" id="week_end_date"
                           class="w-full rounded px-3 py-2 border border-gray-300" required>
                </div>

                <div class="text-right">
                    <button type="submit"
                            class="bg-green-600 text-white text-sm font-medium px-4 py-2 rounded hover:bg-green-700 transition">
                        {{ __('messages.Create Sheet') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function openAddSheetModal() {
            document.getElementById('addSheetModal').classList.remove('hidden');
            document.getElementById('addSheetModal').classList.add('flex');
        }

        function closeAddSheetModal() {
            document.getElementById('addSheetModal').classList.add('hidden');
            document.getElementById('addSheetModal').classList.remove('flex');
        }
    </script>

    <script>
        function updateNoteType(sheetId, userId, selectedType) {
            fetch(`/sheets/${sheetId}/users/${userId}/note-type`, {
                method: 'POST', // this must be POST
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    note_type: selectedType
                })
            })

                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        alert(data.message || 'Failed to update note type');
                    }
                });
        }
    </script>




    <script>

        function changeMerged(sheetId, prod, con) {

            console.log("asmaa");

            fetch(`/sheets/${sheetId}/addmerged`, {
                method: 'POST', // this must be POST
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({})
            })

                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // alert(data.message || ' Merged Successfully');


                        Swal.fire({
                            title: 'Success!',
                            text: 'Merged completed Successfully.',
                            icon: 'success',
                            showCancelButton: true,
                            confirmButtonText: 'OK',
                            cancelButtonText: 'Cancel'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload(); // Reload the page
                            }
                        });

                    }
                });


            let product = prod;
            let consumation = con;
            const merged = document.getElementById(`merged`);
            const newRow = `
        <tr id="merged">
            <td></td>
                <td class="px-2 py-1 text-right text-sm font-semibold text-gray-600">
            All </td>
            <td class="px-4 py-2">${product} </td>
            <td class="px-4 py-2">${consumation} </td>
            <td> </td>
            <td> </td>
                <td class="px-4 py-2 text-right">
Merged </td>
        </tr>
    `;

            if ($('#myTable tr#merged').length === 0) {

                $('#myTable tbody').append(newRow);
            }
        }

        @auth
        const currentUserId = {{ auth()->id() }};
        @else
        console.log("not authenticated");
        console.log("*************************************************");
        @endauth
    </script>




    <script>
        function toggleSheet(sheetId) {
            const container = document.getElementById(`sheet-${sheetId}`);
            const content = document.getElementById(`sheet-content-${sheetId}`);

            // Toggle visibility
            container.classList.toggle('hidden');

            // If already loaded, skip
            if (content.dataset.loaded === 'true') return;

            content.innerHTML = '⏳ Loading...';

            fetch(`/sheets/${sheetId}/users-table`)
                .then(res => res.text())
                .then(html => {
                    content.innerHTML = html;
                    content.dataset.loaded = 'true';
                })
                .catch(err => {
                    content.innerHTML = '❌ Failed to load data.';
                    console.error('Error loading sheet users:', err);
                });
        }
    </script>


    <script>
        function openUserModal() {
            document.getElementById('userModal').classList.remove('hidden');
            document.getElementById('userModal').classList.add('flex');
        }

        function closeUserModal() {
            document.getElementById('userModal').classList.add('hidden');
            document.getElementById('userModal').classList.remove('flex');
        }

        $(document).ready(function () {
            $('.add-user-to-sheet-form').on('submit', function (e) {
                e.preventDefault();

                let form = $(this);
                let sheetId = form.data('sheet-id');
                let formData = form.serialize();

                $.ajax({
                    url: '{{ route('sheets.users.store', ':id') }}'.replace(':id', sheetId),
                    method: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function (res) {
                        $('#ajax-message-' + sheetId).html('<span class="text-green-600">✅ ' +
                            res.message + '</span>');
                        form.trigger('reset');

                        const u = res.user;
                        const rowId = `row-${sheetId}-${u.id}`;

                        // Append to table
                        $('#sheet-' + sheetId + ' table tbody').append(`
                            <tr id="${rowId}" class="border-t">
                                <td class="px-4 py-2">${u.name}</td>
                                <td class="px-4 py-2">${u.phone}</td>

                                <td id="production-${sheetId}-${u.id}" data-field="production" data-sheet="${sheetId}" data-user="${u.id}" class="px-4 py-2">
                                    <span class="editable cursor-pointer" onclick="updateField(${sheetId}, ${u.id}, 'production')">
                                        ${u.production}
                                    </span>
                                </td>

                                <td id="consumption-${sheetId}-${u.id}" data-field="consumption" data-sheet="${sheetId}" data-user="${u.id}" class="px-4 py-2">
                                    <span class="editable cursor-pointer" onclick="updateField(${sheetId}, ${u.id}, 'consumption')">
                                        ${u.consumption}
                                    </span>
                                </td>

                                <td class="px-4 py-2">${u.note || ''}</td>

                                <td class="px-4 py-2 space-x-2">
                                    <button onclick="enableInlineEdit(document.querySelector(\`[data-sheet='${sheetId}'][data-user='${u.id}'][data-field='production'] span\`))"
                                            class="bg-indigo-100 text-indigo-700 hover:bg-indigo-200 font-medium text-xs px-3 py-1 rounded">
                                        Edit Production
                                    </button>

                                    <button onclick="enableInlineEdit(document.querySelector(\`[data-sheet='${sheetId}'][data-user='${u.id}'][data-field='consumption'] span\`))"
                                            class="bg-indigo-100 text-indigo-700 hover:bg-indigo-200 font-medium text-xs px-3 py-1 rounded">
                                        Edit Consumption
                                    </button>
                                </td>
                            </tr>
                        `);
                    },
                    error: function (xhr) {
                        let errors = xhr.responseJSON.errors;
                        let errorMessages = Object.values(errors).map(e =>
                            `<div class="text-red-600"> ${e}</div>`).join('');
                        $('#ajax-message-' + sheetId).html(errorMessages);
                    }
                });
            });
        });
    </script>



    <script>
        function updateField(sheetId, userId, field) {
            fetch(`/sheets/${sheetId}/users/${userId}/increment`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    field: field
                })
            })
                .then(response => {
                    if (!response.ok) throw new Error('Server Error');
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        const cell = document.getElementById(`${field}-cell-${sheetId}-${userId}`);
                        if (cell) {
                            const span = cell.querySelector('span');
                            if (span) {
                                span.textContent = parseInt(data.value);
                            }
                        }

                        updateTotals(sheetId); // 👈 only recalculate for this sheet
                    } else {
                        showToast(data.error || 'Something went wrong');
                    }
                })
                .catch(err => {
                    console.error('AJAX Error:', err);
                    showToast('AJAX failed: ' + err.message);
                });
        }


        function updateTotals(sheetId) {
            let totalProduction = 0;
            let totalConsumption = 0;

            document.querySelectorAll(`[id^="production-cell-${sheetId}-"]`).forEach(cell => {
                const span = cell.querySelector('span');
                if (span) {
                    const value = parseFloat(span.textContent);
                    if (!isNaN(value)) totalProduction += value;
                }
            });

            document.querySelectorAll(`[id^="consumption-cell-${sheetId}-"]`).forEach(cell => {
                const span = cell.querySelector('span');
                if (span) {
                    const value = parseFloat(span.textContent);
                    if (!isNaN(value)) totalConsumption += value;
                }
            });

            const prodEl = document.getElementById(`total-production-${sheetId}`);
            const consEl = document.getElementById(`total-consumption-${sheetId}`);

            if (prodEl) prodEl.textContent = Math.round(totalProduction);
            if (consEl) consEl.textContent = Math.round(totalConsumption);

            // Update Deportation Button
            const deportBtn = document.getElementById(`deportation-button-${sheetId}`);
            const hasDeportation = deportBtn?.dataset?.deported === 'true'; // optional: manage via JS or server

            if (deportBtn && !hasDeportation) {
                if (Math.round(totalProduction) === Math.round(totalConsumption)) {
                    deportBtn.disabled = false;
                    deportBtn.textContent = '{{ __('messages.Deportation') }}';
                    deportBtn.className =
                        'px-3 py-1 text-sm font-medium rounded transition bg-green-600 text-white hover:bg-green-700';
                } else {
                    deportBtn.disabled = true;
                    deportBtn.textContent = '{{ __('messages.Deportation') }}';
                    deportBtn.className =
                        'px-3 py-1 text-sm font-medium rounded transition bg-gray-300 text-gray-500 cursor-not-allowed';
                }
            }
        }


        function updateNote(sheetId, userId) {
            const cell = document.getElementById(`note-${sheetId}-${userId}`);
            const span = cell.querySelector('span');
            const currentNote = span.textContent.trim();

            // Create input element
            const input = document.createElement('input');
            input.type = 'number';
            input.step = '0.01';
            input.placeholder = 'e.g. 0.5 or -0.25';
            input.value = currentNote;
            input.className = 'w-24 text-sm px-2 border border-gray-300 rounded';

            // Replace span with input
            cell.innerHTML = '';
            cell.appendChild(input);
            input.focus();

            // Save on blur
            input.addEventListener('blur', () => {
                const newNote = input.value;

                if (newNote === currentNote) {
                    cell.innerHTML =
                        `<span class="editable cursor-pointer" onclick="updateNote(${sheetId}, ${userId})">${currentNote}</span>`;
                    return;
                }

                fetch(`/sheets/${sheetId}/users/${userId}/update-note`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        note: newNote
                    })
                })
                    .then(res => {
                        if (!res.ok) throw new Error('Server error');
                        return res.json();
                    })
                    .then(data => {
                        if (data.success) {
                            cell.innerHTML =
                                `<span class="editable cursor-pointer" onclick="updateNote(${sheetId}, ${userId})">${newNote}</span>`;
                        } else {
                            showToast(data.error || 'Failed to update');
                            cell.innerHTML =
                                `<span class="editable cursor-pointer" onclick="updateNote(${sheetId}, ${userId})">${currentNote}</span>`;
                        }
                    })
                    .catch(err => {
                        showToast('AJAX Error: ' + err.message);
                        cell.innerHTML =
                            `<span class="editable cursor-pointer" onclick="updateNote(${sheetId}, ${userId})">${currentNote}</span>`;
                    });
            });

            input.addEventListener('keydown', e => {
                if (e.key === 'Enter') input.blur();
            });
        }


        // function undoChange(sheetId) {
        //     fetch(`/sheets/${sheetId}/users/undo`, {
        //             method: 'POST',
        //             headers: {
        //                 'X-CSRF-TOKEN': '{{ csrf_token() }}'
        //             }
        //         })
        //         .then(res => res.json())
        //         .then(data => {
        //             if (data.success) {
        //                 const {
        //                     field,
        //                     user_id,
        //                     value
        //                 } = data;
        //                 const cell = document.getElementById(`${field}-cell-${sheetId}-${user_id}`);
        //                 if (cell) {
        //                     const span = cell.querySelector('span');
        //                     if (span) {
        //                         span.textContent = parseInt(value);
        //                     }
        //                 }
        //                 updateTotals(sheetId);
        //             } else {
        //                 showToast(data.error || 'Nothing to undo');
        //             }
        //         });
        // }

        // function redoChange(sheetId) {
        //     fetch(`/sheets/${sheetId}/users/redo`, {
        //             method: 'POST',
        //             headers: {
        //                 'X-CSRF-TOKEN': '{{ csrf_token() }}'
        //             }
        //         })
        //         .then(res => res.json())
        //         .then(data => {
        //             if (data.success) {
        //                 const {
        //                     field,
        //                     user_id,
        //                     value
        //                 } = data;
        //                 const cell = document.getElementById(`${field}-cell-${sheetId}-${user_id}`);
        //                 if (cell) {
        //                     const span = cell.querySelector('span');
        //                     if (span) {
        //                         span.textContent = parseInt(value);
        //                     }
        //                 }
        //                 updateTotals(sheetId);
        //             } else {
        //                 showToast(data.error || 'Nothing to redo');
        //             }
        //         });
        // }


        function undoChange(sheetId) {
            fetch(`/sheets/${sheetId}/users/undo`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const {
                            field,
                            user_id,
                            value
                        } = data;
                        const cell = document.getElementById(`${field}-cell-${sheetId}-${user_id}`);
                        if (cell) {
                            const span = cell.querySelector('span');
                            if (span) {
                                span.textContent = parseInt(value);
                            }

                            // Flash effect
                            cell.classList.add('flash-highlight');
                            setTimeout(() => {
                                cell.classList.remove('flash-highlight');
                            }, 2000);

                            // Scroll to row
                            cell.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });
                        }
                        updateTotals(sheetId);
                    } else {
                        showToast(data.error || 'Nothing to undo');
                    }
                });
        }

        function redoChange(sheetId) {
            fetch(`/sheets/${sheetId}/users/redo`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const {
                            field,
                            user_id,
                            value
                        } = data;
                        const cell = document.getElementById(`${field}-cell-${sheetId}-${user_id}`);
                        if (cell) {
                            const span = cell.querySelector('span');
                            if (span) {
                                span.textContent = parseInt(value);
                            }

                            // Flash effect
                            cell.classList.add('flash-highlight');
                            setTimeout(() => {
                                cell.classList.remove('flash-highlight');
                            }, 2000);

                            // Scroll to row
                            cell.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });
                        }
                        updateTotals(sheetId);
                    } else {
                        showToast(data.error || 'Nothing to redo');
                    }
                });
        }
    </script>

    <script>
        // function enableInlineEdit(span) {
        //     const value = span.textContent.trim(); // preserve text
        //     const parent = span.parentElement;

        //     const input = document.createElement('input');
        //     input.type = 'text'; // allow any text
        //     input.value = value;
        //     input.classList.add('border', 'px-2', 'py-1', 'rounded', 'w-32');

        //     input.onblur = () => saveInlineEdit(input, span);
        //     input.onkeydown = (e) => {
        //         if (e.key === 'Enter') input.blur();
        //         if (e.key === 'Escape') {
        //             span.style.display = 'inline';
        //             input.remove();
        //         }
        //     };

        //     span.style.display = 'none';
        //     parent.appendChild(input);
        //     input.focus();
        // }

        function enableInlineEdit(span) {
            const parent = span.parentElement;

            // Prevent double-editing if input already exists
            if (parent.querySelector('input')) return;

            const value = span.textContent.trim();
            const input = document.createElement('input');
            input.type = 'text';
            input.value = value;
            input.classList.add('border', 'px-2', 'py-1', 'rounded', 'w-32', 'text-sm');

            // Save on blur
            input.onblur = () => saveInlineEdit(input, span);

            // Handle Enter (save) and Escape (cancel)
            input.onkeydown = (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    input.blur();
                } else if (e.key === 'Escape') {
                    input.remove();
                    span.style.display = 'inline';
                }
            };

            // Hide the span and show the input
            span.style.display = 'none';
            parent.appendChild(input);
            input.focus();
            input.select();
        }


        function saveInlineEdit(input, span) {
            const newValue = input.value.trim();
            const td = span.parentElement;

            const field = td.dataset.field;
            const sheetId = td.dataset.sheet;
            const userId = td.dataset.user;

            fetch(`/sheets/${sheetId}/users/${userId}/manual-update`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    [field]: newValue
                })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        span.textContent = data[field];

                        // Only update totals if the edited field affects totals
                        if (field === 'production' || field === 'consumption') {
                            if (typeof updateTotals === 'function') {
                                updateTotals(sheetId);
                            }
                        }
                    } else {
                        showToast(data.error || 'Update failed');
                    }
                })
                .finally(() => {
                    input.remove();
                    span.style.display = 'inline';
                });
        }


        function showToast(message, type = 'error') {
            const container = document.getElementById('toast-container');

            const toast = document.createElement('div');
            toast.className = `
        max-w-sm px-5 py-3 rounded-lg shadow-lg text-base text-white flex items-center gap-2
        ${type === 'success' ? 'bg-green-600' : 'bg-red-600'}
    `;

            toast.innerHTML = `
        <span>${message}</span>
    `;

            container.appendChild(toast);

            setTimeout(() => {
                toast.classList.add('opacity-0', 'transition-opacity', 'duration-500');
                setTimeout(() => toast.remove(), 500);
            }, 3000);
        }
    </script>


    <script>
        function filterSheetRows() {
            const query = document.getElementById('sheetSearch').value.toLowerCase();

            document.querySelectorAll('[id^="sheet-"]').forEach(sheet => {
                sheet.querySelectorAll('tbody tr').forEach(row => {
                    // Skip total row
                    if (row.classList.contains('bg-indigo-50')) return;

                    const nameCell = row.querySelector('[data-field="name"]');
                    const phoneCell = row.querySelector('[data-field="phone"]');

                    const name = nameCell ? nameCell.innerText.toLowerCase() : '';
                    const phone = phoneCell ? phoneCell.innerText.toLowerCase() : '';

                    const matches = name.includes(query) || phone.includes(query);

                    row.style.display = matches ? '' : 'none';
                });
            });
        }
    </script>


    <script>
        function sortTable(th, field) {
            const table = th.closest('table');
            const tbody = table.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr:not(.bg-indigo-50)'));

            const isAsc = th.getAttribute('data-sort') === 'asc';
            th.setAttribute('data-sort', isAsc ? 'desc' : 'asc');
            th.querySelector('.sort-arrow').textContent = isAsc ? '▼' : '▲';

            const getCellValue = (row) => {
                if (field === 'name') {
                    return row.querySelector('[data-field="name"]')?.innerText.trim().toLowerCase() || '';
                }
                if (field === 'production') {
                    return parseFloat(row.querySelector('[data-field="production"]')?.innerText || '0');
                }
                if (field === 'consumption') {
                    return parseFloat(row.querySelector('[data-field="consumption"]')?.innerText || '0');
                }
                return '';
            };


            rows.sort((a, b) => {
                const valA = getCellValue(a);
                const valB = getCellValue(b);
                return isAsc ?
                    valA > valB ? 1 : -1 :
                    valA < valB ? 1 : -1;
            });

            // Re-append rows in new order
            rows.forEach(row => tbody.appendChild(row));

            // Move total row to bottom again
            const totalRow = table.querySelector('.bg-indigo-50');
            if (totalRow) tbody.appendChild(totalRow);
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Find the name <th> element
            const nameTh = document.querySelector('th[onclick*="sortTable"][onclick*="name"]');
            if (nameTh) {
                // Trigger one-time initial sort in ascending order
                nameTh.setAttribute('data-sort', 'asc'); // To make first toggle go "asc"
                sortTable(nameTh, 'name');
            }
        });
    </script>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script>
        function confirmDelete(sheetId, userId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This will remove the user from the sheet.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete!',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'bg-red-600 hover:bg-red-700 text-white font-medium px-4 py-2 rounded mr-2',
                    cancelButton: 'bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium px-4 py-2 rounded'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-user-${sheetId}-${userId}`).submit();
                }
            });
        }
    </script>



@endsection
