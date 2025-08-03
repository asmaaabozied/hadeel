@extends('layout-guest')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gray-100 px-4 py-10">
        <div class="max-w-xl w-full bg-white rounded-xl shadow-lg p-6 space-y-6">

            @if(!empty($general->banks))
                @foreach($general->banks as $bank)
                    <div class="p-4 bg-yellow-50 border-l-4 border-yellow-400 text-yellow-800 rounded text-sm">

                        <p><strong>{{ __('messages.bank_name') }}:</strong> {{ $bank->bank_name }}</p>
                        <p><strong>{{ __('messages.iban') }}:</strong>
                            <span style="background-color: #7d9fef" > {{ $bank->iban }}</span>
                          <button  id="alertBtn" class="copy-btn btn btn-success" data-number="{{$bank->iban}}">Copy</button>


                        </p>
                    </div>

                @endforeach
            @endif            {{-- Title --}}
            <h2 class="text-xl font-bold text-gray-800 border-b pb-2">{{ __('messages.Your Deportation Info') }}</h2>

            {{-- User Info --}}
            <div class="text-gray-700 space-y-1 text-sm">
                <p><span class="font-medium text-gray-900">{{ __('messages.Name') }}:</span> {{ $user->name }}</p>
                <p><span class="font-medium text-gray-900">{{ __('messages.Phone') }}:</span> {{ $user->phone }}</p>
                <p><span class="font-medium text-gray-900">{{ __('messages.Week') }}:</span>
                    {{ $latestSheet->week_start_date }} {{ __('messages.to') }} {{ $latestSheet->week_end_date }}</p>
            </div>

            {{-- Deportation Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm border border-gray-200 rounded overflow-hidden">
                    <thead class="bg-gray-100 text-gray-700 text-xs uppercase">
                    <tr>
                        <th class="px-4 py-2">{{ __('messages.Production') }}</th>
                        <th class="px-4 py-2">{{ __('messages.Consumption') }}</th>
                        <th class="px-4 py-2">{{ __('messages.Deportation') }}</th>
                        <th class="px-4 py-2">{{ __('messages.Note') }}</th>
                        <th class="px-4 py-2">{{ __('messages.Note Type') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="border-t text-center">
                        <td class="px-4 py-2">{{ $deportation->production }}</td>
                        <td class="px-4 py-2">{{ $deportation->consumption }}</td>
                        <td class="px-4 py-2 text-indigo-600 font-semibold">
                            {{ number_format($deportation->deportation_note, 2) }}
                        </td>
                        <td class="px-4 py-2 text-gray-700">{{ $note ?? '—' }}</td>
                        <td class="px-4 py-2 text-gray-700">{{ $note_type ?? '—' }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>

            {{-- Total if note exists --}}
            @if (!empty($note))
                <div class="mt-4 p-3 bg-blue-50 border-l-4 border-blue-400 text-blue-800 rounded text-sm">
                    <strong>{{ __('messages.Total') }}:</strong>
                    {{ number_format($deportation->deportation_note + $note, 2) }}
                </div>
            @endif

        </div>
    </div>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

    $(document).ready(function () {
        $('.copy-btn').on('click', function () {
            const number = $(this).data('number'); // Get data-number

            // Create a temporary input to copy from
            const $tempInput = $('<input>').val(number).css({position: 'absolute', left: '-9999px'});
            $('body').append($tempInput);
            $tempInput.select();

            try {
                document.execCommand('copy');
                // alert('Copied Iban : ' + number);
                $('.copy-btn').on('click', function() {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Copied Iban : '+number,
                        icon: 'success',
                        confirmButtonText: 'OK',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload(); // Reloads the page
                        }
                    });
                });
            } catch (err) {
                alert('Failed to copy!');
            }

            $tempInput.remove(); // Clean up
        });



    });
</script>
