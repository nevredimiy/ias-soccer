@extends('layouts.app')

@section('title', 'Міста')

@section('content')
<div class="page__container">
    <div class="page__wrapper">
        <div class="page__cities cities">
            <div class="cities__block _block">
                
                    <livewire:city-selector />
                
            </div>
        </div>
    </div>
</div>
@endsection