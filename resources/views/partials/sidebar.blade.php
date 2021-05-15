<div class="c-sidebar c-sidebar-dark c-sidebar-fixed c-sidebar-lg-show" id="sidebar">
    <ul class="c-sidebar-nav">
        @if (auth()->user()->is_admin)
            <li class="c-sidebar-nav-title">{{ __('Manage Checklists') }}</li>
            @foreach ($admin_menu as $group)
                <li class="c-sidebar-nav-item c-sidebar-nav-dropdown c-show">
                    <a class="c-sidebar-nav-link"
                       href="{{ route('admin.checklist_groups.edit', $group->id) }}">
                        <svg class="c-sidebar-nav-icon">
                            <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-folder-open') }}"></use>
                        </svg> {{ $group->name }}
                    </a>
                    <ul class="c-sidebar-nav-dropdown-items">
                        @foreach ($group->checklists as $checklist)
                            <li class="c-sidebar-nav-item">
                                <a class="c-sidebar-nav-link" style="padding: .5rem .5rem .5rem 76px"
                                   href="{{ route('admin.checklist_groups.checklists.edit', [$group, $checklist]) }}">
                                    <svg class="c-sidebar-nav-icon">
                                        <use
                                            xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-list') }}"></use>
                                    </svg>
                                    {{ $checklist->name }}</a>
                            </li>
                        @endforeach
                        <li class="c-sidebar-nav-item">
                            <a class="c-sidebar-nav-link" style="padding: 1rem .5rem .5rem 76px"
                               href="{{ route('admin.checklist_groups.checklists.create', $group) }}">
                                <svg class="c-sidebar-nav-icon">
                                    <use
                                        xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-note-add') }}"></use>
                                </svg>
                                {{ __('New checklist') }}</a>
                        </li>
                    </ul>
                </li>
            @endforeach
            <li class="c-sidebar-nav-item c-sidebar-nav-dropdown">
                <a class="c-sidebar-nav-link" href="{{ route('admin.checklist_groups.create') }}">
                    <svg class="c-sidebar-nav-icon">
                        <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-library-add') }}"></use>
                    </svg>
                    {{ __('New checklist group') }}</a>
            </li>

            <li class="c-sidebar-nav-title">{{ __('Pages') }}</li>
            @foreach (\App\Models\Page::all() as $page)
                <li class="c-sidebar-nav-item c-sidebar-nav-dropdown">
                    <a class="c-sidebar-nav-link"
                       href="{{ route('admin.pages.edit', $page) }}">
                        <svg class="c-sidebar-nav-icon">
                            <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-puzzle') }}"></use>
                        </svg> {{ $page->title }}
                    </a>
                </li>
            @endforeach

            <li class="c-sidebar-nav-title">{{ __('Manage Data') }}</li>
            <li class="c-sidebar-nav-item c-sidebar-nav-dropdown">
                <a class="c-sidebar-nav-link"
                   href="{{ route('admin.users.index') }}">
                    <svg class="c-sidebar-nav-icon">
                        <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-puzzle') }}"></use>
                    </svg> {{ __('Users') }}
                </a>
            </li>
        @else
            @foreach ($user_menu as $group)
                <li class="c-sidebar-nav-title">{{ $group['name'] }}
                    @if ($group['is_new'])
                        <span class="badge badge-info">NEW</span>
                    @elseif ($group['is_updated'])
                        <span class="badge badge-info">UPD</span>
                    @endif
                </li>
                @foreach ($group['checklists'] as $checklist)
                    <li class="c-sidebar-nav-item">
                        <a class="c-sidebar-nav-link"
                           href="{{ route('user.checklists.show', [$checklist['id']]) }}">
                            <svg class="c-sidebar-nav-icon">
                                <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-list') }}"></use>
                            </svg>
                            {{ $checklist['name'] }}
                            @livewire('completed-tasks-counter', [
                                'completed_tasks' => count($checklist['user_tasks']),
                                'tasks_count' => count($checklist['tasks']),
                                'checklist_id' => $checklist['id']
                            ])

                            @if ($checklist['is_new'])
                                <span class="badge badge-info">NEW</span>
                            @elseif ($checklist['is_updated'])
                                <span class="badge badge-info">UPD</span>
                            @endif
                        </a>
                    </li>
                @endforeach
            @endforeach
        @endif
    </ul>
    <button class="c-sidebar-minimizer c-class-toggler" type="button" data-target="_parent"
            data-class="c-sidebar-minimized"></button>
</div>
