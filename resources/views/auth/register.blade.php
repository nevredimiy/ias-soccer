@extends('layouts.app')

@section('title', 'Реєстрація')

@section('content')
<div class="container block-center">
    <div class="login-section__block">
        <h2 class="title">Реєстрація</h2>

 
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="account__body">
                <div class="account__field">
                    <label class="account__label" for="name">Нікнейм</label>
                    <input class="account__input input @error('name') is-invalid @enderror" type="text" name="name" id="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="text-red-500 text-sm">{{ $message }}</div>
                    @enderror
                </div>
                <div class="account__field">
                    <label class="account__label" for="email">Email</label>
                    <input class="account__input input @error('email') is-invalid @enderror" type="email" name="email" id="email" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="text-red-500 text-sm">{{ $message }}</div>
                    @enderror
                    
                </div>
                <div class="account__field">
                    <label  class="account__label"for="password">Пароль</label>
                    <input class="account__input input @error('password') is-invalid @enderror" type="password" name="password" id="password" required>
                    @error('password')
                        <div class="text-red-500 text-sm">{{ $message }}</div>
                    @enderror
                </div>
                <div class="account__field">
                    <label class="account__label" for="password_confirmation">Підтвердіть пароль</label>
                    <input class="account__input input @error('password_confirmation') is-invalid @enderror" type="password" name="password_confirmation" id="password_confirmation" required>
                    @error('password_confirmation')
                        <div class="text-red-500 text-sm">{{ $message }}</div>
                    @enderror
                </div>
                <button class="account__button button button--green flex-content-center" type="submit">Зареєструватися</button>
            </div>
        </form>
    </div>
</div>
@endsection
