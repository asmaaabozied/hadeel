@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto py-6 px-4">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">{{ __('messages.general_settings') }}</h2>

        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                {{ session('success') }}
            </div>
    @endif

    <!-- Add New Button -->
        <div class="flex justify-end mb-4">
            <button onclick="openModal('createSettingModal')"
                    class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 text-sm">
                + {{ __('messages.add_setting') }}
            </button>
        </div>

        <!-- Settings Table -->
        <!-- Settings Cards -->
        <div class="space-y-4">
            @foreach ($settings as $setting)
                <div class="bg-white rounded shadow">
                    <button type="button"
                            class="w-full flex justify-between items-center px-4 py-3 text-left text-sm font-medium text-gray-800 bg-gray-100 rounded-t"
                            onclick="toggleCard(this)">
                        <span>{{ $setting->group->name ?? '-' }}</span>
                        <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div class="hidden px-4 py-4 border-t" data-card-body>
                        @foreach($setting->banks as $bank)
                            <p><strong>{{ __('messages.bank_name') }}:</strong> {{ $bank->bank_name ?? '' }}</p>
                            <p><strong>{{ __('messages.iban') }}:</strong><span
                                > {{ $bank->iban ?? '' }}</span>
                                @endforeach

                            </p>

                            <p><strong>{{ __('messages.message') }}:</strong> {{ $setting->message }}</p>
                            <div class="mt-4 flex space-x-2">
                                <button class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-xs"
                                        data-id="{{ $setting->id }}"
                                        data-bank="{{ $setting->banks }}"
                                        data-message="{{ $setting->message }}"
                                        data-group-id="{{ $setting->group_id }}"
                                        onclick="handleEditButtonClick(this)">
                                    {{ __('messages.edit') }}
                                </button>

                                <form method="POST" action="{{ route('general_settings.delete', $setting->id) }}"
                                      onsubmit="return confirm('{{ __('messages.sure_delete') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs">
                                        {{ __('messages.delete') }}
                                    </button>
                                </form>
                            </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>

    <!-- Create Modal -->
    <div id="createSettingModal" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">
        <div class="bg-white w-full max-w-md p-6 rounded shadow">
            <h3 class="text-lg font-bold mb-4">{{ __('messages.add_setting') }}</h3>
            <form method="POST" action="{{ route('general_settings.store') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.group_name') }}</label>
                    <select name="group_id" required class="w-full border-gray-300 rounded px-3 py-2 text-sm shadow-sm">
                        <option value="">{{ __('messages.select_group') }}</option>
                        @foreach ($groups as $group)
                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                        @endforeach
                    </select>

                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.message') }}</label>
                    <textarea name="message" rows="3"
                              class="w-full border-gray-300 rounded px-3 py-2 text-sm shadow-sm"></textarea>
                </div>


                <div class="input-group row inputs-container" id="inputs-container">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.bank_name') }}</label>

                        <input type="text" name="bank_name[]"
                               class="w-full border-gray-300 rounded px-3 py-2 text-sm shadow-sm"
                               placeholder="Enter Bank Name"/>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.iban') }}</label>

                        <input type="text" name="iban[]"
                               class="w-full border-gray-300 rounded px-3 py-2 text-sm shadow-sm"
                               placeholder="Enter iban"/>

                    </div>
                    <button type="button" class="remove-btn">Delete</button>
                    <hr>
                    <br>
                </div>

                <button type="button" class="add-btn add-input" id="add-input">+ Add More</button>

                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeModal('createSettingModal')"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-3 py-1 rounded text-sm">
                        {{ __('messages.cancel') }}
                    </button>
                    <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-1 rounded text-sm">
                        {{ __('messages.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editSettingModal" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">
        <div class="bg-white w-full max-w-md p-6 rounded shadow">
            <h3 class="text-lg font-bold mb-4">{{ __('messages.edit_setting') }}</h3>
            <form method="POST" action="{{ route('general_settings.update') }}">
                @csrf
                <input type="hidden" name="id" id="edit_id">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.group_name') }}</label>
                    <select name="group_id" id="edit_group_id"
                            class="w-full border-gray-300 rounded px-3 py-2 text-sm shadow-sm">
                        <option value="">{{ __('messages.select_group') }}</option>
                        @foreach ($groups as $group)
                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                        @endforeach
                    </select>

                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.message') }}</label>
                    <textarea name="message" id="edit_message" rows="3"
                              class="w-full border-gray-300 rounded px-3 py-2 text-sm shadow-sm"></textarea>
                </div>


                <div class="input-group row inputs-container" id="inputs-container-edit">

                </div>
                <button type="button" class="add-btn add-input" id="add-input">+ Add More</button>

                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeModal('editSettingModal')"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-3 py-1 rounded text-sm">
                        {{ __('messages.cancel') }}
                    </button>
                    <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-1 rounded text-sm">
                        {{ __('messages.save_changes') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- JavaScript for Modal Control -->
    <script>
        function toggleCard(button) {
            const body = button.nextElementSibling;
            const icon = button.querySelector('svg');
            if (body.classList.contains('hidden')) {
                body.classList.remove('hidden');
                icon.classList.add('rotate-180');
            } else {
                body.classList.add('hidden');
                icon.classList.remove('rotate-180');
            }
        }
    </script>

    <script>
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
            document.getElementById(id).classList.add('flex');
        }

        function closeModal(id) {
            document.getElementById(id).classList.remove('flex');
            document.getElementById(id).classList.add('hidden');
        }

        function handleEditButtonClick(button) {
            const id = button.getAttribute('data-id');
            const banks = button.getAttribute('data-bank');
            const message = button.getAttribute('data-message');
            // const bank_name = button.getAttribute('data-bank_name');
            // const iban = button.getAttribute('data-iban');
            const groupId = button.getAttribute('data-group-id');
            console.log('banks', banks);
            document.getElementById('edit_id').value = id;
            // document.getElementById('edit_account_number').value = accountNumber;
            document.getElementById('edit_message').value = message;
            // document.getElementById('edit_bank_name').value = bank_name;
            // document.getElementById('edit_iban').value = iban;

            const select = document.getElementById('edit_group_id');
            if (select) {
                [...select.options].forEach(option => {
                    option.selected = option.value == groupId;
                });
            }


            let bankArray = JSON.parse(banks);

            let html = '';
            bankArray.forEach(bank => {
                const newInput = `
      <div class="input-group"> <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bank Name</label>

        <input type="text" name="bank_name[]" placeholder="Enter Bank Name" value='${bank.bank_name}' class="w-full border-gray-300 rounded px-3 py-2 text-sm shadow-sm" />
      </div> <div class="mb-4">

                        <label class="block text-sm font-medium text-gray-700 mb-1">Iban</label>
<input type="text" name="iban[]" placeholder="Enter Iban" value='${bank.iban}' class="w-full border-gray-300 rounded px-3 py-2 text-sm shadow-sm" />
      </div>  <button type="button" class="remove-btn">Delete</button>
     <hr><br> </div>
    `;
                // html += `<input type="text" name="bank_name[]" value='${bank.bank_name}'>`;
                html += newInput;
            });

            document.getElementById('inputs-container-edit').innerHTML = html;
            openModal('editSettingModal');
        }


        $(document).ready(function () {
            // Add new input field
            $('.add-input').on('click', function () {
                const newInput = `
      <div class="input-group"> <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bank Name</label>

        <input type="text" name="bank_name[]" placeholder="Enter Bank Name" class="w-full border-gray-300 rounded px-3 py-2 text-sm shadow-sm" />
       </div> <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Iban</label>

<input type="text" name="iban[]" placeholder="Enter Iban" class="w-full border-gray-300 rounded px-3 py-2 text-sm shadow-sm" />
       </div> <button type="button" class="remove-btn">Delete</button>
     <hr><br> </div>
    `;
                $('.inputs-container').append(newInput);
            });

            // Remove input field (delegation for dynamically added buttons)
            $('.inputs-container').on('click', '.remove-btn', function () {
                $(this).closest('.input-group').remove();
            });
        });
    </script>
@endsection
