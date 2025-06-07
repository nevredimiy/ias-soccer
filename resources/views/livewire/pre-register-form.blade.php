
<div class="login-section__block">
    <h2 class="title">Регістрація гравця</h2>

    <form wire:submit.prevent="submit">
        <div class="account__body">
            <div class="account__field">
                <label class="account__label">Email</label>
                <input type="email" wire:model.defer="email" class="account__input input">
                @error('email') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
            </div>

            <div class="account__field">
                <label class="account__label">Ім'я</label>
                <input type="text" wire:model.defer="firstname" class="account__input input">
                @error('firstname') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
            </div>

            <div class="account__field">
                <label class="account__label">Прізвище</label>
                <input type="text" wire:model.defer="lastname" class="account__input input">
                @error('lastname') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
            </div>
    
            <div class="account__field">
                <label class="account__label">Пароль</label>
                <input type="password" wire:model.defer="password" class="account__input input">
                @error('password') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
            </div>
    
             <div class="account__field">
                <label class="account__label">День народження</label>
                <input type="date" wire:model.defer="birthday" class="account__input input" min="1950-01-01" max="2018-12-31">
                @error('birthday') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
            </div>

            <div class="account__field">
                <label class="account__label">Телефон</label>
                <input type="text" wire:model.defer="phone" class="account__input input">
                @error('phone') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
            </div>
    
            <div class="account__field">
                <label class="account__label">Telegram</label>
                <input type="text" wire:model.defer="tg" class="account__input input">
                @error('tg') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
            </div>
    
            <div class="account__field">
                <label class="account__label">Рейтинг (1-10)</label>
                <input type="number" wire:model.defer="rating" class="account__input input" min="1" max="10">
                @error('rating') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
            </div>
    
            <button type="submit" class="account__button button button--green flex-content-center">
                Завершити регістрацію
            </button>

        </div>
    </form>
</div>
