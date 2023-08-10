<div class="modal-footer">
    <button type="button" wire:click="closeModal" class="btn btn-sm btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancelar</button>
    <button wire:loading.attr="disabled" type="submit" class="btn btn-sm btn-danger">
        <span wire:loading class="spinner-border spinner-border-sm" role="status"></span>
        Sim, excluir!
    </button>
</div>
