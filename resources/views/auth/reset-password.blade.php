@extends('layouts.app')

@section('title', 'Реєстрація')

@section('content')
<div class="container block-center">
    <div class="login-section__block">
        <h2 class="title">Реєстрація</h2>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group">
                <label for="email">Email адрес</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required class="form-control">
                @error('email')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Новий пароль</label>
                <input type="password" name="password" id="password" required class="form-control">
                @error('password')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Підтвердити пароль</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required class="form-control">
            </div>

            <button type="submit" class="button button--blue">Скинути пароль</button>
        </form>

    </div>
</div>
@endsection