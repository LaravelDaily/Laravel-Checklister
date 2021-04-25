<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreChecklistRequest;
use App\Models\Checklist;
use App\Models\ChecklistGroup;
use Illuminate\Http\Request;

class ChecklistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param ChecklistGroup $checklistGroup
     * @return \Illuminate\Http\Response
     */
    public function create(ChecklistGroup $checklistGroup)
    {
        return view('admin.checklists.create', compact('checklistGroup'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreChecklistRequest $request
     * @param ChecklistGroup $checklistGroup
     * @return \Illuminate\Http\Response
     */
    public function store(StoreChecklistRequest $request, ChecklistGroup $checklistGroup)
    {
        $checklistGroup->checklists()->create($request->validated());

        return redirect()->route('home');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param ChecklistGroup $checklistGroup
     * @param Checklist $checklist
     * @return \Illuminate\Http\Response
     */
    public function edit(ChecklistGroup $checklistGroup, Checklist $checklist)
    {
        return view('admin.checklists.edit', compact('checklistGroup', 'checklist'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreChecklistRequest $request
     * @param ChecklistGroup $checklistGroup
     * @param Checklist $checklist
     * @return \Illuminate\Http\Response
     */
    public function update(StoreChecklistRequest $request, ChecklistGroup $checklistGroup, Checklist $checklist)
    {
        $checklist->update($request->validated());

        return redirect()->route('home');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ChecklistGroup $checklistGroup
     * @param Checklist $checklist
     * @return \Illuminate\Http\Response
     */
    public function destroy(ChecklistGroup $checklistGroup, Checklist $checklist)
    {
        $checklist->delete();

        return redirect()->route('home');
    }
}
