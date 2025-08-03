<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Sheet;
use App\Models\WeeklyDeportation;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\GeneralSetting; 
use Illuminate\Validation\Rule;


class UserDeportationController extends Controller
{
    public function showForm()
    {
        return view('user.lookup');
    }

    public function view(Request $request)
    {
        $request->validate([
            'unique_identifier' => [
                'required',
                'string',
                Rule::exists('users', 'unique_identifier'),
            ],
        ], [
            'unique_identifier.exists' => 'No user found with this ID.',
        ]);

        $user = User::where('unique_identifier', $request->unique_identifier)->firstOrFail();

        // Find latest sheet that has deportation data for the user and ended on or before today
        $latestSheetId = WeeklyDeportation::where('user_id', $user->id)
            ->whereHas('sheet', function ($query) {
                $query->whereDate('week_end_date', '<=', now());
            })
            ->orderByDesc('sheet_id')
            ->value('sheet_id');

        if (!$latestSheetId) {
            return back()->with('error', 'No deportation data found for your ID.');
        }

        $latestSheet = Sheet::find($latestSheetId);

        $deportation = WeeklyDeportation::where('sheet_id', $latestSheetId)
            ->where('user_id', $user->id)
            ->first();

        $note = DB::table('sheet_user')
            ->where('sheet_id', $latestSheet->id)
            ->where('user_id', $user->id)
            ->value('note');

        $note_type = DB::table('sheet_user')
            ->where('sheet_id', $latestSheet->id)
            ->where('user_id', $user->id)
            ->value('note_type');

        // Get user's group from pivot table
        $groupId = DB::table('group_user')
            ->where('user_id', $user->id)
            ->orderByDesc('id')
            ->value('group_id');

        // Get general setting for the user's group
        $general = null;
        if ($groupId) {
            $general = GeneralSetting::where('group_id', $groupId)->first();
        }

        return view('user.result', compact('deportation', 'latestSheet', 'user', 'note', 'note_type', 'general'));
    }



}
