@extends('layouts.app')

@section('title', 'Solomon — Dashboard')

@section('topbar')
    <x-topbar title="Solomon">
        <x-slot:right>
            @include('partials.avatar', ['user' => auth()->user()])
        </x-slot:right>
    </x-topbar>
@endsection

@section('content')
    @include('habits.partials.welcome')
    @include('habits.partials.motivation-card')

    <div class="grid grid-cols-1 gap-5 lg:grid-cols-[1fr_300px]">
        @include('habits.partials.habit-list')
        @include('habits.partials.stats-sidebar')
    </div>
@endsection

@section('footer')
    @include('habits.partials.dialogs')
    @include('partials.bottom-nav', ['active' => 'habits'])
@endsection

@section('scripts')
    @include('habits.partials.scripts')
@endsection
