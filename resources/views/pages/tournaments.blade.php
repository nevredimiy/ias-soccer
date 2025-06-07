@extends('layouts.app')

@section('title', 'Турніри')

@section('content')

<livewire:dependent-dropdown />
    <div class="page__container">
        <div class="page__wrapper">
            <livewire:tournament-events />
            <livewire:tournament-info />
        </div>
    </div>
@endsection	
   