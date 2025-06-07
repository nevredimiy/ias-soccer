@extends('layouts.app')

@section('title', 'Серії')




@section('content')

    <livewire:dependent-dropdown />
    
    <div class="page__container">
        <div class="page__wrapper">
            <livewire:manager-series-list />
        </div>
    </div>
@endsection	
   