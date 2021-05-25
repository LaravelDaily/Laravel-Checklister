<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                {{ $list_name }}
            </div>
            <div class="card-body">
                @if ($list_tasks->count())
                <table class="table">
                    @foreach($list_tasks as $task)
                        <tr>
                            <td width="5%">
                                <input type="checkbox" wire:click="complete_task({{ $task->id }})"
                                       @if (in_array($task->id, $completed_tasks)) checked="checked" @endif />
                            </td>
                            <td width="90%">
                                <a wire:click.prevent="toggle_task({{$task->id }})" href="#">{{ $task->name }}</a>
                                @if (!is_null($list_type))
                                    <div style="font-style: italic; font-size: 11px">
                                        {{ $task->checklist->name }}
                                        @if (optional($user_tasks->where('task_id', $task->id)->first())->due_date)
                                            | {{ __('Due') }} {{ $user_tasks->where('task_id', $task->id)->first()->due_date->format('M d, Y') }}
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td width="5%">
                                @if (optional($user_tasks->where('task_id', $task->id)->first())->is_important)
                                    <a wire:click.prevent="mark_as_important({{ $task->id }})" href="#">&starf;</a>
                                @else
                                    <a wire:click.prevent="mark_as_important({{ $task->id }})" href="#">&star;</a>
                                @endif
                            </td>
                        </tr>
                        @if (in_array($task->id, $opened_tasks))
                            <tr>
                                <td></td>
                                <td colspan="3">{!! $task->description !!}</td>
                            </tr>
                        @endif
                    @endforeach
                </table>
                @else
                    {{ __('No tasks found') }}
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4">
        @if (!is_null($current_task))
            <div class="card">
                <div class="card-body">
                    <div class="float-right">
                        @if ($current_task->is_important)
                            <a wire:click.prevent="mark_as_important({{ $current_task->id }})" href="#">&starf;</a>
                        @else
                            <a wire:click.prevent="mark_as_important({{ $current_task->id }})" href="#">&star;</a>
                        @endif
                    </div>
                    <b>{{ $current_task->name }}</b>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    &#9788;
                    &nbsp;
                    @if ($current_task->added_to_my_day_at)
                        <a wire:click.prevent="add_to_my_day({{ $current_task->id }})"
                           href="#">{{ __('Remove from My Day') }}</a>
                    @else
                        <a wire:click.prevent="add_to_my_day({{ $current_task->id }})"
                           href="#">{{ __('Add to My Day') }}</a>
                    @endif
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    &#9993;
                    &nbsp;
                    <a href="#">{{ __('Remind me') }}</a>
                    <hr/>
                    &#9745;
                    &nbsp;
                    @if ($current_task->due_date)
                        Due {{ $current_task->due_date->format('M j, Y') }}
                        &nbsp;&nbsp;
                        <a wire:click.prevent="set_due_date({{ $current_task->id }})" href="#">{{ __('Remove') }}</a>
                    @else
                        <a wire:click.prevent="toggle_due_date" href="#">{{ __('Add Due Date') }}</a>
                        @if ($due_date_opened)
                            <ul>
                                <li>
                                    <a wire:click.prevent="set_due_date({{ $current_task->id }}, '{{ today()->toDateString() }}')"
                                       href="#">{{ __('Today') }}</a>
                                </li>
                                <li>
                                    <a wire:click.prevent="set_due_date({{ $current_task->id }}, '{{ today()->addDay()->toDateString() }}')"
                                       href="#">{{ __('Tomorrow') }}</a>
                                </li>
                                <li>
                                    <a wire:click.prevent="set_due_date({{ $current_task->id }}, '{{ today()->addWeek()->startOfWeek()->toDateString() }}')"
                                       href="#">{{ __('Next week') }}</a>
                                </li>
                                <li>
                                    {{ __('Or pick a date') }}
                                    <br />
                                    <input wire:model="due_date" type="date" name="picker_date" />
                                </li>
                            </ul>
                        @endif
                    @endif
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    &#9998;
                    &nbsp;
                    <a href="#">{{ __('Note') }}</a>
                </div>
            </div>
        @endif
    </div>
</div>
