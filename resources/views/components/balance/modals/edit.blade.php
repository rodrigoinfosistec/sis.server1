<x-layout.modal.modal-edit modal="edit" size="modal-fullscreen">
    <x-layout.modal.modal-edit-header icon="bi-pencil-square" modal="edit">
        {{ $config['title'] }}

        <x-slot:identifier>
            BALANÇO<i class="bi bi-caret-right-fill"></i> <span class="text-dark fw-bold">{{ $balance_id }}</span>
            <br>
            DEPÓSITO<i class="bi bi-caret-right-fill"></i> <span class="text-dark fw-bold">{{ $deposit_name }}</span>
            <br>
            FORNECEDOR<i class="bi bi-caret-right-fill"></i> <span class="text-dark fw-bold">{{ $provider_name }}</span>
        </x-slot>
    </x-layout.modal.modal-edit-header>

    <x-layout.modal.modal-edit-body method="modernize">

{{-- conteúdo --}}
<x-layout.modal.modal-edit-body-group>
    
</x-layout.modal.modal-edit-body-group>
{{-- conteúdo --}}

    </x-layout.modal.modal-edit-body>

    <x-layout.modal.modal-edit-footer/>
</x-layout.modal.modal-edit>
