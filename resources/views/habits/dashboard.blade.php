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
    @if (session('status'))
        <div class="mb-4 rounded-lg border border-[#2E7D32]/30 bg-[#2E7D32]/10 px-4 py-3 text-sm font-semibold text-[#2E7D32]">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 rounded-lg border border-danger/30 bg-danger/10 px-4 py-3 text-sm font-semibold text-danger">
            {{ $errors->first() }}
        </div>
    @endif

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