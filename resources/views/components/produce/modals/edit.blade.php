<x-layout.modal.modal-edit modal="edit" size="">
    <x-layout.modal.modal-edit-header icon="bi-pencil-square" modal="edit">
        {{ $config['title'] }}

        <x-slot:identifier>
            CNPJ {{ $cnpj }}
            <br>
            {{ $name }}
        </x-slot>
    </x-layout.modal.modal-edit-header>

    <x-layout.modal.modal-edit-body method="modernize">

{{-- conteúdo --}}
<x-layout.modal.modal-edit-body-group>
    <x-layout.modal.modal-edit-body-group-item columms="6">
        <x-layout.modal.modal-edit-body-group-item-label item="cnpj" title="CNPJ" plus="none"/>

        <input type="text" wire:model="cnpj" class="form-control form-control-sm" id="cnpj" onKeyUp="maskCnpj(this, event)" disabled>

        <x-layout.modal.modal-edit-body-group-item-error item="cnpj" message="$message"/>
    </x-layout.modal.modal-edit-body-group-item>

    <x-layout.modal.modal-edit-body-group-item columms="6">
        <x-layout.modal.modal-edit-body-group-item-label item="price" title="PREÇO TIPO" plus="none"/>

        <select wire:model="price" class="form-select form-select-sm text-uppercase" id="price">
            <x-layout.modal.modal-edit-body-group-item-option-muted/>

            <option value="1">1</option>
            <option value="2">2</option>
        </select>

        <x-layout.modal.modal-edit-body-group-item-error item="price" message="$message"/>
    </x-layout.modal.modal-edit-body-group-item>
</x-layout.modal.modal-edit-body-group>

<x-layout.modal.modal-edit-body-group>
    <x-layout.modal.modal-edit-body-group-item columms="12">
        <x-layout.modal.modal-edit-body-group-item-label item="name" title="RAZÃO SOCIAL" plus="none"/>

        <input type="text" wire:model="name" class="form-control form-control-sm" id="name">

        <x-layout.modal.modal-edit-body-group-item-error item="name" message="$message"/>
    </x-layout.modal.modal-edit-body-group-item>
</x-layout.modal.modal-edit-body-group>

<x-layout.modal.modal-edit-body-group>
    <x-layout.modal.modal-edit-body-group-item columms="12">
        <x-layout.modal.modal-edit-body-group-item-label item="nickname" title="NOME FANTASIA" plus="none"/>

        <input type="text" wire:model="nickname" class="form-control form-control-sm" id="nickname">

        <x-layout.modal.modal-edit-body-group-item-error item="nickname" message="$message"/>
    </x-layout.modal.modal-edit-body-group-item>
</x-layout.modal.modal-edit-body-group>
{{-- conteúdo --}}

    </x-layout.modal.modal-edit-body>

    <x-layout.modal.modal-edit-footer/>
</x-layout.modal.modal-edit>
