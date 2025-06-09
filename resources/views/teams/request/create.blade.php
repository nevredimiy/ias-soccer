@extends('layouts.app')

@section('title', 'Створення команди')

@section('content')
<div class="page__container">
    <div class="page__wrapper">
        <div class="page__tournament tournament">
            <div class="tournament__block _block">
                <section class="tournament__join-team join-team">

                    <div class="page__team-setup team-setup">
                        @if ($errors->any())
                        <div style="color: red; padding: 12px; text-align: center">
                                @foreach ($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif
                        <form action="{{ url('/teams/request/store') }}" method="post" class="team-setup__block _block" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="event_id" value="{{ $eventId }}">
                            <input type="hidden" name="price" value="{{ $price }}">
                            <div class="team-setup__name">
                                <div class="team-setup__label team-setup__label--big">
                                    НАЗВА КОМАНДИ
                                </div>
                                <input type="text" placeholder="Назва команди" name="name" class="team-setup__input" value="{{ old('name') }}">
                            </div>
                          
                            {{-- <livewire:team-logo-uploader /> --}}
        
                            <div class="team-setup__footer">
                                <div class="team-setup__field">
                                    <div class="team-setup__label">
                                        ВИБРАТИ КОЛІР КОМАНДИ
                                    </div>
                                    <div class="team-setup__colors">
                                        @foreach ($colors as $color)
                                            @php
                                                $isDisable = $teams->where('color_id', $color->id)->isNotEmpty();
                                            @endphp
                                            <label style="--color: {{ $color->color_picker }}" class="team-setup__color">
                                                <input {{ $isDisable ? 'disabled' : ''}}
                                                    type="radio" 
                                                    value="{{ $color->id }}" 
                                                    name="color_id"
                                                    {{ old('color_id') == $color->id ? 'checked' : '' }}
                                                >
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                                @if($isPromoCode)
                                <div class="team-setup__field">
                                    <div class="team-setup__label">
                                        ПРОМОКОД
                                    </div>
                                    <div class="team-setup__code-input input-code">
                                        <input name="promo_code_1" type="text" maxlength="1" />
                                        <input name="promo_code_2" type="text" maxlength="1" />
                                        <input name="promo_code_3" type="text" maxlength="1" />
                                        <input name="promo_code_4" type="text" maxlength="1" />
                                    </div>
                                </div>
                                @endif
                                <div class="team-setup__field">
                                    <div class="team-setup__label">
                                        ВНЕСОК: <span>{{ $price }} ГРН</span>
                                    </div>
                                    <button type="submit" class="team-setup__pay-button button button--blue">
                                        Оплатити внесок
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
@endsection