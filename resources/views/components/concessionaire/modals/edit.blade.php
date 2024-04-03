<x-layout.modal.modal-edit modal="edit" size="">
    <x-layout.modal.modal-edit-header icon="bi-pencil-square" modal="edit">
        {{ $config['title'] }}

        <x-slot:identifier>
            ID: {{ $concessionaire_id }}

            <i class="bi-caret-right-fill text-muted" style="font-size: 8px;"></i>

            {{ $name }}
        </x-slot>
    </x-layout.modal.modal-edit-header>

    <x-layout.modal.modal-edit-body method="modernize">

{{-- conteúdo --}}
<x-layout.modal.modal-edit-body-group>
    <x-layout.modal.modal-edit-body-group-item columms="12">
        <x-layout.modal.modal-edit-body-group-item-label item="name" title="NOME" plus="none"/>

        <input type="text" wire:model="name" class="form-control form-control-sm" id="name">

        <x-layout.modal.modal-edit-body-group-item-error item="name" message="$message"/>
    </x-layout.modal.modal-edit-body-group-item>
</x-layout.modal.modal-edit-body-group>

<x-layout.modal.modal-edit-body-group>
    <x-layout.modal.modal-edit-body-group-item columms="12">
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
