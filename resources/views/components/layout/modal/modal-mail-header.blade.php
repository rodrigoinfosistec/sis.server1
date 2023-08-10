<div class="modal-header">
    <h5 class="modal-title" id="mailModalLabel">
        <i class="bi-envelope-fill text-secondary" style="font-size: 20px;"></i>
        E-mail de {{ $slot }}
    </h5>

    <button type="button" wire:loading.attr="disabled" wire:click="closeModal" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
