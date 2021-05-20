<?php

namespace Tests\Feature;

use App\Http\Livewire\TasksTable;
use App\Models\Checklist;
use App\Models\ChecklistGroup;
use App\Models\Task;
use App\Models\User;
use App\Services\MenuService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminChecklistsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $admin = User::factory()->create(['is_admin' => 1]);
        $this->actingAs($admin);
    }

    public function test_manage_checklist_groups()
    {
        // Test CREATING the checklist group
        $response = $this->post('admin/checklist_groups', [
            'name' => 'First group'
        ]);
        $response->assertRedirect('welcome');

        $group = ChecklistGroup::where('name', 'First group')->first();
        $this->assertNotNull($group);

        // Test EDITING the checklist group
        $response = $this->get('admin/checklist_groups/' . $group->id . '/edit');
        $response->assertStatus(200);

        $response = $this->put('admin/checklist_groups/' . $group->id, [
            'name' => 'Updated first group'
        ]);
        $response->assertRedirect('welcome');

        $group = ChecklistGroup::where('name', 'Updated first group')->first();
        $this->assertNotNull($group);

        $menu = (new MenuService())->get_menu();
        $this->assertEquals(1, $menu['admin_menu']->where('name', 'Updated first group')->count());

        // Test DELETING the checklist group
        $response = $this->delete('admin/checklist_groups/' . $group->id);
        $response->assertRedirect('welcome');

        $group = ChecklistGroup::where('name', 'Updated first group')->first();
        $this->assertNull($group);

        $menu = (new MenuService())->get_menu();
        $this->assertEquals(0, $menu['admin_menu']->where('name', 'Updated first group')->count());
    }

    public function test_manage_checklists()
    {
        $checklist_group = ChecklistGroup::factory()->create();

        $checklists_url = 'admin/checklist_groups/' . $checklist_group->id . '/checklists';

        // Test CREATING the checklist
        $response = $this->get($checklists_url . '/create');
        $response->assertStatus(200);

        $response = $this->post($checklists_url, [
            'name' => 'First checklist'
        ]);
        $response->assertRedirect('welcome');

        $checklist = Checklist::where('name', 'First checklist')->first();
        $this->assertNotNull($checklist);

        // Test EDITING the checklist
        $response = $this->get($checklists_url . '/' . $checklist->id . '/edit');
        $response->assertStatus(200);

        $response = $this->put($checklists_url . '/' . $checklist->id, [
            'name' => 'Updated checklist'
        ]);
        $response->assertRedirect('welcome');

        $checklist = Checklist::where('name', 'Updated checklist')->first();
        $this->assertNotNull($checklist);

        $menu = (new MenuService())->get_menu();
        $this->assertTrue($menu['admin_menu']->first()->checklists->contains($checklist));

        // Test DELETING the checklist
        $response = $this->delete($checklists_url . '/' . $checklist->id);
        $response->assertRedirect('welcome');

        $deleted_checklist = Checklist::where('name', 'Updated checklist')->first();
        $this->assertNull($deleted_checklist);

        $menu = (new MenuService())->get_menu();
        $this->assertFalse($menu['admin_menu']->first()->checklists->contains($checklist));
    }

    public function test_manage_tasks()
    {
        $checklist_group = ChecklistGroup::factory()->create();
        $checklist = Checklist::factory()->create(['checklist_group_id' => $checklist_group->id]);

        $tasks_url = 'admin/checklists/' . $checklist->id . '/tasks';
        $response = $this->post($tasks_url, [
            'name' => 'Some task',
            'description' => 'Some description'
        ]);
        $response->assertRedirect('admin/checklist_groups/' . $checklist_group->id . '/checklists/' . $checklist->id . '/edit');

        $task = Task::where('name', 'Some task')->first();
        $this->assertNotNull($task);
        $this->assertEquals(1, $task->position);

        $response = $this->put($tasks_url . '/' . $task->id, [
            'name' => 'Updated task',
            'description' => $task->description
        ]);
        $response->assertRedirect('admin/checklist_groups/' . $checklist_group->id . '/checklists/' . $checklist->id . '/edit');

        $task = Task::where('name', 'Updated task')->first();
        $this->assertNotNull($task);
    }

    public function test_delete_task_with_position_reordered()
    {
        $checklist_group = ChecklistGroup::factory()->create();
        $checklist = Checklist::factory()->create(['checklist_group_id' => $checklist_group->id]);

        $task1 = Task::factory()->create(['checklist_id' => $checklist->id, 'position' => 1]);
        $task2 = Task::factory()->create(['checklist_id' => $checklist->id, 'position' => 2]);

        $tasks_url = 'admin/checklists/' . $checklist->id . '/tasks';
        $response = $this->delete($tasks_url . '/' . $task1->id);
        $response->assertRedirect('admin/checklist_groups/' . $checklist_group->id . '/checklists/' . $checklist->id . '/edit');

        $task = Task::where('name', $task1->name)->first();
        $this->assertNull($task);

        $task = Task::where('name', $task2->name)->first();
        $this->assertNotNull($task);
        $this->assertEquals(1, $task->position);
    }

    public function test_reordering_task_with_livewire()
    {
        $checklist_group = ChecklistGroup::factory()->create();
        $checklist = Checklist::factory()->create(['checklist_group_id' => $checklist_group->id]);

        $task1 = Task::factory()->create(['checklist_id' => $checklist->id, 'position' => 1]);
        $task2 = Task::factory()->create(['checklist_id' => $checklist->id, 'position' => 2]);

        Livewire::test(TasksTable::class, ['checklist' => $checklist])
            ->call('task_up', $task2->id);

        $task = Task::find($task2->id);
        $this->assertEquals(1, $task->position);

        Livewire::test(TasksTable::class, ['checklist' => $checklist])
            ->call('task_down', $task2->id);

        $task = Task::find($task2->id);
        $this->assertEquals(2, $task->position);
    }
}
