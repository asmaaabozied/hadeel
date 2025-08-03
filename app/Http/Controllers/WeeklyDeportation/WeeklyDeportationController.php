<?php

namespace App\Http\Controllers\WeeklyDeportation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\WeeklyDeportation;


class WeeklyDeportationController extends Controller
{
    public function index()
    {
        $groupedDeportations = WeeklyDeportation::with(['user', 'sheet'])
            ->latest()
            ->get()
            ->groupBy('sheet_id');

        $notes = [];
        $noteTypes = [];

        foreach ($groupedDeportations as $sheetId => $deportations) {
            foreach ($deportations as $deportation) {
                $userId = $deportation->user_id;

                $notes["$sheetId-$userId"] = DB::table('sheet_user')
                    ->where('sheet_id', $sheetId)
                    ->where('user_id', $userId)
                    ->value('note');

                $noteTypes["$sheetId-$userId"] = DB::table('sheet_user')
                    ->where('sheet_id', $sheetId)
                    ->where('user_id', $userId)
                    ->value('note_type');
            }
        }

        return view('weekly_deportations.index', compact('groupedDeportations', 'notes', 'noteTypes'));
    }


    public function destroy(WeeklyDeportation $deportation)
    {
        $deportation->delete();

        return response()->json(['success' => true]);
    }

}
