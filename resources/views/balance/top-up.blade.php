@extends('layouts.app')

@section('content')
<div class="container block-center">
    @if ($errors->any())
    <div style="color: red; padding: 12px; text-align: center">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif
    <div class="login-section__block">
        <h2 class="title">Поповнення балансу</h2>
        <form action="{{ route('balance.process') }}" method="POST">
            @csrf
            <input type="hidden" name="return_url" value="{{ $return_url }}">
            <div class="account__body">
                <div class="account__field">
                    <label class="account__label" for="amount">Введіть суму:</label>
                    <input class="account__input input" type="number" name="amount" min="1" value="{{$amount}}" required>
                </div>
                <button class="account__button button button--green flex-content-center"  type="submit">Оплатити</button>
            </div>
        </form>
    </div>
</div>
@endsection
