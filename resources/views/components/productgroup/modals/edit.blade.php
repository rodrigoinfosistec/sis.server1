<x-layout.modal.modal-edit modal="edit" size="">
    <x-layout.modal.modal-edit-header icon="bi-pencil-square" modal="edit">
        {{ $config['title'] }}

        <x-slot:identifier>
            ID: {{ $productgroup_id }}

            <i class="bi-caret-right-fill text-muted" style="font-size: 8px;"></i>

            {{ $code }}-{{ $origin }}
        </x-slot>
    </x-layout.modal.modal-edit-header>

    <x-layout.modal.modal-edit-body method="modernize">

{{-- conteúdo --}}
<x-layout.modal.modal-edit-body-group>
    <x-layout.modal.modal-edit-body-group-item columms="6">
        <x-layout.modal.modal-add-body-group-item-label item="code" title="CÓDIGO" plus="none"/>

        <input type="text" wire:model="code" class="form-control form-control-sm" id="code">

        <x-layout.modal.modal-edit-body-group-item-error item="code" message="$message"/>
    </x-layout.modal.modal-edit-body-group-item>

    <x-layout.modal.modal-edit-body-group-item columms="6">
        <x-layout.modal.modal-edit-body-group-item-label item="origin" title="ORIGEM" plus="none"/>

        <select wire:model="origin" class="form-select form-select-sm text-uppercase" id="origin">
            <x-layout.modal.modal-edit-body-group-item-option-muted/>

            <option value="NACIONAL">NACIONAL</option>
            <option value="IMPORTADO">IMPORTADO</option>
        </select>

        <x-layout.modal.modal-edit-body-group-item-error item="origin" message="$message"/>
    </x-layout.modal.modal-edit-body-group-item>
</x-layout.modal.modal-edit-body-group>

<x-layout.modal.modal-edit-body-group>
    <x-layout.modal.modal-edit-body-group-item columms="12">
        <x-layout.modal.modal-add-body-group-item-label item="name" title="DESCRIÇÃO" plus="none"/>

        <input type="text" wire:model="name" class="form-control form-control-sm" id="name">

        <x-layout.modal.modal-edit-body-group-item-error item="name" message="$message"/>
    </x-layout.modal.modal-edit-body-group-item>
</x-layout.modal.modal-edit-body-group>
{{-- conteúdo --}}

    </x-layout.modal.modal-edit-body>

    <x-layout.modal.modal-edit-footer/>
</x-layout.modal.modal-edit>
