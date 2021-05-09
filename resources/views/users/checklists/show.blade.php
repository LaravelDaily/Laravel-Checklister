@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="fade-in">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                        {{ $checklist->name }}
                        </div>
                        <div class="card-body">
                            <table class="table">
                                @foreach($checklist->tasks as $task)
                                    <tr>
                                        <td></td>
                                        <td class="task-description-toggle"
                                            data-id="{{ $task->id }}">{{ $task->name }}</td>
                                        <td>
                                            <svg id="task-caret-top-{{ $task->id }}" class="c-sidebar-nav-icon">
                                                <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-caret-top') }}"></use>
                                            </svg>
                                            <svg id="task-caret-bottom-{{ $task->id }}" class="c-sidebar-nav-icon d-none">
                                                <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-caret-bottom') }}"></use>
                                            </svg>
                                        </td>
                                    </tr>
                                    <tr class="d-none" id="task-description-{{ $task->id }}">
                                        <td></td>
                                        <td colspan="2">{!! $task->description !!}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    $(function() {
        $('.task-description-toggle').click(function() {
            $('#task-description-' + $(this).data('id')).toggleClass('d-none');
            $('#task-caret-top-' + $(this).data('id')).toggleClass('d-none');
            $('#task-caret-bottom-' + $(this).data('id')).toggleClass('d-none');
        });
    });
</script>
@endsection
