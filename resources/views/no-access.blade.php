@extends('layouts.app')
@section('title', 'Доступ заборонено')
@section('content')
    <div class="no-access__container text-center">
        <h1>Доступ заборонено</h1>
        <p>У вас немає прав доступу до цієї сторінки</p>
        <a href="{{ session('previous_url', url('/')) }}">Повернутися назад</a>
    </div>

@endsection