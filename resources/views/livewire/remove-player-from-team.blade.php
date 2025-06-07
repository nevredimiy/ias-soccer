<div>
    @if ($confirming)
        <div wire:click="cancel" class="modal">
            <div class="modal__block" >
                <p>Ви впевнені, що хочете видалити гравця <strong>{{ $player['full_name'] }}</strong> із команди <strong>{{ $team->name }}</strong>?</p>
                <div class="mt-2 flex gap-2">
                    <button wire:click.stop="remove" class="players-tournament__label red-bg">Видалати</button>
                    <button wire:click="cancel" class="players-tournament__label gray-bg">Відміна</button>
                </div>
            </div>
        </div>
    @else
        <button wire:click="confirm" class="players-tournament__label red-bg">ВИДАЛИТИ</button>
    @endif
</div>
