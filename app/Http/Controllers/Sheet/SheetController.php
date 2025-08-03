<?php

namespace App\Http\Controllers\Sheet;

use App\Http\Controllers\Controller;
use App\Models\SheetUser;
use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\Sheet;
use App\Models\User;
use App\Models\DeportationRule;

use App\Models\SheetUserChange;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class SheetController extends Controller
{

    // public function groupSheets(Group $group)
    // {
    //     // $sheets = $group->sheets()->with('users')->orderBy('week_start_date', 'desc')->get();

    //     $sheets = $group->sheets()
    //         ->with(['users:id,name,phone,unique_identifier']) // only load essential user data
    //         ->orderBy('week_start_date', 'desc')
    //         ->get();

    //     // $allUsers = User::select('id', 'name', 'phone', 'unique_identifier')->get();

    //     // return view('sheets.index', compact('group', 'sheets','allUsers'));
    //     return view('sheets.index', compact('group', 'sheets'));
    // }


    public function groupSheets(Group $group)
    {
        $sheets = $group->sheets()
            ->orderBy('week_start_date', 'desc')
            ->get();

        return view('sheets.index', compact('group', 'sheets'));
    }

    public function loadSheetUsers(Sheet $sheet)
    {
        // Load users sorted by name (Arabic) including pivot
        $sheet->load(['users' => function ($q) {
            $q->orderBy('name', 'asc');
        }]);

        return view('sheets.partials.users_table', compact('sheet'));
    }


    public function storeUser(Request $request, Sheet $sheet)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'production' => 'required|numeric|min:0',
            'consumption' => 'required|numeric|min:0',
            'note' => 'nullable|string|max:255',
        ]);

        $sheet->users()->syncWithoutDetaching([
            $validated['user_id'] => [
                'production' => $validated['production'],
                'consumption' => $validated['consumption'],
                'note' => $validated['note'],
            ]
        ]);

        if ($request->ajax()) {
            $user = \App\Models\User::find($validated['user_id']);
            $pivot = $sheet->users()->where('user_id', $user->id)->first()->pivot;

            return response()->json([
                'message' => 'User added.',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'phone' => $user->phone,
                    'production' => $pivot->production,
                    'consumption' => $pivot->consumption,
                    'note' => $pivot->note,
                ]
            ]);
        }


        return back()->with('success', 'User added to sheet successfully.');
    }


    public function store(Request $request)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'week_start_date' => 'required|date',
            'week_end_date' => 'required|date|after_or_equal:week_start_date',
        ]);

        // Check if sheet for the same group and week already exists
        $exists = Sheet::where('group_id', $request->group_id)
            ->where('week_start_date', $request->week_start_date)
            ->exists();

        if ($exists) {
            return back()->with('error', 'A sheet for this group and week already exists.');
        }

        $newSheet = Sheet::create([
            'group_id' => $request->group_id,
            'week_start_date' => $request->week_start_date,
            'week_end_date' => $request->week_end_date,
        ]);

        // Get the latest previous sheet (if exists)
        $lastSheet = Sheet::where('group_id', $request->group_id)
            ->where('week_start_date', '<', $request->week_start_date)
            ->orderByDesc('week_start_date')
            ->first();

        if ($lastSheet) {
            // Copy users from last sheet to new sheet
            foreach ($lastSheet->users as $user) {
                $newSheet->users()->attach($user->id, [
                    'production' => 0,
                    'consumption' => 0,
                    'note' => null,
                ]);
            }
        }

        return back()->with('success', 'Weekly sheet created successfully.');
    }


    public function increment(Request $request, Sheet $sheet, User $user)
    {
        try {
            $field = $request->input('field');

            if (!in_array($field, ['production', 'consumption'])) {
                return response()->json(['error' => 'Invalid field'], 400);
            }

            $pivot = SheetUser::where('sheet_id', $sheet->id)
                ->where('user_id', $user->id)
//                ->latest('id')
                ->where('admin_id', auth()->id())
                ->first();
            $old = $pivot->$field;
            $new = $old + 1;

//            if ($pivot->admin_id == auth()->id() || empty($pivot->admin_id)) {
//                DB::table('sheet_user')
//                    ->where('sheet_id', $sheet->id)
//                    ->where('user_id', $user->id)
//                    ->where('admin_id',auth()->id())
//                    ->update([$field => $new, 'updated_at' => now(), 'admin_id' => auth()->id()]);
//
////            }
            $pivot->update([$field => $new, 'updated_at' => now()]);


//            elseif ($pivot->admin_id !== auth()->id()) {
//                SheetUser::create([
//                    'sheet_id' => $sheet->id,
//                    'user_id' => $user->id,
//                    $field => $new,
//                    'created_at' => now(),
//                    'admin_id' => auth()->id(),
//                ]);
//
//            }

            \App\Models\SheetUserChange::create([
                'sheet_id' => $sheet->id,
                'user_id' => $user->id,
                'field' => $field,
                'old_value' => $old,
                'new_value' => $new,
                'changed_by' => auth()->id(),
            ]);

            return response()->json(['success' => true, 'value' => $new]);

        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function undo(Sheet $sheet)
    {
        $userId = auth()->id();
        $loginTime = session('login_time', now()->subDay());

        $change = SheetUserChange::where('sheet_id', $sheet->id)
            ->where('changed_by', $userId)
            ->where('reverted', false)
            ->where('created_at', '>=', $loginTime)
            ->orderByDesc('id')
            ->first();

        if (!$change) {
            return response()->json(['error' => 'No change to undo'], 404);
        }

        DB::table('sheet_user')
            ->where('sheet_id', $sheet->id)
            ->where('user_id', $change->user_id)
            ->update([
                $change->field => $change->old_value,
                'updated_at' => now()
            ]);

        $change->reverted = true;
        $change->save();

        return response()->json([
            'success' => true,
            'field' => $change->field,
            'value' => $change->old_value,
            'user_id' => $change->user_id,
        ]);
    }


    public function redo(Sheet $sheet)
    {
        $userId = auth()->id();
        $loginTime = session('login_time', now()->subDay());

        $change = SheetUserChange::where('sheet_id', $sheet->id)
            ->where('changed_by', $userId)
            ->where('reverted', true)
            ->where('created_at', '>=', $loginTime)
            ->orderBy('id')
            ->first();

        if (!$change) {
            return response()->json(['error' => 'No change to redo'], 404);
        }

        DB::table('sheet_user')
            ->where('sheet_id', $sheet->id)
            ->where('user_id', $change->user_id)
            ->update([
                $change->field => $change->new_value,
                'updated_at' => now()
            ]);

        $change->reverted = false;
        $change->save();

        return response()->json([
            'success' => true,
            'field' => $change->field,
            'value' => $change->new_value,
            'user_id' => $change->user_id,
        ]);
    }


// public function manualUpdate(Request $request, Sheet $sheet, User $user)
// {
//     $request->validate([
//         'production' => 'nullable|numeric',
//         'consumption' => 'nullable|numeric',
//         'note' => 'nullable|numeric',
//     ]);

//     $pivot = DB::table('sheet_user')
//         ->where('sheet_id', $sheet->id)
//         ->where('user_id', $user->id)
//         ->first();

//     if (!$pivot) {
//         return response()->json(['error' => 'Record not found'], 404);
//     }

//     $updates = [];
//     $fields = ['production', 'consumption', 'note'];

//     foreach ($fields as $field) {
//         if (!is_null($request->$field) && $request->$field != $pivot->$field) {
//             // Only log changes for production and consumption
//             if ($field !== 'note') {
//                 SheetUserChange::create([
//                     'sheet_id' => $sheet->id,
//                     'user_id' => $user->id,
//                     'field' => $field,
//                     'old_value' => $pivot->$field,
//                     'new_value' => $request->$field,
//                     'changed_by' => auth()->id(),
//                 ]);
//             }

//             $updates[$field] = $request->$field;
//         }
//     }

//     if (!empty($updates)) {
//         $updates['updated_at'] = now();
//         DB::table('sheet_user')
//             ->where('sheet_id', $sheet->id)
//             ->where('user_id', $user->id)
//             ->update($updates);
//     }

//     return response()->json([
//         'success' => true,
//         'production' => $updates['production'] ?? $pivot->production,
//         'consumption' => $updates['consumption'] ?? $pivot->consumption,
//         'note' => $updates['note'] ?? $pivot->note,
//     ]);
// }


    public function manualUpdate(Request $request, Sheet $sheet, User $user)
    {
        $request->validate([
            'production' => 'nullable|numeric',
            'consumption' => 'nullable|numeric',
            'note' => 'nullable|numeric',
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
        ]);

        // 1. Ensure the user belongs to the sheet
        $pivot = DB::table('sheet_user')
            ->where('sheet_id', $sheet->id)
            ->where('user_id', $user->id)
            ->where('admin_id', auth()->id())
            ->first();

        if (!$pivot) {
            return response()->json(['success' => false, 'error' => 'Record not found'], 404);
        }

        $pivotUpdates = [];
        $userUpdates = [];

        // 2. Handle pivot updates (production, consumption, note)
        foreach (['production', 'consumption', 'note'] as $field) {
            if (!is_null($request->$field) && $request->$field != $pivot->$field) {
                if (in_array($field, ['production', 'consumption'])) {
                    SheetUserChange::create([
                        'sheet_id' => $sheet->id,
                        'user_id' => $user->id,
                        'field' => $field,
                        'old_value' => $pivot->$field,
                        'new_value' => $request->$field,
                        'changed_by' => auth()->id(),
                        'admin_id' => auth()->id(),
                    ]);
                }

                $pivotUpdates[$field] = $request->$field;
            }
        }

        // 3. Handle user updates (name, phone)
        if (!is_null($request->name) && $request->name !== $user->name) {
            $userUpdates['name'] = $request->name;
        }

        if (!is_null($request->phone) && $request->phone !== $user->phone) {
            $userUpdates['phone'] = $request->phone;
        }

        // 4. Apply the updates
        if (!empty($pivotUpdates)) {
            $pivotUpdates['updated_at'] = now();
            DB::table('sheet_user')
                ->where('sheet_id', $sheet->id)
                ->where('user_id', $user->id)
                ->where('admin_id', auth()->id())
                ->update($pivotUpdates);
        }

        if (!empty($userUpdates)) {
            $user->update($userUpdates);
        }

        // 5. Return updated values (from fresh DB if needed)
        return response()->json([
            'success' => true,
            'production' => $pivotUpdates['production'] ?? $pivot->production,
            'consumption' => $pivotUpdates['consumption'] ?? $pivot->consumption,
            'note' => $pivotUpdates['note'] ?? $pivot->note,
            'name' => $userUpdates['name'] ?? $user->name,
            'phone' => $userUpdates['phone'] ?? $user->phone,
        ]);
    }


    public function addmerged($sheet_id)
    {


        $data = SheetUser::where('sheet_id', $sheet_id)->get();

        foreach ($data as $sheet) {
            $sheet->update(['type' => 'merged']);

        }
        return response()->json([
            'success' => true,
            'data' => 'Merged completed'

        ]);
//        return redirect()->back()->with('success', 'Merged completed.');

    }

    public function updateField(Request $request, Sheet $sheet)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'field' => 'required|in:production,consumption',
            'value' => 'required|numeric'
        ]);

        $sheet->users()->updateExistingPivot($validated['user_id'], [
            $validated['field'] => $validated['value']
        ]);

        $totalProduction = round($sheet->users()->sum('sheet_user.production'), 2);
        $totalConsumption = round($sheet->users()->sum('sheet_user.consumption'), 2);

        return response()->json([
            'success' => true,
            'new_totals' => [
                'production' => $totalProduction,
                'consumption' => $totalConsumption
            ]
        ]);
    }

    public function deport(Sheet $sheet)
    {
        $rules = DeportationRule::all();

        if ($rules->isEmpty()) {
            return redirect()->back()->with('error', 'No deportation rules found. Please define at least one rule.');
        }

        try {

            foreach ($sheet->users as $user) {
                $production = $user->pivot->production;
                $consumption = $user->pivot->consumption;

                $rule = $rules->first(function ($r) use ($production) {
                    return $production >= $r->min_production &&
                        (is_null($r->max_production) || $production <= $r->max_production);
                });

                if (!$rule) {
                    continue;
                }

                $deportation = ($consumption - ($production + $rule->adjustment)) / 4;
                $deportation = floor($deportation * 100) / 100;
                $deportation *= -1;

                DB::table('weekly_deportations')->insert([
                    'sheet_id' => $sheet->id,
                    'user_id' => $user->id,
                    'production' => $production,
                    'consumption' => $consumption,
                    'deportation_note' => $deportation,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            return redirect()->back()->with('success', 'Deportation completed and saved.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Failed to complete deportation. Please try again.');
        }
    }


    public function removeUser(Sheet $sheet, User $user)
    {
        $sheet->users()->detach($user->id);
        return back()->with('success', 'User removed from sheet.');
    }


    public function updateNoteType(Request $request, Sheet $sheet, User $user)
    {
        Log::info('Note type update request received', [
            'sheet_id' => $sheet->id,
            'user_id' => $user->id,
            'note_type' => $request->note_type,
        ]);

        $request->validate([
            'note_type' => 'required|in:فائض,تعويض,جائزة,ترحيل',
        ]);

        DB::table('sheet_user')
            ->where('sheet_id', $sheet->id)
            ->where('user_id', $user->id)
            ->update([
                'note_type' => $request->note_type,
            ]);

        return response()->json(['success' => true]);
    }


}
