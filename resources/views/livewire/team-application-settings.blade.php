<div class="prop-profile__block flex flex-col justify-center items-center">
    <h2 class="prop-profile__title section-title section-title--margin">Налаштування часу для заявок</h2>

    @if (session()->has('success'))
        <div class="bg-green-100 text-green-800 p-2 rounded mb-2">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="save" class="space-y-3">
        <div class="time">
            <div class="time__field">
                <label for="days" class="time__title">Дні</label>
                <input type="number" id="days" wire:model="days" class="input" min="0">
            </div>
    
            <div class="time__field">
                <label for="hours" class="time__title">Години</label>
                <input type="number" id="hours" wire:model="hours" class="input" min="0" max="23">
            </div>
    
            <div class="time__field">
                <label for="minutes" class="time__title">Хвилини</label>
                <input type="number" id="minutes" wire:model="minutes" class="input" min="0" max="59">
            </div>            
        </div>
        <div class="time__field">
            <button type="submit" class="button button--green">
                Зберегти
            </button>
        </div>
    </form>
</div>
