<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Notifications\TaskReminderNotification;
use Illuminate\Console\Command;

class SendTaskRemindersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task_reminders:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send task reminder emails to users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $tasks = Task::with('user')
            ->where('reminder_at', '<=', now()->toDateTimeString())
            ->get();

        foreach ($tasks as $task) {
            $task->user->notify(new TaskReminderNotification($task));
            $task->update(['reminder_at' => NULL]);
        }

        return 0;
    }
}
