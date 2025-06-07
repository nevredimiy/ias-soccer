@extends('layouts.app')

@section('title', 'Стандіони')

@section('content')

<div class="page__container">
    <div class="page__wrapper">
        <div class="page__stadiums stadiums">
            <div class="stadiums__block _block">
                <div class="stadiums__items">
                    @foreach ( $stadiums as $stadium )
                        <div class="stadiums__item item-stadium">
                            <h3 class="item-stadium__title">
                                {{$stadium->name}}
                            </h3>
                            <div class="item-stadium__image">
                                <img src="{{ asset('storage/' . $stadium->photo) }}" alt="Image" class="ibg">
                            </div>
                            <div class="item-stadium__body">
                                <div class="item-stadium__location _icon-location">
                                    {{$stadium->address}}
                                </div>
                                <div class="item-stadium__details">
                                    <div class="item-stadium__info info-stadium">
                                        {{$stadium->fields_40x20}}
                                        <div class="info-stadium__icon">
                                            <div class="info-stadium__field">
                                                40х20
                                                <img src="img/stadium/01.webp" alt="Image">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item-stadium__info info-stadium">
                                        {{$stadium->fields_60x40}}
                                        <div class="info-stadium__icon">
                                            <div class="info-stadium__field">
                                                60x40
                                                <img src="img/stadium/01.webp" alt="Image">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item-stadium__info info-stadium">
                                        {{$stadium->parking_spots}}
                                        <div class="info-stadium__icon">
                                            <img src="img/stadium/02.webp" alt="Image">
                                        </div>
                                    </div>
                                    <div class="item-stadium__info info-stadium
                                        @if ($stadium->has_shower)
                                            _icon-check 
                                        @else _icon-cross
                                        @endif"
                                    >
                                        <div class="info-stadium__icon">
                                            <img src="img/stadium/03.webp" alt="Image">
                                        </div>
                                    </div>
                                    <div class="item-stadium__info info-stadium
                                        @if ($stadium->has_speaker_system)
                                            _icon-check 
                                        @else _icon-cross
                                        @endif"
                                    >
                                        <div class="info-stadium__icon">
                                            <img src="img/stadium/04.webp" alt="Image">
                                        </div>
                                    </div>
                                    <div class="item-stadium__info info-stadium
                                        @if ($stadium->has_wardrobe)
                                            _icon-check 
                                        @else _icon-cross
                                        @endif"
                                    >
                                        <div class="info-stadium__icon">
                                            <img src="img/stadium/05.webp" alt="Image">
                                        </div>
                                    </div>
                                    <div class="item-stadium__info info-stadium 
                                        @if ($stadium->has_toilet)
                                            _icon-check 
                                        @else _icon-cross
                                        @endif"
                                    >
                                        <div class="info-stadium__icon">
                                            <img src="img/stadium/06.webp" alt="Image">
                                        </div>
                                    </div>
                                </div>
                                <div class="item-stadium__contacts">
                                    @if ($stadium->phone)
                                        <img src="img/stadium/call.webp" alt="Image">
                                        <a href="{{$stadium->phone}}">{{$stadium->phone}} (АДМІНІСТРАЦІЯ)</a>                                        
                                    @endif
                                    <img class="map" src="img/stadium/map.webp" alt="Image">
                                </div>
                            </div>
                        </div>                        
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection