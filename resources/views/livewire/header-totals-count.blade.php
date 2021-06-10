<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                {{ __('Store Review') }}
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        @foreach ($checklists as $checklist)
                            <div class="row mb-2">
                                <div class="col-md-5 text-right">
                                    <a class="font-weight-bold"
                                       href="{{ route('user.checklists.show', $checklist->id) }}">{{ $checklist->name }}</a>
                                </div>
                                <div class="col-md-7">
                                    <div class="progress progress-xs mt-2 mb-1">
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
                                    <strong>{{ $checklist->user_tasks_count }}/{{ $checklist->tasks_count }}</strong>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="col-md-4">
                        <h2>{{ $checklists->sum('user_tasks_count') }}/{{ $checklists->sum('tasks_count') }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
