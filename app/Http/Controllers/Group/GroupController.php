<?php

namespace App\Http\Controllers\Group;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Sheet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $groups = Group::latest()->paginate(10);
        return view('groups.index', compact('groups'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('groups.create');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image',
        ]);

        $imagePath = $request->file('image')->store('groups', 'public');

        $group = Group::create([
            'name' => $request->name,
            'image' => $imagePath,
        ]);
        
        Sheet::create([
            'group_id' => $group->id,
            'week_start_date' => now()->startOfWeek(),
            'week_end_date' => now()->endOfWeek(),
        ]);

        return redirect()->route('groups.index')->with('success', 'Group created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $group = Group::findOrFail($id);
        return view('groups.edit', compact('group'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $group = Group::findOrFail($id); 

         $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image',
        ]);

        $data = ['name' => $request->name];

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($group->image);
            $data['image'] = $request->file('image')->store('groups', 'public');
        }

        $group->update($data);

        return redirect()->route('groups.index')->with('success', 'Group updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $group = Group::findOrFail($id);
        
        Storage::disk('public')->delete($group->image);
        $group->delete();

        return redirect()->route('groups.index')->with('success', 'Group deleted.');
    }


    public function addUser(Request $request, Group $group)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        // Attach without duplicating
        $group->users()->syncWithoutDetaching([$request->user_id]);

        return back()->with('success', 'User added to group.');
    }

}
