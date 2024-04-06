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
    <x-layout.modal.modal-add-body-group-item columms="10">
        <x-layout.modal.modal-add-body-group-item-label item="name" title="NOME" plus="none"/>

        <input type="text" wire:model="name" class="form-control form-control-sm" id="name">

        <x-layout.modal.modal-add-body-group-item-error item="name" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>

    <x-layout.modal.modal-add-body-group-item columms="2">
        <x-layout.modal.modal-add-body-group-item-label item="color" title="COR" plus="none"/>

        <input type="color" wire:model="color" class="form-control form-control-sm" id="color">

        <x-layout.modal.modal-add-body-group-item-error item="color" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>
</x-layout.modal.modal-add-body-group>

<x-layout.modal.modal-add-body-group>
    <x-layout.modal.modal-add-body-group-item columms="12">
        <x-layout.modal.modal-add-body-group-item-label item="link" title="LINK" plus="none"/>

        <input type="text" wire:model="link" class="form-control form-control-sm" id="link">

        <x-layout.modal.modal-add-body-group-item-error item="link" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>
</x-layout.modal.modal-add-body-group>

<x-layout.modal.modal-add-body-group>
    <x-layout.modal.modal-add-body-group-item columms="12">
        <x-layout.modal.modal-add-body-group-item-label item="icon" title="ÍCONE" plus="none"/>

        <div class="form-check">
            <input type="radio" wire:model="icon" class="form-check-input" id="icon1" value="search" checked>
            <label class="form-check-label" for="icon1">
                <i class="bi-search"></i>
            </label>
        </div>

        <div class="form-check">
            <input type="radio" wire:model="icon" class="form-check-input" id="icon2" value="search-heart"d>
            <label class="form-check-label" for="icon2">
                <i class="bi-search-heart"></i>
            </label>
        </div>

        <x-layout.modal.modal-add-body-group-item-error item="icon" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>
</x-layout.modal.modal-add-body-group>
{{-- conteúdo --}}

    </x-layout.modal.modal-add-body>

    <x-layout.modal.modal-add-footer/>
</x-layout.modal.modal-add>
