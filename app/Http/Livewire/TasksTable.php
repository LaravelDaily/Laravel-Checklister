<?php

namespace App\Http\Livewire;

use App\Models\Task;
use Livewire\Component;

class TasksTable extends Component
{
    public $checklist;

    public function render()
    {
        $tasks = $this->checklist->tasks()->orderBy('position')->get();

        return view('livewire.tasks-table', compact('tasks'));
    }

    public function updateTaskOrder($tasks)
    {
        foreach ($tasks as $task) {
            Task::find($task['value'])->update(['position' => $task['order']]);
        }
    }
}
