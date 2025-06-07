@extends('layouts.app')

@section('title', 'Таблиці')

@section('content')

<livewire:dependent-dropdown />

<div class="tables__container _block">

    @livewire('tables-page')
    
@endsection