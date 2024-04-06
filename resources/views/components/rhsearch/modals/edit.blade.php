<x-layout.modal.modal-edit modal="edit" size="">
    <x-layout.modal.modal-edit-header icon="bi-pencil-square" modal="edit">
        {{ $config['title'] }}

        <x-slot:identifier>
            ID: {{ $rhsearch_id }}

            <i class="bi-caret-right-fill text-muted" style="font-size: 8px;"></i>

            {{ $name }}
        </x-slot>
    </x-layout.modal.modal-edit-header>

    <x-layout.modal.modal-edit-body method="modernize">

{{-- conteúdo --}}
<x-layout.modal.modal-edit-body-group>
    <x-layout.modal.modal-edit-body-group-item columms="10">
        <x-layout.modal.modal-edit-body-group-item-label item="name" title="NOME" plus="none"/>

        <input type="text" wire:model="name" class="form-control form-control-sm" id="name">

        <x-layout.modal.modal-edit-body-group-item-error item="name" message="$message"/>
    </x-layout.modal.modal-edit-body-group-item>

    <x-layout.modal.modal-edit-body-group-item columms="2">
        <x-layout.modal.modal-edit-body-group-item-label item="color" title="COR" plus="none"/>

        <input type="color" wire:model="color" class="form-control form-control-sm" id="color">

        <x-layout.modal.modal-edit-body-group-item-error item="color" message="$message"/>
    </x-layout.modal.modal-edit-body-group-item>
</x-layout.modal.modal-edit-body-group>

<x-layout.modal.modal-edit-body-group>
    <x-layout.modal.modal-edit-body-group-item columms="12">
        <x-layout.modal.modal-edit-body-group-item-label item="link" title="LINK" plus="none"/>

        <input type="text" wire:model="link" class="form-control form-control-sm" id="link">

        <x-layout.modal.modal-edit-body-group-item-error item="link" message="$message"/>
    </x-layout.modal.modal-edit-body-group-item>
</x-layout.modal.modal-edit-body-group>

<x-layout.modal.modal-edit-body-group>
    <x-layout.modal.modal-edit-body-group-item columms="6">
        <x-layout.modal.modal-edit-body-group-item-label item="icon" title="ÍCONE" plus="none"/>

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

        <x-layout.modal.modal-edit-body-group-item-error item="icon" message="$message"/>
    </x-layout.modal.modal-edit-body-group-item>
    
    <x-layout.modal.modal-edit-body-group-item columms="6">
        <x-layout.modal.modal-edit-body-group-item-status>
            <input wire:model="status" class="form-check-input" type="checkbox" role="switch" id="status">

            <x-slot:label>
                @if($status)
                    <x-layout.modal.modal-edit-body-group-item-status-active/>
                @else
                    <x-layout.modal.modal-edit-body-group-item-status-inactive/>
                @endif
            </x-slot>
        </x-layout.modal.modal-edit-body-group-item-status>
    </x-layout.modal.modal-edit-body-group-item>
</x-layout.modal.modal-edit-body-group>
{{-- conteúdo --}}

    </x-layout.modal.modal-edit-body>

    <x-layout.modal.modal-edit-footer/>
</x-layout.modal.modal-edit>
