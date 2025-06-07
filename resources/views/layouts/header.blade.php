
<header class="header">
    <div class="header__container">
        <div class="header__images">
            <a href="{{ route('home') }}" class="header__logo">
                <img class="ibg ibg--contain" src="{{ asset('storage/' . optional(App\Models\SiteSetting::first())->logo) }}" alt="Logo">
            </a>
        </div>
        <div class="header__menu">
            <div class="header__block">
                <!--Активному пукнту додати клас _active-->
                <a href="{{ route('teams.series') }}" class="header__link button _icon-ch-right">
                    <span>Заявити команду</span>
                </a>
                <a href="{{ route('players.series') }}" class="header__link button _icon-ch-right">
                    <span>Заявка гравця</span>
                </a>
            </div>

            <a href="{{route('cities')}}">
                <livewire:city-emblem />
            </a>


            <div class="header__block">
                @auth
                    <!-- Пользователь авторизован -->
                    <div class="header__profile profile-header">
                        <div class="profile-header__image">
                            <img src="{{ auth()->user()->avatar ?? asset('/img/header/profile.webp') }}" 
                                 alt="{{ auth()->user()->name }}" 
                                 class="ibg">
                        </div>
                        <div class="profile-header__label">
                           @livewire('user-balance')
                        </div>
                        <a href="{{ route('balance.form') }}" class="profile-header__add _icon-add-circle"></a>
            
                        <!-- Кнопка проверки баланса через Livewire -->
                        @livewire('check-balance')

                    </div>
            
                    <a href="{{ route('profile') }}" class="header__link button button--transparent _icon-user-circle">
                        <span>Профіль</span>
                    </a>
                    
                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
                        @csrf
                         <button type="submit" class="header__link button button--transparent _icon-logout">
                            <span>Вихід</span>
                         </button>
                    </form>
                @else
                    <!-- Гость (не авторизован) -->
                    <a href="{{ route('register') }}" class="header__link button button--transparent _icon-user-add">
                        <span>Реєстрація</span>
                    </a>
                    <a href="{{ route('login') }}" class="header__link button button--transparent _icon-login">
                        <span>Вхід</span>
                    </a>
                @endauth
            </div>
            
            
        </div>
        <div class="header__contacts">
            <a href="tel:{{ $siteSettings->contacts }}" class="header__phone">{{ $phone }}</a>
            <div class="header__social social">
                @if ($siteSettings->facebook)
                <a href="{{$siteSettings->facebook}}" class="social__item _icon-s-fb">                    
                </a>
                @endif
                @if ($siteSettings->instagram)
                <a href="{{$siteSettings->instagram}}" class="social__item _icon-s-inst">
                </a>                    
                @endif
            </div>
        </div>
    </div>
</header>

@include('layouts.navbar')


