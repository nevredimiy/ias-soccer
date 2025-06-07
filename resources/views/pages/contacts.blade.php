@extends('layouts.app')

@section('title', 'Контакти')

@section('content')
<div class="page__container">
    <div class="page__wrapper">
        <div class="page__contacts contacts">
            <div class="contacts__block _block">
                <div class="contacts__items">
                    <div class="contacts__item item-contacts">
                        <div class="item-contacts__image">
                            <img src="img/contacts/01.webp" alt="Image" class="ibg ibg--contain">
                        </div>
                        <div class="item-contacts__body">
                            <h3 class="item-contacts__title">
                                МАКСИМ МАМЕДОВ
                            </h3>
                            <div class="item-contacts__label">
                                Організатор
                            </div>
                            <div class="item-contacts__label">
                                <a href="#">+380932735767</a>
                            </div>
                        </div>
                    </div>
                    <div class="contacts__item item-contacts">
                        <div class="item-contacts__image">
                            <img src="img/contacts/02.webp" alt="Image" class="ibg ibg--contain">
                        </div>
                        <div class="item-contacts__body">
                            <h3 class="item-contacts__title">
                                АНДРІЙ ПАЛАМАРЧУК
                            </h3>
                            <div class="item-contacts__label">
                                АДМІНІСТРАТОР
                            </div>
                            <div class="item-contacts__label">
                                <a href="#">+380934319492</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection