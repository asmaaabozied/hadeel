<style>
    .flash-highlight {
        background-color: #ffeaa7;
        /* light yellow */
        transition: background-color 0.5s ease;
    }
</style>


{{-- If no users, show message. Else, show table --}}
@if ($sheet->users->isEmpty())
    <p class="text-gray-500 italic">{{ __('messages.No user data in this sheet.') }}</p>
@else
    <div class="flex gap-2 mb-1" style=" position: fixed; z-index: 10;">
        <button class="bg-gray-200 text-gray-800 text-sm px-3 py-1 rounded hover:bg-gray-300 transition"
                onclick="undoChange({{ $sheet->id }})">{{ __('messages.Undo') }}</button>
        <button class="bg-gray-200 text-gray-800 text-sm px-3 py-1 rounded hover:bg-gray-300 transition"
                onclick="redoChange({{ $sheet->id }})">{{ __('messages.Redo') }}</button>

    </div>

    <div class="w-full overflow-x-auto">
        @php
            $hasDeportation = \App\Models\WeeklyDeportation::where('sheet_id', $sheet->id)->exists();
            $hasMerged = \App\Models\SheetUser::where('sheet_id', $sheet->id)->where('type','merged')->exists();
            $totalProduction = $sheet->users->sum(fn($u) => $u->pivot->production);
            $totalConsumption = $sheet->users->sum(fn($u) => $u->pivot->consumption);
            $isSheetEditable = !$hasDeportation;
        @endphp

        <br><br>
        <table class="min-w-full text-sm border border-gray-200 rounded mt-2" id="myTable"
               dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
            <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
            <tr>
                <th class="px-4 py-2">{{ __('messages.ID') }}</th>

                <th class="px-4 py-2 cursor-pointer text-right" dir="rtl"
                    onclick="sortTable(this, 'name')" data-sort="asc">
                    {{ __('messages.Name') }} <span class="sort-arrow">▲</span>
                </th>
                <th class="px-4 py-2 cursor-pointer" onclick="sortTable(this, 'production')"
                    data-sort="asc">
                    {{ __('messages.Production') }} <span class="sort-arrow">▲</span>
                </th>
                <th class="px-4 py-2 cursor-pointer" onclick="sortTable(this, 'consumption')"
                    data-sort="asc">
                    {{ __('messages.Consumption') }} <span class="sort-arrow">▲</span>
                </th>

                <th class="px-4 py-2">{{ __('messages.Phone') }}</th>
                <th class="px-4 py-2">{{ __('messages.ID') }}</th>
                <th class="px-4 py-2">{{ __('messages.Note') }}</th>
                <th class="px-4 py-2">{{ __('messages.Note Type') }}</th>
                <th class="px-4 py-2">{{ __('messages.Actions') }}</th>
            </tr>
            </thead>

            <tbody>
            @foreach ($sheet->users as $user)
                <tr class="border-t" id="row-{{ $sheet->id }}-{{ $user->id }}">
                    <td class="px-4 py-2 text-right text-lg font-bold" dir="rtl">
                        {{ $loop->iteration}}

                    </td>
                    <td class="px-4 py-2 text-right text-lg font-bold" dir="rtl" data-field="name"
                        data-sheet="{{ $sheet->id }}" data-user="{{ $user->id }}">
                                        <span
                                            class="editable {{ $isSheetEditable ? 'cursor-pointer' : 'opacity-50 cursor-not-allowed' }}"
                                            @if ($isSheetEditable) onclick="enableInlineEdit(this)" @endif
                                            dir="rtl">
                                            {{ $user->name }}
                                        </span>
                    </td>

                    {{-- Production --}}
                    <td id="production-cell-{{ $sheet->id }}-{{ $user->id }}"
                        data-field="production" data-sheet="{{ $sheet->id }}"
                        data-user="{{ $user->id }}" class="px-4 py-2">

                        @if($user->pivot->type=='merged')

                            <span
                                class="editable {{ $isSheetEditable ? 'cursor-pointer' : 'opacity-50 cursor-not-allowed' }}">
                                            {{ (int) $user->pivot->production }}



                                        </span>

                        @else
                            <span
                                class="editable {{ $isSheetEditable ? 'cursor-pointer' : 'opacity-50 cursor-not-allowed' }}"
                                @if ($isSheetEditable) onclick="updateField({{ $sheet->id }}, {{ $user->id }}, 'production')" @endif >
                                            {{ (int) $user->pivot->production }}



                                        </span>
                        @endif


                    </td>

                    {{-- Consumption --}}
                    <td id="consumption-cell-{{ $sheet->id }}-{{ $user->id }}"
                        data-field="consumption" data-sheet="{{ $sheet->id }}"
                        data-user="{{ $user->id }}" class="px-4 py-2">

                        @if($user->pivot->type=='merged')

                            <span
                                class="editable {{ $isSheetEditable ? 'cursor-pointer' : 'opacity-50 cursor-not-allowed' }}">
                                            {{ (int) $user->pivot->consumption }}
                                        </span>
                        @else


                            <span
                                class="editable {{ $isSheetEditable ? 'cursor-pointer' : 'opacity-50 cursor-not-allowed' }}"
                                @if ($isSheetEditable) onclick="updateField({{ $sheet->id }}, {{ $user->id }}, 'consumption')" @endif>
                                            {{ (int) $user->pivot->consumption }}
                                        </span>

                        @endif
                    </td>

                    <td class="px-4 py-2" data-field="phone" data-sheet="{{ $sheet->id }}"
                        data-user="{{ $user->id }}">
                                        <span
                                            class="editable {{ $isSheetEditable ? 'cursor-pointer' : 'opacity-50 cursor-not-allowed' }}"
                                            @if ($isSheetEditable) onclick="enableInlineEdit(this)" @endif>
                                            {{ $user->phone }}
                                        </span>
                    </td>

                    <td class="px-4 py-2 font-mono text-gray-600 dark:text-gray-300">
                        {{ $user->unique_identifier }}</td>

                    {{-- Note --}}
                    <td id="note-{{ $sheet->id }}-{{ $user->id }}" data-field="note"
                        data-sheet="{{ $sheet->id }}" data-user="{{ $user->id }}"
                        class="px-4 py-2">
                                        <span
                                            class="editable {{ $isSheetEditable ? 'cursor-pointer' : 'opacity-50 cursor-not-allowed' }}"
                                            @if ($isSheetEditable) onclick="enableInlineEdit(this)" @endif>
                                            {{ $user->pivot->note }}
                                        </span>

                    </td>


                    @php
                        $noteType = $user->pivot->note_type;
                        $noteOptions = ['فائض', 'تعويض', 'جائزة', 'ترحيل'];
                    @endphp

                    <td class="px-4 py-2 text-sm text-gray-700">
                        <div class="relative">
                            <select
                                onchange="updateNoteType({{ $sheet->id }}, {{ $user->id }}, this.value)"
                                class="w-full appearance-none bg-white border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md pl-3 pr-8 py-1.5 text-sm shadow-sm transition disabled:bg-gray-100 disabled:text-gray-400 disabled:cursor-not-allowed"
                                {{ !$isSheetEditable ? 'disabled' : '' }}>
                                <option value="">{{ __('messages.Select') }}
                                </option>
                                @foreach ($noteOptions as $option)
                                    <option value="{{ $option }}"
                                        {{ $option == $noteType ? 'selected' : '' }}>
                                        {{ $option }}
                                    </option>
                                @endforeach
                            </select>
                            <div
                                class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                     stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                    </td>


                    {{-- Actions --}}
                    <td class="px-4 py-2 space-y-1 space-x-2">
                        @if ($isSheetEditable)
                            @if($user->pivot->type=='merged')

                                <button
                                    class="bg-indigo-100 text-indigo-700 hover:bg-indigo-200 font-medium text-xs px-3 py-1 rounded">
                                    {{ __('messages.Edit Production') }}
                                </button>

                            @else
                                <button
                                    onclick="enableInlineEdit(document.querySelector(`[data-sheet='{{ $sheet->id }}'][data-user='{{ $user->id }}'][data-field='production'] span`))"
                                    class="bg-indigo-100 text-indigo-700 hover:bg-indigo-200 font-medium text-xs px-3 py-1 rounded">
                                    {{ __('messages.Edit Production') }}
                                </button>

                            @endif


                            @if($user->pivot->type=='merged')

                                <button
                                    class="bg-indigo-100 text-indigo-700 hover:bg-indigo-200 font-medium text-xs px-3 py-1 rounded">
                                    {{ __('messages.Edit Consumption') }}
                                </button>

                            @else
                                <button
                                    onclick="enableInlineEdit(document.querySelector(`[data-sheet='{{ $sheet->id }}'][data-user='{{ $user->id }}'][data-field='consumption'] span`))"
                                    class="bg-indigo-100 text-indigo-700 hover:bg-indigo-200 font-medium text-xs px-3 py-1 rounded">
                                    {{ __('messages.Edit Consumption') }}
                                </button>
                            @endif
                            @if($user->pivot->type=='merged')

                                <button
                                    class="bg-indigo-100 text-indigo-700 hover:bg-indigo-200 font-medium text-xs px-3 py-1 rounded">
                                    {{ __('messages.Edit Note') }}
                                </button>
                            @else
                                <button
                                    onclick="enableInlineEdit(document.querySelector(`[data-sheet='{{ $sheet->id }}'][data-user='{{ $user->id }}'][data-field='note'] span`))"
                                    class="bg-indigo-100 text-indigo-700 hover:bg-indigo-200 font-medium text-xs px-3 py-1 rounded">
                                    {{ __('messages.Edit Note') }}
                                </button>
                            @endif

                            <form id="delete-user-{{ $sheet->id }}-{{ $user->id }}"
                                  action="{{ route('sheets.users.remove', [$sheet->id, $user->id]) }}"
                                  method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                        onclick="confirmDelete('{{ $sheet->id }}', '{{ $user->id }}')"
                                        class="bg-indigo-100 text-indigo-700 hover:bg-indigo-200 font-medium text-xs px-3 py-1 rounded">
                                    {{ __('messages.Delete') }}
                                </button>
                            </form>
                        @else
                            <span class="text-gray-400 text-xs">{{ __('messages.Locked') }}</span>
                        @endif
                    </td>

                </tr>
            @endforeach

            @php
                $totalProduction = $sheet->users->sum(fn($u) => $u->pivot->production);
                $totalConsumption = $sheet->users->sum(fn($u) => $u->pivot->consumption);
                 $totalallProduction = $sheet->allusers->sum(fn($u) => $u->pivot->production);
                $totalallConsumption = $sheet->allusers->sum(fn($u) => $u->pivot->consumption);
            @endphp

            {{-- Total Row --}}
            <tr class="border-t bg-indigo-50 font-semibold text-gray-800">
                <td></td>
                <td class="px-2 py-1 text-right text-sm font-semibold text-gray-600">
                    {{ __('messages.Total') }}
                </td>
                <td class="px-4 py-2" id="total-production-{{ $sheet->id }}">
                    {{ (int) $totalProduction }}
                </td>
                <td class="px-4 py-2" id="total-consumption-{{ $sheet->id }}">
                    {{ (int) $totalConsumption }}
                </td>

                <td></td> {{-- empty for Note --}}
                <td></td> {{-- empty for Note Type --}}
                <td class="px-4 py-2 text-right" id="deportation-wrapper-{{ $sheet->id }}">
                    @php
                        $hasDeportation = \App\Models\WeeklyDeportation::where(
                            'sheet_id',
                            $sheet->id,
                        )->exists();
                    @endphp

                    <form id="deportation-form-{{ $sheet->id }}"
                          action="{{ route('sheets.deport', $sheet->id) }}" method="POST"
                          onsubmit="this.querySelector('button').disabled = true;">
                        @csrf
                        <button id="deportation-button-{{ $sheet->id }}" type="submit"
                                class="px-3 py-1 text-sm font-medium rounded transition
                                                                    {{ $hasDeportation
                                                                        ? 'bg-gray-300 text-gray-500 cursor-not-allowed'
                                                                        : ($totalProduction == $totalConsumption
                                                                            ? 'bg-green-600 text-white hover:bg-green-700'
                                                                            : 'bg-gray-300 text-gray-500 cursor-not-allowed') }}"
                            {{ $hasDeportation || $totalProduction != $totalConsumption ? 'disabled' : '' }}>
                            {{ $hasDeportation ? __('messages.Already Deported') : __('messages.Deportation') }}
                        </button>
                    </form>
                </td>


            </tr>
            @if($hasMerged)
            <tr class="border-t bg-indigo-50 font-semibold text-gray-800" id="merged">
                <td></td>
                <td class="px-2 py-1 text-right text-sm font-semibold text-gray-600">
                    All
                </td>
                <td class="px-4 py-2">
                    {{ (int) $totalallProduction }}
                </td>
                <td class="px-4 py-2">
                    {{ (int) $totalallConsumption }}
                </td>

                <td></td>  empty for Note
                <td></td>  empty for Note
                <td class="px-4 py-2 text-right">


                    Merged
                </td>
            </tr>
            @endif


            </tbody>
        </table>
        <br><br>
        <button class="btn btn-danger" onclick="changeMerged({{$sheet->id}},{{$totalallProduction}},{{$totalallConsumption}})">


            Merged


        </button>

    </div>
@endif

<script>


</script>
