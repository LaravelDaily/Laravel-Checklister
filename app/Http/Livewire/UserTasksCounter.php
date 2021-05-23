<?php

namespace App\Http\Livewire;

use Livewire\Component;

class UserTasksCounter extends Component
{
    public string $task_type;
    public int $tasks_count;

    protected $listeners = [
        'user_tasks_counter_change' => 'recalculate_tasks',
    ];

    public function render()
    {
        return view('livewire.user-tasks-counter');
    }

    public function recalculate_tasks($task_type, $count_change = 1)
    {
        if ($this->task_type == $task_type) {
            $this->tasks_count += $count_change;
        }
    }
}
