@extends('layouts.app')

@section('title', 'Забув пароль')

@section('content')
<div class="container block-center">
    @if ($errors->any())
    <div style="color: red; padding: 12px; text-align: center">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif
    <div class="login-section__block @if ($errors->has('email') || $errors->has('password')) is-invalid @endif">
        <h2 class="title">Надіслати посилання для скидання пароля</h2>
       
    
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="account__body">
                <div class="account__field">
                    <label class="account__label" for="email">Email адреса</label>
                    <input class="account__input input @error('email') is-invalid @enderror" type="email" name="email" id="email" value="{{ old('email') }}" class="form-control">
                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            
                <button type="submit" class="account__button button button--green flex-content-center">Надіслати</button>

            </div>
        </form>
        
    </div>
</div>
@endsection
