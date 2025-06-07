@extends('layouts.app')

@section('title', 'Підтвердження email')

@section('content')
<div class="container block-center">
    <div class="login-section__block">
        <h2 class="title">Підтвердження email</h2>
        <p class="text">Будь ласка, підтвердіть вашу електронну адресу. Ми надіслали вам листа.</p>
        @if (session('status'))
            <p style="color: green;">{{ session('status') }}</p>
        @endif
        <form class="flex flex-col" method="POST" action="{{ route('verification.resend') }}">
            @csrf
            <button class="account__button button button--green flex-content-center" type="submit">Надіслати повторно</button>
        </form>
    </div>
</div>
@endsection