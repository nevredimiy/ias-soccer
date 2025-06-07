<div class="prop-profile__block">
    <h2 class="prop-profile__title section-title section-title--margin">Бажаючі приєднатися до вашої
        команди
    </h2>
    <div class="prop-profile__subtitle label-prop">
        Залишок часу на прийняття рішення
    </div>

    <div class="prop-profile__body">

        <div class="prop-profile__requests">

            @if(session()->has('message'))
                <p class="text-green-500">{{ session('message') }}</p>
            @endif

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @forelse ($playerApplications as $application)
                @foreach ($application['user']['player'] as $player)
                    @livewire('player-request', ['player' => $player, 'application' => $application], key($player['id']))
                @endforeach
            @empty
                <div class="text-gray-500 m-auto">Немає жодної заявки</div>
            @endforelse
        </div>
        
    </div>
</div>