@extends('layouts.app')

@section('title', 'Контакти')

@section('content')
<div class="page__container">
    <div class="page__wrapper">
        <div class="page__contacts contacts">
            <div class="contacts__block _block">
                <div class="contacts__items">
                    @foreach ($contacts as $contact)
                        <div class="contacts__item item-contacts">
                            <div class="item-contacts__image">
                                <img src="{{asset('storage/' . $contact->photo)}}" alt="Image" class="ibg ibg--contain">
                            </div>
                            <div class="item-contacts__body">
                                <h3 class="item-contacts__title">
                                    {{ $contact->full_name }}
                                </h3>
                                <div class="item-contacts__label">
                                    {{ $contact->position }}
                                </div>
                                <div class="item-contacts__label">
                                    <a href="tel:{{$contact->phone}}">{{ $contact->phone }}</a>
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