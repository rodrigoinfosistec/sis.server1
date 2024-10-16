<div wire:ignore.self class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="generateMovimentModal" tabindex="-1" aria-labelledby="generateMovimentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <form wire:submit.prevent="sireMoviment" enctype="multipart/form-data">
                <div class="modal-header">
                    <div class="modal-title" id="generateMovimentModalLabel" style="line-height: 1.1; margin-right: 10px;">
                        <h5>
                            <i class="bi-layers-fill text-secondary" style="font-size: 20px;"></i>
                            Movimentações do Produto
                        </h5>

                        <div class="text-primary fw-bold" style="font-size: 10pt;">
                            {{ $name }}
                        </div>
                    </div>

                    <button type="button" wire:loading.attr="disabled" wire:click="closeModal" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    GERAR RELATÓRIO COMPLETO.
                </div>

                <x-layout.modal.modal-generate-footer/>
            </form>
        </div>
    </div>
</div>
