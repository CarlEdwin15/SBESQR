<div>
    @if ($show)
        <div class="toast show position-fixed top-0 end-0 m-4 border border-primary shadow-lg z-50"
             role="alert" aria-live="assertive" aria-atomic="true"
             style="z-index: 9999;">
            <div class="toast-header bg-primary text-white">
                <strong class="me-auto">{{ $title }}</strong>
                <small>{{ $date }}</small>
                <button type="button" class="btn-close ms-2 mb-1" wire:click="$set('show', false)"></button>
            </div>
            <div class="toast-body text-dark">
                {{ $body }}
            </div>
        </div>
    @endif
</div>

<script>
    window.addEventListener('hide-toast', event => {
        setTimeout(() => {
            Livewire.dispatch('hide-toast');
        }, event.detail.timeout || 5000);
    });
</script>
