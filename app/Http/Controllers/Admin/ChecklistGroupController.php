<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreChecklistGroupRequest;
use App\Http\Requests\UpdateChecklistGroupRequest;
use App\Models\ChecklistGroup;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ChecklistGroupController extends Controller
{
    public function create(): View
    {
        return view('admin.checklist_groups.create');
    }

    public function store(StoreChecklistGroupRequest $request): RedirectResponse
    {
        ChecklistGroup::create($request->validated());

        return redirect()->route('welcome');
    }

    public function edit(ChecklistGroup $checklistGroup): View
    {
        return view('admin.checklist_groups.edit', compact('checklistGroup'));
    }

    public function update(UpdateChecklistGroupRequest $request, ChecklistGroup $checklistGroup): RedirectResponse
    {
        $checklistGroup->update($request->validated());

        return redirect()->route('welcome');
    }

    public function destroy(ChecklistGroup $checklistGroup): RedirectResponse
    {
        $checklistGroup->delete();

        return redirect()->route('welcome');
    }
}
