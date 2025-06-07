<div class="mt-8">
    <div class="prop-profile__subtitle label-prop">
        СТАТУС ПОШУКУ
    </div>
    <div class="prop-profile__status status-prop">
        <label class="status-prop__item">
            Терміново потрібні гравці
            <input type="radio" name="player_request_status" value="urgent" wire:model="player_request_status" wire:change="updateStatus">
        </label>
        <label class="status-prop__item">
            Потрібні гравці
            <input type="radio" name="player_request_status" value="needed" wire:model="player_request_status" wire:change="updateStatus">
        </label>
        <label class="status-prop__item">
            Закрита заявка
            <input type="radio" name="player_request_status" value="closed" wire:model="player_request_status" wire:change="updateStatus">
        </label>
    </div>
    @if(session()->has('message'))
        <p class="text-green-500">{{ session('message') }}</p>
    @endif
</div>
