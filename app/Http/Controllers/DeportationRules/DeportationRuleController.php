<?php

namespace App\Http\Controllers\DeportationRules;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DeportationRule;



class DeportationRuleController extends Controller
{
    public function index()
    {
        $rules = DeportationRule::orderBy('min_production')->get();
        return view('deportation_rules.index', compact('rules'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'min_production' => 'required|integer',
            'max_production' => 'nullable|integer',
            'adjustment' => 'required|integer',
        ]);

        DeportationRule::create($request->all());
        return back()->with('success', 'Rule added.');
    }

    public function update(Request $request, DeportationRule $rule)
    {
        $request->validate([
            'min_production' => 'nullable|integer',
            'max_production' => 'nullable|integer',
            'adjustment' => 'nullable|integer',
        ]);

        $rule->update($request->all());
        return back()->with('success', 'Rule updated.');
    }

    public function destroy(DeportationRule $rule)
    {
        $rule->delete();
        return back()->with('success', 'Rule deleted.');
    }
}
