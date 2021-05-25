@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="fade-in">
            @livewire('checklist-show', ['list_type' => $list_type])
        </div>
    </div>
@endsection
