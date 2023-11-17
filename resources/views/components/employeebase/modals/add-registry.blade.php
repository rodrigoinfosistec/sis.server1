<x-layout.modal.modal-add modal="addRegistry" method="registerRegistry" size="">
    <x-layout.modal.modal-add-header icon="bi-fingerprint" modal="addRegistry">
        Ponto

        <x-slot:identifier>
            {{-- ... --}}
        </x-slot>
    </x-layout.modal.modal-add-header>

    <x-layout.modal.modal-add-body method="registerRegistry">

{{-- conteúdo --}}
<x-layout.modal.modal-add-body-group>
    <x-layout.modal.modal-add-body-group-item columms="12">
        <span class="text-primary">{{ $employee->name }}</span>
        <br>
        <span class="text-muted">{{ date('d/m/Y') }}</span>
        <span class="text-danger fw-bold">{{ date('H:i') }}</span>
    </x-layout.modal.modal-add-body-group-item>
</x-layout.modal.modal-add-body-group>
{{-- conteúdo --}}

    </x-layout.modal.modal-add-body>

    <div class="modal-footer">
        <button wire:loading.attr="disabled" type="submit" class="btn btn-sm btn-success">
            <span wire:loading class="spinner-border spinner-border-sm" role="status"></span>
            Salvar
        </button>
    </div>
</x-layout.modal.modal-add>
