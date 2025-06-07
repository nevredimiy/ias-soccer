@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="login-section__block">
            <h2 class="title text-center">Сплата через LiqPay</h2>
            <div class="text-center">
                {!! $form !!}
            </div>
        </div>
    </div>
@endsection