<x-layout.modal.modal-edit modal="editDoc" size="">
    <x-layout.modal.modal-edit-header icon="bi-archive" modal="editDoc">
        {{ $config['title'] }}

        <x-slot:identifier>
            PIS {{ $pis }}
            <br>
            {{ $name }}
        </x-slot>
    </x-layout.modal.modal-edit-header>

    <x-layout.modal.modal-edit-body method="modernizeDoc">

{{-- conteúdo --}}
<x-layout.modal.modal-edit-body-group>
    <x-layout.modal.modal-edit-body-group-item columms="6">
        <x-layout.modal.modal-edit-body-group-item-label item="cpf" title="CPF" plus="none"/>

            <input type="text" wire:model="cpf" class="form-control form-control-sm" id="cpf" onKeyUp="maskCpf(this, event)">

        <x-layout.modal.modal-edit-body-group-item-error item="cpf" message="$message"/>
    </x-layout.modal.modal-edit-body-group-item>

    <x-layout.modal.modal-edit-body-group-item columms="6">
        <x-layout.modal.modal-edit-body-group-item-label item="rg" title="RG" plus="none"/>

            <input type="text" wire:model="rg" class="form-control form-control-sm" id="rg">

        <x-layout.modal.modal-edit-body-group-item-error item="rg" message="$message"/>
    </x-layout.modal.modal-edit-body-group-item>
</x-layout.modal.modal-edit-body-group>

<x-layout.modal.modal-edit-body-group>
    <x-layout.modal.modal-edit-body-group-item columms="6">
        <x-layout.modal.modal-edit-body-group-item-label item="cnh" title="HABILITAÇÃO" plus="none"/>

            <input type="text" wire:model="cnh" class="form-control form-control-sm" id="cnh">

        <x-layout.modal.modal-edit-body-group-item-error item="cnh" message="$message"/>
    </x-layout.modal.modal-edit-body-group-item>

    <x-layout.modal.modal-edit-body-group-item columms="6">
        <x-layout.modal.modal-edit-body-group-item-label item="ctps" title="CARTEIRA DE TRABALHO" plus="none"/>

            <input type="text" wire:model="ctps" class="form-control form-control-sm" id="ctps">

        <x-layout.modal.modal-edit-body-group-item-error item="ctps" message="$message"/>
    </x-layout.modal.modal-edit-body-group-item>
</x-layout.modal.modal-edit-body-group>
{{-- conteúdo --}}

    </x-layout.modal.modal-edit-body>

    <x-layout.modal.modal-edit-footer/>
</x-layout.modal.modal-edit>
