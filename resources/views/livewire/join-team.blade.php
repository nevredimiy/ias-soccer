
<button @if(!$applied) wire:click="apply" @endif
        class="reg-tournament__button button button--yellow @if($applied) disabled @endif"
        :class="{ 'button--gray': @js($applied), 'bg-blue-500': !@js($applied) }"
        
       >
    
    @if ($isInTeam)
        Ви в команді
    @elseif(!$applied && !$isInTeam)
        Хочу грати
     @elseif ($applied)
        Заявка відправлена
    @endif
    
</button>