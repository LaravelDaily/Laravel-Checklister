<?php

namespace App\Services;

use App\Models\Checklist;
use App\Models\ChecklistGroup;
use App\Models\Task;
use Carbon\Carbon;

class MenuService
{

    public function get_menu(): array
    {
        $menu = ChecklistGroup::with([
            'checklists' => function ($query) {
                $query->whereNull('user_id');
            },
            'checklists.tasks' => function ($query) {
                $query->whereNull('tasks.user_id');
            },
            'checklists.user_completed_tasks',
        ])
            ->get();

        $groups = [];

        $user_checklists = Checklist::where('user_id', auth()->id())->get();

        foreach ($menu->toArray() as $group) {
            if (count($group['checklists']) > 0) {
                $group_updated_at = $user_checklists->where('checklist_group_id', $group['id'])->max('updated_at');
                $group['is_new'] = $group_updated_at && Carbon::create($group['created_at'])->greaterThan($group_updated_at);
                $group['is_updated'] = !($group['is_new'])
                    && $group_updated_at
                    && Carbon::create($group['updated_at'])->greaterThan($group_updated_at);

                foreach ($group['checklists'] as &$checklist) {
                    $checklist_updated_at = $user_checklists->where('checklist_id', $checklist['id'])->max('updated_at');

                    $checklist['is_new'] = !($group['is_new'])
                        && $checklist_updated_at
                        && Carbon::create($checklist['created_at'])->greaterThan($checklist_updated_at);
                    $checklist['is_updated'] = !($group['is_new']) && !($group['is_updated'])
                        && !($checklist['is_new'])
                        && $checklist_updated_at
                        && Carbon::create($checklist['updated_at'])->greaterThan($checklist_updated_at);;
                    $checklist['tasks_count'] = count($checklist['tasks']);
                    $checklist['completed_tasks_count'] = count($checklist['user_completed_tasks']);
                }

                $groups[] = $group;
            }
        }

        $user_tasks_menu = [];
        if (!auth()->user()->is_admin) {
            $user_tasks = Task::where('user_id', auth()->id())->get();
            $user_tasks_menu = [
                'my_day' => [
                    'name' => __('My Day'),
                    'icon' => 'sun',
                    'tasks_count' => $user_tasks->whereNotNull('added_to_my_day_at')->count()
                ],
                'important' => [
                    'name' => __('Important'),
                    'icon' => 'star',
                    'tasks_count' => $user_tasks->where('is_important', 1)->count()
                ],
                'planned' => [
                    'name' => __('Planned'),
                    'icon' => 'calendar',
                    'tasks_count' => $user_tasks->whereNotNull('due_date')->count()
                ],
            ];
        }

        return [
            'admin_menu' => $menu,
            'user_menu' => $groups,
            'user_tasks_menu' => $user_tasks_menu,
        ];
    }

}
