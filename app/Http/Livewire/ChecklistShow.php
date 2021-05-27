<?php

namespace App\Http\Livewire;

use App\Models\Task;
use Carbon\Carbon;
use Livewire\Component;

class ChecklistShow extends Component
{
    public $checklist;
    public $list_type;
    public $list_name;
    public $list_tasks;
    public $user_tasks;

    public $opened_tasks = [];
    public $completed_tasks = [];
    public ?Task $current_task;

    public $due_date_opened = FALSE;
    public $due_date;
    public $note_opened = FALSE;
    public $note;
    public $reminder_opened = FALSE;
    public $reminder_date;
    public $reminder_hour;

    public function mount()
    {
        $this->current_task = NULL;
        $this->reminder_date = now()->addDay()->toDateString();
        $this->reminder_hour = now()->hour;
    }

    public function render()
    {
        if (is_null($this->list_type)) {
            $this->list_name = $this->checklist->name;
            $this->list_tasks = $this->checklist->tasks->where('user_id', NULL);
            $this->user_tasks = $this->checklist->user_tasks()->get();
            $this->completed_tasks = $this->user_tasks->whereNotNull('completed_at')->pluck('task_id')->toArray();
        } else {
            switch ($this->list_type) {
                case 'my_day':
                    $this->list_name = __('My Day');
                    $this->user_tasks = Task::where('user_id', auth()->id())->whereNotNull('added_to_my_day_at')->get();
                    break;
                case 'important':
                    $this->list_name = __('Important');
                    $this->user_tasks = Task::where('user_id', auth()->id())->where('is_important', 1)->get();
                    break;
                case 'planned':
                    $this->list_name = __('Planned');
                    $this->user_tasks = Task::where('user_id', auth()->id())->whereNotNull('due_date')->orderBy('due_date')->get();
                    break;
            }
            $this->list_tasks = Task::whereIn('id', $this->user_tasks->pluck('task_id'))->get();
            $this->completed_tasks = $this->user_tasks->whereNotNull('completed_at')->pluck('task_id')->toArray();
        }

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

    public function mark_as_important($task_id)
    {
        $user_task = Task::where('user_id', auth()->id())
            ->where(function ($query) use ($task_id) {
                $query->where('id', $task_id)
                    ->orWhere('task_id', $task_id);
            })
            ->first();
        if ($user_task) {
            if ($user_task->is_important == 0) {
                $user_task->update(['is_important' => 1]);
                $this->emit('user_tasks_counter_change', 'important');
            } else {
                $user_task->update(['is_important' => 0]);
                $this->emit('user_tasks_counter_change', 'important', -1);
            }
        } else {
            $task = Task::find($task_id);
            $user_task = $task->replicate();
            $user_task['user_id'] = auth()->id();
            $user_task['task_id'] = $task_id;
            $user_task['is_important'] = 1;
            $user_task->save();
            $this->emit('user_tasks_counter_change', 'important');
        }
        $this->current_task = $user_task;
    }

    public function toggle_due_date()
    {
        $this->due_date_opened = !$this->due_date_opened;
    }

    public function set_due_date($task_id, $due_date = NULL)
    {
        $user_task = Task::where('user_id', auth()->id())
            ->where(function ($query) use ($task_id) {
                $query->where('id', $task_id)
                    ->orWhere('task_id', $task_id);
            })
            ->first();
        if ($user_task) {
            if (is_null($due_date)) {
                $user_task->update(['due_date' => NULL]);
                $this->emit('user_tasks_counter_change', 'planned', -1);
            } else {
                $user_task->update(['due_date' => $due_date]);
                $this->emit('user_tasks_counter_change', 'planned');
            }
        } else {
            $task = Task::find($task_id);
            $user_task = $task->replicate();
            $user_task['user_id'] = auth()->id();
            $user_task['task_id'] = $task_id;
            $user_task['due_date'] = $due_date;
            $user_task->save();
            $this->emit('user_tasks_counter_change', 'planned');
        }
        $this->current_task = $user_task;
    }

    public function updatedDueDate($value)
    {
        $this->set_due_date($this->current_task->id, $value);
    }

    public function toggle_note()
    {
        $this->note_opened = !$this->note_opened;
        $this->note = $this->current_task->note;
    }

    public function save_note()
    {
        $this->current_task->update(['note' => $this->note]);
        $this->note_opened = FALSE;
    }

    public function toggle_reminder()
    {
        $this->reminder_opened = !$this->reminder_opened;
    }

    public function set_reminder($task_id, $reminder_date = NULL)
    {
        $user_task = Task::where('user_id', auth()->id())
            ->where('id', $task_id)
            ->first();

        $reminder_at = NULL;
        if ($reminder_date == 'custom') {
            $reminder_at = Carbon::create($this->reminder_date)
                ->setHour($this->reminder_hour)
                ->setMinute(0)
                ->setSecond(0)
                ->toDateTimeString();
        } else if (!is_null($reminder_date)) {
            $reminder_at = Carbon::create($reminder_date)
                ->setHour(now()->hour)
                ->setMinute(0)
                ->setSecond(0)
                ->toDateTimeString();
        }

        if ($user_task) {
            $user_task->update(['reminder_at' => $reminder_at]);
        } else {
            $task = Task::find($task_id);
            $user_task = $task->replicate();
            $user_task['user_id'] = auth()->id();
            $user_task['task_id'] = $task_id;
            $user_task['reminder_at'] = $reminder_at;
            $user_task->save();
        }
        $this->current_task = $user_task;
    }

}
