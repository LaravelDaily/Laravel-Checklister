<?php

namespace App\Http\Livewire;

use App\Models\Checklist;
use Livewire\Component;

class HeaderTotalsCount extends Component
{
    public $checklist_group_id;

    protected $listeners = ['task_complete' => 'render'];

    public function render()
    {
        $checklists = Checklist::where('checklist_group_id', $this->checklist_group_id)
            ->whereNull('user_id')
            ->withCount(['tasks' => function($query) {
                $query->whereNull('user_id');
            }])
            ->withCount(['user_tasks' => function($query) {
                $query->whereNotNull('completed_at');
            }])
            ->get();

        return view('livewire.header-totals-count', compact('checklists'));
    }
}
