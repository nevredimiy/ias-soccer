@extends('layouts.app')

@section('title', 'Створення команди')

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
            <form action="{{ route('players.update') }}" method="POST" class="account__block _block" enctype="multipart/form-data">
                @csrf

                <div class="account__hero">
                    <div class="account__field">
                        <div class="account__label ">
                            ФОТО
                        </div>
                        <label class="account__upload upload-btn">
                            @if($player->photo)
                                <img src="{{ asset('storage/' . $player->photo) }}" class="w-full h-full absolute inset-0 object-cover rounded">
                            @endif
                            <input id="photoInput" type="file" name="photo" accept="image/webp, image/jpeg, image/png, image/gif, image/svg+xml" /> 
                            Додати фото самостійно, або фото з'явиться автоматично після першої зіграної серії

                              <!-- Контейнер для превью фото -->
                            <div id="photoPreviewContainer" style="display:none; position: absolute; width: 100%; height: 100%;">
                                <img 
                                    id="photoPreview" 
                                    src="" 
                                    alt="Предварительный просмотр" 
                                    style="border-radius: 5px; width: 100%; height: 100%; object-fit: cover;"
                                >
                            </div>
                        </label>                       
                    </div>


                   

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
                            <input type="text" placeholder="Прізвище" name="last_name" value="{{ old('last_name', $player->last_name) }}" class="account__input input">
                        </div>
                        <div class="account__field">
                            <div class="account__label ">ІМ’Я</div>
                            <input type="text" placeholder="ІМ’Я" name="first_name" value="{{ old('first_name', $player->first_name) }}" class="account__input input">
                        </div>
                        <div class="account__field">
                            <div class="account__label ">EMAIL</div>
                            <input type="email" placeholder="EMAIL" name="email" value="{{ old('email', $user->email) }}" class="account__input input">
                        </div>
                        <div class="account__field">
                            <div class="account__label ">ДАТА НАРОДЖЕННЯ</div>
                            <div class="account__date">
                                <input type="date" name="birth_date" class="input" value="{{ old('birth_date', $player->birth_date ? $player->birth_date->format('Y-m-d') : '') }}">
                            </div>
                        </div>
                        {{-- <div class="account__field">
                            <div class="account__label ">ДАТА НАРОДЖЕННЯ</div>
                            <div class="account__date">
                                <input type="number" min="1" max="31" placeholder="01" value="{{ old('day') }}" name="day" class="account__input input">
                                <input type="number" min="1" max="12" placeholder="01" value="{{ old('month') }}" name="month" class="account__input input">
                                <input type="text" pattern="\d{4}" placeholder="1988" value="{{ old('year') }}" name="year" class="account__input input">
                            </div>
                        </div> --}}
                    </div>
                </div>
               
                <div class="account__data">
                    <div class="account__field">
                        <div class="account__label ">
                            НОМЕР ТЕЛЕФОНУ
                        </div>
                        <input type="text" pattern="\+380\d{9}" required placeholder="+380XXXXXXXXX" value="{{ old('phone', $player->phone) }}" name="phone" class="account__input input">
                    </div>
                  
                    <div class="account__field">
                        <div class="account__label ">
                            НІКНЕЙМ В TELEGRAM
                        </div>
                        <input type="text" value="{{ old('tg', $player->tg) }}" placeholder="@MAMEDOV1988" name="tg" class="account__input input">
                    </div>
                  
                    <button type="submit" class="account__button button button--green">
                        <span>Оновити дані</span>
                    </button>
                </div>
              
            </form>
        </div>
    </div>
</div>
@endsection