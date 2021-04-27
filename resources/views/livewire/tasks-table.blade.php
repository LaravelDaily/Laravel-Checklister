<table class="table table-responsive-sm" wire:sortable="updateTaskOrder">
    <tbody>
    @foreach ($tasks as $task)
        <tr wire:sortable.item="{{ $task->id }}" wire:key="task-{{ $task->id }}">
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
