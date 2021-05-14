<table class="table table-responsive-sm">
    <tbody>
    @foreach ($tasks as $task)
        <tr>
            <td>
                @if ($task->position > 1)
                <a wire:click.prevent="task_up({{ $task->id }})" href="#">
                    &uarr;
                </a>
                @endif
                @if ($task->position < $tasks->max('position'))
                <a wire:click.prevent="task_down({{ $task->id }})" href="#">
                    &darr;
                </a>
                @endif
            </td>
            <td>{{ $task->name }}</td>
            <td>
                <a class="btn btn-sm btn-primary"
                   href="{{ route('admin.checklists.tasks.edit', [$checklist, $task]) }}">{{ __('Edit') }}</a>
                <form style="display: inline-block"
                      action="{{ route('admin.checklists.tasks.destroy', [$checklist, $task]) }}"
                      method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger" type="submit"
                            onclick="return confirm('{{ __('Are you sure?') }}')"> {{ __('Delete') }}</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
