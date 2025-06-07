<div class="teams-profile__items">
    
    @foreach($teams as $team)
        <div wire:key="team-{{ $team->id }}" class="flex flex-col max-w-60">        
            <button  wire:click="selectTeam({{ $team->id }})" class="teams-profile__item team-card {{ $activeTeamId === $team->id ? '_active' : '' }}">
                @if ($team->status)
                    <p class="status-message {{ $team->status == 'paid' ? 'text-green-400' : 'text-red-400' }} ">
                        {{ $team->status == 'paid' ? 'Сплачено' : 'Очікування оплати' }}
                    </p>
                @endif
                <div class="flex gap-2">
                    <div style="background-color: {{$team->color->color_picker}}" class="w-4 h-4 rounded"></div>
                    <h3 class="team-card__name">{{ $team->name }}</h3>
                </div>
                <div class="team-card__logo">
                    <img src="{{ asset('storage/' . $team->logo) }}" alt="Image" class="ibg ibg--contain">
                </div>

                <x-player-rating :rating="$team->averageRating()" />

                <div class="team-card__label">
                    {{-- {{ $team->event->stadium->name }} --}}
                    {{-- {{ $team->event->seriesMeta->first()->stadium->name }} --}}
                </div>
            </button>
            
            @if ($team->owner_id == $userId)
                <livewire:team-editor :team="$team" :key="'edit-'.$team->id" />            
            @endif

            @if ($team->status != 'paid')
            <div class="flex flex-col gap-2 items-center mt-2">
                <div class="">
                    <a class="payment-btn button button--yellow" href="{{ route('teams.request.check', $team->id) }}">
                        Оновити статус оплати
                    </a>
                </div>
                <div>
                    <a  
                        data-amount="{{$team->amount}}"
                        class="payment-btn button button--yellow" 
                        href="{{ route('teams.request.payment', [$team->id, $team->price]) }}"
                    >
                        Оплатити
                    </a>
                </div>
            </div>
            @endif        
        </div>
    @endforeach
</div>
