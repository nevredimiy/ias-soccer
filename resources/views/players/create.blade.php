@extends('layouts.app')

@section('title', 'Персональні дані гравця')

@section('content')
<div class="page__container">
    
    <div class="page__wrapper">
        @if (session()->has('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div style="color: red; padding: 12px; text-align: center">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
        <div class="page__account account">
            {{-- <form action="{{ route('players.store') }}" method="POST" class="account__block _block" enctype="multipart/form-data">
                @csrf

                <div class="account__hero1">
                  

                    <script>
                    document.getElementById('photoInput').addEventListener('change', function(event) {
                        const file = event.target.files[0]; // Получаем выбранный файл
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                const img = document.getElementById('photoPreview');
                                img.src = e.target.result; // Устанавливаем превью
                                document.getElementById('photoPreviewContainer').style.display = 'block'; // Показываем контейнер
                            };
                            reader.readAsDataURL(file); // Читаем файл как Data URL
                        }
                    });
                    </script>



                    <div class="account__body">
                        <div class="account__field">
                            <div class="account__label ">Прізвище</div>
                            <input type="text" placeholder="Прізвище" name="lastname" value="{{ old('lastname') }}" class="account__input input">
                        </div>
                        <div class="account__field">
                            <div class="account__label ">ІМ’Я</div>
                            <input type="text" placeholder="ІМ’Я" name="firstname" value="{{ old('firstname') }}" class="account__input input">
                        </div>
                        <div class="account__field">
                            <div class="account__label ">ДАТА НАРОДЖЕННЯ</div>
                            <div class="account__date">
                                <input type="number" min="1" max="31" placeholder="01" value="{{ old('day') }}" name="day" class="account__input input">
                                <input type="number" min="1" max="12" placeholder="01" value="{{ old('month') }}" name="month" class="account__input input">
                                <input type="text" pattern="\d{4}" placeholder="1988" value="{{ old('year') }}" name="year" class="account__input input">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="account__rating-set">
                    <div class="account__rating-block">
                        <div class="account__label label-acount">
                            *ОБРАТИ СВІЙ РІВЕНЬ
                        </div>
                        <div  data-rating-size="10" data-rating-value="1" class="account__rating rating">
                           
                            @livewire('rating', ['rating' => old('rating', 0)])

                        </div>                        
                    </div>
                </div>
                <div class="account__data">
                    <div class="account__field">
                        <div class="account__label ">
                            НОМЕР ТЕЛЕФОНУ
                        </div>
                        <input type="text"  placeholder="+380XXXXXXXXX" value="{{ old('phone') }}" name="phone" class="account__input input">
                    </div>
                  
                    <div class="account__field">
                        <div class="account__label ">
                            НІКНЕЙМ В TELEGRAM
                        </div>
                        <input type="text" value="{{ old('tg') }}" placeholder="@MAMEDOV1988" name="tg" class="account__input input">
                    </div>
                  
                    <button type="submit" class="account__button button button--green">
                        <span>СТВОРИТИ КАБІНЕТ ГРАВЦЯ</span>
                    </button>
                </div>
              
            </form> --}}

            @livewire('player-form')

            <div class="account__rating-info">
                <h2 class="account__title section-title section-title--margin">
                    *ПРИБЛИЗНИЙ РІВЕНЬ ГРАВЦІВ ВІД 1 ДО 10
                </h2>

                @for($j=0; $j<10; $j++)
                    <div class="account__rating-block">
                        <div class="account__label">
                            @switch($j)
                                @case(0)
                                    ГРАВ ДЕКІЛЬКА РАЗ В ЖИТТІ
                                    @break
                                @case(1)
                                    ГРАЮ 1-2 РАЗИ НА РІК
                                    @break
                                @case(2)
                                    ГРАЮ 1-2 РАЗИ НА МІСЯЦЬ
                                    @break
                                @case(3)
                                    ГРАЮ 1-2 РАЗИ НА ТИЖДЕНь
                                    @break
                                @case(4)
                                    ГРАЮ 2-3 РАЗИ НА ТИЖДЕНь
                                    @break
                                @case(5)
                                    ГРАЮ 3-5 РАЗИ НА ТИЖДЕНь
                                    @break
                                @case(6)
                                    Кожного дня
                                    @break
                                @case(7)
                                    Кожного дня
                                    @break
                                @case(8)
                                    Кожного дня
                                    @break
                                @case(9)
                                    Це моя професія
                                    @break
                                @default
                                    
                            @endswitch
                            
                        </div>
                        <div data-rating-size="10" data-rating-value="1" class="account__rating rating">
                            <div class="rating__items">
                                @for ($i = 0; $i < 10; $i++)
                                    <label class="rating__item info @if ($i <= $j) rating__item--active @endif">
                                        <svg width="18" height="19" viewBox="0 0 18 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M0.669408 7.82732C0.398804 7.57384 0.545797 7.11561 0.911812 7.07165L6.09806 6.44858C6.24723 6.43066 6.3768 6.33578 6.43972 6.19761L8.62721 1.39406C8.78159 1.05505 9.25739 1.05499 9.41177 1.39399L11.5993 6.19751C11.6622 6.33568 11.7909 6.43082 11.9401 6.44873L17.1266 7.07165C17.4926 7.11561 17.6392 7.57398 17.3686 7.82745L13.5347 11.4193C13.4244 11.5226 13.3753 11.6764 13.4046 11.8256L14.4221 17.0141C14.4939 17.3803 14.1092 17.664 13.7876 17.4816L9.23042 14.8972C9.09934 14.8228 8.94009 14.8232 8.80901 14.8975L4.25138 17.481C3.92976 17.6633 3.54432 17.3802 3.61615 17.0141L4.63382 11.8259C4.66309 11.6767 4.61412 11.5226 4.50383 11.4193L0.669408 7.82732Z" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </label>
                                @endfor                               
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </div>
</div>
@endsection