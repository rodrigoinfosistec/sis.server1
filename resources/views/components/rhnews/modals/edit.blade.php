<x-layout.modal.modal-edit modal="edit" size="">
    <x-layout.modal.modal-edit-header icon="bi-pencil-square" modal="edit">
        {{ $config['title'] }}

        <x-slot:identifier>
            ID: {{ $rhnews_id }}

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
        <textarea wire:model="description" class="form-control form-control-sm" id="description" rows="6"></textarea>

        <x-layout.modal.modal-mail-body-group-item-count :comment="$description"/>

        <x-layout.modal.modal-mail-body-group-item-error item="description" message="$message"/>
    </x-layout.modal.modal-edit-body-group-item>
</x-layout.modal.modal-edit-body-group>

<x-layout.modal.modal-edit-body-group>
    <x-layout.modal.modal-edit-body-group-item columms="12">
        <x-layout.modal.modal-edit-body-group-item-label item="salute" title="SAUDAÇÃO" plus="none"/>

        <input type="text" wire:model="salute" class="form-control form-control-sm" id="salute">

        <x-layout.modal.modal-edit-body-group-item-error item="salute" message="$message"/>
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
