<div wire:ignore.self class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="mailModal" tabindex="-1" aria-labelledby="mailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <form wire:submit.prevent="send" enctype="multipart/form-data">
                <div class="modal-header">
                    <div class="modal-title" id="mailModalLabel" style="line-height: 1.1; margin-right: 10px;">
                        <h6>
                            <i class="bi-send-fill text-secondary" style="font-size: 20px;"></i>
                            SUGESTÃO
                        </h6>

                        <div class="text-muted" style="font-size: 8pt;">
                            <x-slot:identifier>

                            </x-slot>
                        </div>
                    </div>

                    <button type="button" wire:loading.attr="disabled" wire:click="closeModal" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
{{-- conteúdo --}}
<div class="row g-3" style="margin-bottom: 10px;">
    <div class="col-sm-12">
        <textarea wire:model="comment" class="form-control form-control-sm" id="comment" rows="6"></textarea>

        <x-layout.modal.modal-mail-body-group-item-count :comment="$comment"/>

        <x-layout.modal.modal-mail-body-group-item-error item="comment" message="$message"/>
    </div>
</div>

<x-layout.modal.modal-add-body-group>
    <x-layout.modal.modal-add-body-group-item columms="12">
        <x-layout.modal.modal-add-body-group-item-bool model="identify">
            <div style="margin-top: 5px;">
                <span class="text-danger fw-bold" style="font-size: 9pt;">DESEJA IDENTIFICAR-SE?</span>
            </div>
            <input wire:model="identify" class="form-check-input" type="checkbox" role="switch" id="identify">

            <x-slot:label>
                @if($identify)
                    <x-layout.modal.modal-add-body-group-item-bool-true title="SIM"/>
                @else
                    <x-layout.modal.modal-add-body-group-item-bool-false title="NÃO" />
                @endif
            </x-slot>
        </x-layout.modal.modal-add-body-group-item-bool>
    </x-layout.modal.modal-add-body-group-item>
</x-layout.modal.modal-add-body-group>
{{-- conteúdo --}}
                </div>

                <x-layout.modal.modal-mail-footer/>
            </form>
        </div>
    </div>
</div>
