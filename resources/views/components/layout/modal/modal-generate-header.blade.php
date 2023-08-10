<div class="modal-header">
    <h5 class="modal-title" id="generateModalLabel">
        <i class="bi-layers-fill text-secondary" style="font-size: 20px;"></i>
        Gerar Relatório de {{ $slot }}
    </h5>

    <button type="button" wire:loading.attr="disabled" wire:click="closeModal" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
