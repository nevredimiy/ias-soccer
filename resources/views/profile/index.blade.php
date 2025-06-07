@extends('layouts.app')

@section('title', 'Мій профіль')

@section('content')
<div class="container block-center">

    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if (session()->has('notice'))
        <div class="alert alert-notice">
            {{ session('notice') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif
    

   
    {{-- <form method="POST" action="{{ route('liqpay.pay') }}">
        @csrf
        <input type="number" name="amount" placeholder="Сумма" required class="border p-2">
        <button type="submit" class="bg-green-500 text-white px-4 py-2">Пополнить через LiqPay</button>
    </form> --}}
    

    <div class="page__container">
        <div class="page__wrapper">
            <div class="page__profile profile">
                <div class="profile__block _block">
                    <div class="flex justify-center flex-col items-center mb-4">
                        <h2 class="hero-tournament__label hero-tournament__label--big">Мій профіль</h2>
                        <p>Вітаємо, {{ auth()->user()->name }}!</p>
                    </div>
                    <div class="profile__hero hero-tournament">
                        <div class="hero-tournament__image">
                            <img src="{{ asset('storage/' . $player->photo) }}" alt="Image" class="{{auth()->user()->name}}">
                        </div>
                        <div class="hero-tournament__body">
                            <div class="hero-tournament__info">
                                <div class="hero-tournament__label hero-tournament__label--big">
                                    <span>{{ $player->last_name }} {{ $player->first_name }}</span>
                                </div>
                            </div>
                            <div class="hero-tournament__info">
                                <div class="hero-tournament__label">
                                    Email: {{ auth()->user()->email }}
                                </div>
                            </div>
                            <div class="hero-tournament__info">
                                <div class="hero-tournament__label">
                                    {{ $formattedBithDate }} 
                                </div>
                            </div>
                            <div class="hero-tournament__info">

                                <div class="hero-tournament__label">
                                    РІВЕНЬ ПІДГОТОВКИ
                                </div>
                                <form class="flex flex-col gap-2 items-center" action="{{route('profile.updateRating')}}" method="post">
                                    @csrf
                                    <div data-rating="set" data-rating-size="10" data-rating-value="{{ $player->rating}}" class="hero-tournament__rating rating"></div>
                                    <button class="hero-tournament__button button button--green">
                                        <span>ЗМІНИТИ РІВЕНЬ</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                       
                    </div>
                    <div class="hero-tournament__info flex justify-center mb-8">
                        <div class="button button--blue">
                            <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                              </svg>
                              
                            <a href="{{route('players.edit')}}">Редагувати дані</a>
                        </div>
                    </div>

                    @if ($user->role == 'manager' || $user->role == 'admin')
                    <div class="hero-tournament__info flex justify-center mb-8">
                        <div class="button button--red">
                            <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                              </svg>
                              
                            <a href="{{route('manager.series')}}">Редагувати статистику матчів</a>
                        </div>
                    </div>
                        
                    @endif

                    @if ($user->role == 'admin')
                        @livewire('tournament-list')                        
                    @endif
                    


                    <section class="profile__teams teams-profile">                        
                        <h2 class="teams-profile__title section-title section-title--margin">
                            УПРАВЛІННЯ КОМАНДАМИ
                        </h2>                          
                        @if($user->teams->isNotEmpty() || $player->team_id > 0 || count($player->teams) > 0)

                              <livewire:team-list />

                        @else
                            <div class="teams-profile__none">
                                ПОКИ ЩО НЕМАЄ СТВОРЕНИХ КОМАНД
                            </div>
                        @endif                            
                    </section>
                    
                    @if($user->teams->isNotEmpty() || count($player->teams) > 0)
                        <livewire:team-details />
                    @endif 
                    
                </div>
            </div>
        </div>
    </div>

</div>
@endsection