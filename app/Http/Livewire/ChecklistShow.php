<?php

namespace App\Http\Livewire;

use App\Models\Task;
use Livewire\Component;

class ChecklistShow extends Component
{
    public $checklist;
    public $opened_tasks = [];
    public $completed_tasks = [];
    public ?Task $current_task;

    public function mount()
    {
        $this->completed_tasks = Task::where('checklist_id', $this->checklist->id)
            ->where('user_id', auth()->id())
            ->whereNotNull('completed_at')
            ->pluck('task_id')
            ->toArray();

        $this->current_task = NULL;
    }

    public function render()
    {
        return view('livewire.checklist-show');
    }

    public function toggle_task($task_id)
    {
        if (in_array($task_id, $this->opened_tasks)) {
            $this->opened_tasks = array_diff($this->opened_tasks, [$task_id]);
            $this->current_task = NULL;
        } else {
            $this->opened_tasks[] = $task_id;
            $this->current_task = Task::where('user_id', auth()->id())
                ->where('task_id', $task_id)
                ->first();
            if (!$this->current_task) {
                $task = Task::find($task_id);
                $this->current_task = $task->replicate();
                $this->current_task['user_id'] = auth()->id();
                $this->current_task['task_id'] = $task_id;
                $this->current_task->save();
            }
        }
    }

    public function complete_task($task_id)
    {
        $task = Task::find($task_id);
        if ($task) {
            $user_task = Task::where('task_id', $task_id)
                ->where('user_id', auth()->id())
                ->first();
            if ($user_task) {
                if (is_null($user_task->completed_at)) {
                    $user_task->update(['completed_at' => now()]);
                    $this->completed_tasks[] = $task_id;
                    $this->emit('task_complete', $task_id, $task->checklist_id);
                } else {
                    $user_task->update(['completed_at' => NULL]);
                    $this->emit('task_complete', $task_id, $task->checklist_id, -1);
                }
            } else {
                $user_task = $task->replicate();
                $user_task['user_id'] = auth()->id();
                $user_task['task_id'] = $task_id;
                $user_task['completed_at'] = now();
                $user_task->save();
                $this->completed_tasks[] = $task_id;
                $this->emit('task_complete', $task_id, $task->checklist_id);
            }
        }
    }

    public function add_to_my_day($task_id)
    {
        $user_task = Task::where('user_id', auth()->id())
            ->where('id', $task_id)
            ->first();
        if ($user_task) {
            if (is_null($user_task->added_to_my_day_at)) {
                $user_task->update(['added_to_my_day_at' => now()]);
                $this->emit('user_tasks_counter_change', 'my_day');
            } else {
                $user_task->update(['added_to_my_day_at' => NULL]);
                $this->emit('user_tasks_counter_change', 'my_day', -1);
            }
        } else {
            $task = Task::find($task_id);
            $user_task = $task->replicate();
            $user_task['user_id'] = auth()->id();
            $user_task['task_id'] = $task_id;
            $user_task['added_to_my_day_at'] = now();
            $user_task->save();
            $this->emit('user_tasks_counter_change', 'my_day');
        }
        $this->current_task = $user_task;
    }
}
