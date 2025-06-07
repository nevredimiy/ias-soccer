<div class="prop-profile__request request">
                       
    <div wire:key="request-{{ $player['id'] }}"  data-expire="{{ $expiresAt }}" class="request__time" x-data="{
        expiresAt: new Date('{{ $expiresAt }}').getTime(),
        now: new Date().getTime(),
        timer: '',
        timeLeft: '',
    
        init() {
            this.update();
            this.timer = setInterval(() => this.update(), 1000);
        },
    
        update() {
            let diff = this.expiresAt - new Date().getTime();
            if (diff <= 0) {
                this.timeLeft = 'Час вичерпано';
                clearInterval(this.timer);
                return;
            }
    
            let days = Math.floor(diff / (1000 * 60 * 60 * 24));
            let hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            let minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            let seconds = Math.floor((diff % (1000 * 60)) / 1000);
    
            this.timeLeft = `${days}д ${hours}г ${minutes}хв ${seconds}с`;
        }
    }"
    x-init="init()"
    >
    
        <div class="request__time text-sm text-gray-700 font-semibold">
            <span x-text="timeLeft"></span>
        </div>
    </div>

    {{-- <div  class="request__time text-sm text-gray-700 font-semibold"
     x-data="timer('{{ $expiresAt }}')"
     x-init="init()"
>
    <span x-text="timeLeft"></span>
</div> --}}

    <article class="request__body item-player item-player--stats">
        <a href="#" class="item-player__image-link">
            <img src="{{asset('storage/' . $player['photo'])}}" alt="Image" class="ibg">
        </a>
        <div class="item-player__name">
            <a href="#">{{ $player['first_name'] }} {{ $player['last_name'] }} </a>
        </div>
        <div class="item-player__details">
            <div class="item-player__info">
                31
                <img src="img/player/field.webp" alt="Image" class="ibg ibg--contain">
            </div>
            <div class="item-player__info">
                28
                <img src="img/player/ball.webp" alt="Image" class="ibg ibg--contain">
            </div>
            <div class="item-player__info item-player__info--yellow-card">
                1
            </div>
            <div class="item-player__info item-player__info--red-card">
                0
            </div>
        </div>
        <div class="item-player__rating rating rating__items">
            @for ($i = 0; $i < 10; $i++)
            <label class="rating__item @if($i < $player['rating']) rating__item--active @endif disable">
            
                <svg width="18" height="19" viewBox="0 0 18 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0.669408 7.82732C0.398804 7.57384 0.545797 7.11561 0.911812 7.07165L6.09806 6.44858C6.24723 6.43066 6.3768 6.33578 6.43972 6.19761L8.62721 1.39406C8.78159 1.05505 9.25739 1.05499 9.41177 1.39399L11.5993 6.19751C11.6622 6.33568 11.7909 6.43082 11.9401 6.44873L17.1266 7.07165C17.4926 7.11561 17.6392 7.57398 17.3686 7.82745L13.5347 11.4193C13.4244 11.5226 13.3753 11.6764 13.4046 11.8256L14.4221 17.0141C14.4939 17.3803 14.1092 17.664 13.7876 17.4816L9.23042 14.8972C9.09934 14.8228 8.94009 14.8232 8.80901 14.8975L4.25138 17.481C3.92976 17.6633 3.54432 17.3802 3.61615 17.0141L4.63382 11.8259C4.66309 11.6767 4.61412 11.5226 4.50383 11.4193L0.669408 7.82732Z" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
                
            </label>
            @endfor
        </div>
        <div class="item-player__age">{{ \Carbon\Carbon::parse($player['birth_date'])->age }}  років</div>
    </article>
    <form action="{{route('profile.updatePlayerList')}}" method="POST" class="request__footer">
        @csrf
        <input type="hidden" name="player_id" value="{{$player['id']}}">
        <input type="hidden" name="team_id" value="{{$team->id}}">
        <input type="hidden" name="user_id" value="{{$player['user_id']}}">
        
        <button type="submit" name="action" value="reject" class="request__button request__button--red">ВІДМОВА</button>
        <button type="submit" name="action" value="accept" class="request__button request__button--green">ПІДПИСАТИ</button>
    </form>
</div>

