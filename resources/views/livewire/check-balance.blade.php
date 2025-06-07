<div class="relative">
    <button wire:click="checkPayment" class="profile-header__add _icon-lock">
       
    </button>
    @if ($statusMessage)
        <p class="text-success">{{ $statusMessage }}</p>
    @endif
</div>

<script>
    document.addEventListener('livewire:load', function () {
        Livewire.on('balanceUpdated', balance => {
            document.getElementById('balance-display').innerText = balance + ' грн';
        });
    });
</script>
