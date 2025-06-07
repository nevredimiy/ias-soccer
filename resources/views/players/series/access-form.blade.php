@extends('layouts.app')

@section('title', 'Заявка гравця')

@section('content')
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Доступ до серії</h2>
    </x-slot>

    <div class="page__container">
        <div class="page__wrapper">
            <div class="page__code code">
                <div class="code__block _block">
                    <h1 class="code__title section-title">
                        Одноденний приватний турнір
                    </h1>
                    <div class="code__label">
                        ВВЕДІТЬ ПАРОЛЬ
                    </div>
                    @error('access_code')
                        <p class="text-red-500 text-sm mt-1 text-center">{{ $message }}</p>
                    @enderror
                    <div class="code__input input-code">
                        <form action="{{ route('players.series.access', $event->id) }}" method="POST">
                            @csrf
                            <input type="text" name="access_code1" maxlength="1" value="{{ old('access_code1') }}" />
                            <input type="text" name="access_code2" maxlength="1" value="{{ old('access_code2') }}" />
                            <input type="text" name="access_code3" maxlength="1" value="{{ old('access_code3') }}" />
                            <input type="text" name="access_code4" maxlength="1" value="{{ old('access_code4') }}" />
                            <button type="submit"
                                class="button button--blue">
                                Перевірити код
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

   
@endsection
