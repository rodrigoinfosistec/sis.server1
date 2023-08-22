<x-layout.modal.modal-add modal="add" method="register" size="">
    <x-layout.modal.modal-add-header icon="bi-plus-circle-fill" modal="add">
        {{ $config['title'] }}

        <x-slot:identifier>
            {{-- ... --}}
        </x-slot>
    </x-layout.modal.modal-add-header>

    <x-layout.modal.modal-add-body method="register">

{{-- conteúdo --}}
<x-layout.modal.modal-add-body-group>
    <x-layout.modal.modal-add-body-group-item columms="6">
        <x-layout.modal.modal-add-body-group-item-label item="cnpj" title="CNPJ" plus="none"/>

        <input type="text" wire:model="cnpj" class="form-control form-control-sm" id="cnpj" onKeyUp="maskCnpj(this, event)">

        <x-layout.modal.modal-add-body-group-item-error item="cnpj" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>

    <x-layout.modal.modal-add-body-group-item columms="6">
        <x-layout.modal.modal-add-body-group-item-label item="price" title="PREÇO TIPO" plus="none"/>

        <select wire:model="price" class="form-select form-select-sm text-uppercase" id="price">
            <x-layout.modal.modal-add-body-group-item-option-muted/>

            <option value="1">1</option>
            <option value="2">2</option>
        </select>

        <x-layout.modal.modal-add-body-group-item-error item="price" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>
</x-layout.modal.modal-add-body-group>

<x-layout.modal.modal-add-body-group>
    <x-layout.modal.modal-add-body-group-item columms="12">
        <x-layout.modal.modal-add-body-group-item-label item="name" title="RAZÃO SOCIAL" plus="none"/>

        <input type="text" wire:model="name" class="form-control form-control-sm" id="name">

        <x-layout.modal.modal-add-body-group-item-error item="name" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>
</x-layout.modal.modal-add-body-group>

<x-layout.modal.modal-add-body-group>
    <x-layout.modal.modal-add-body-group-item columms="12">
        <x-layout.modal.modal-add-body-group-item-label item="nickname" title="NOME FANTASIA (opcional)" plus="none"/>

        <input type="text" wire:model="nickname" class="form-control form-control-sm" id="nickname">

        <x-layout.modal.modal-add-body-group-item-error item="nickname" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>
</x-layout.modal.modal-add-body-group>
{{-- conteúdo --}}

    </x-layout.modal.modal-add-body>

    <x-layout.modal.modal-add-footer/>
</x-layout.modal.modal-add>
