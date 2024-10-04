<x-layout.modal.modal-add modal="add" method="register" size="">
    <x-layout.modal.modal-add-header icon="bi-fingerprint" modal="add">
        Registro de Ponto

        <x-slot:identifier>
            {{-- ... --}}
        </x-slot>
    </x-layout.modal.modal-add-header>

    <x-layout.modal.modal-add-body method="register">

{{-- conteúdo --}}
        <span class="" style="font-size: 15pt;">
            <span class="text-danger">Registrar ponto para:</span>
            <br>
            {{ $register_employee_name }}
            <br>
            <span class="text-primary" style="font-size: 12pt;">{{ date('H:i') }}</span>
        </span>
{{-- conteúdo --}}

    </x-layout.modal.modal-add-body>

    <div class="modal-footer">
        <button wire:loading.attr="disabled" type="submit" class="btn btn-sm btn-dark">
            <span wire:loading class="spinner-border spinner-border-sm" role="status"></span>
            Registrar
        </button>
    </div>
</x-layout.modal.modal-add>
