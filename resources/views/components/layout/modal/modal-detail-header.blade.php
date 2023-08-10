<div class="modal-header">
    <div class="modal-title" id="{{ $modal }}ModalLabel" style="line-height: 1.1; margin-right: 10px;">
        <h5>
            <i class="{{ $icon }} text-secondary" style="font-size: 20px;"></i>
            Detalhes {{ $slot }}
        </h5>

        <div class="text-muted" style="font-size: 8pt;">
            {{ $identifier }}
        </div>
    </div>

    <button type="button" wire:click="closeModal" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
