@extends('layouts.app')

@section('content')
    <div class="article__container">
        <div class="flex justify-end">
            <a href="{{ asset('files/reglament.docx') }}" class="team-setup__pay-button button button--blue">
                Скачати регламент
            </a>
        </div>
        <h1>{{ $article->title }}</h1>
        <div>{!! $article->content !!}</div>
        <a href="{{ url('/') }}">На главную</a>
    </div>
@endsection
