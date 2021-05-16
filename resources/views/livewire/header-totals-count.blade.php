<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                {{ __('Store Review') }}
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-9">
                        <div class="row">
                            @foreach ($checklists as $checklist)
                            <div class="col-md-3">
                                <strong>{{ $checklist->name }}</strong>
                                <br />
                                <strong>{{ $checklist->user_tasks_count }}/{{ $checklist->tasks_count }}</strong>
                                <div class="progress progress-xs mt-2">
                                    @if ($checklist->tasks_count > 0)
                                    <div class="progress-bar bg-success" role="progressbar"
                                         style="width: {{ $checklist->user_tasks_count / $checklist->tasks_count * 100 }}%"
                                         aria-valuenow="{{ $checklist->user_tasks_count / $checklist->tasks_count * 100 }}"
                                         aria-valuemin="0"
                                         aria-valuemax="100">
                                    </div>
                                    @else
                                        <div class="progress-bar bg-success" role="progressbar"
                                             style="width: 0%"
                                             aria-valuenow="0"
                                             aria-valuemin="0"
                                             aria-valuemax="100">
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-md-3">
                        <h2>{{ $checklists->sum('user_tasks_count') }}/{{ $checklists->sum('tasks_count') }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
