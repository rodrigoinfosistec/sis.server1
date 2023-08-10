<div class="modal-footer">
    <button wire:loading.attr="disabled" wire:click="refresh" type="button" class="btn btn-sm btn-secondary" title="Atualizar página">
        <span wire:loading class="spinner-border spinner-border-sm" role="status"></span>
        <i class="bi-arrow-repeat"></i>
    </button>

    <button wire:loading.attr="disabled" type="submit" class="btn btn-sm btn-primary">
        <span wire:loading class="spinner-border spinner-border-sm" role="status"></span>
        Atualizar
    </button>
</div>
