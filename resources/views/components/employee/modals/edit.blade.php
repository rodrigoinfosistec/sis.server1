<x-layout.modal.modal-edit modal="edit" size="">
    <x-layout.modal.modal-edit-header icon="bi-pencil-square" modal="edit">
        {{ $config['title'] }}

        <x-slot:identifier>
            PIS {{ $pis }}
            <br>
            {{ $name }}
        </x-slot>
    </x-layout.modal.modal-edit-header>

    <x-layout.modal.modal-edit-body method="modernize">

{{-- conteúdo --}}
<x-layout.modal.modal-edit-body-group>
    <x-layout.modal.modal-edit-body-group-item columms="8">
        <x-layout.modal.modal-edit-body-group-item-label item="pis" title="PIS" plus="none"/>

            <input type="text" wire:model="pis" class="form-control form-control-sm" id="pis" onKeyUp="maskPis(this, event)" disabled>

        <x-layout.modal.modal-edit-body-group-item-error item="pis" message="$message"/>
    </x-layout.modal.modal-edit-body-group-item>

    <x-layout.modal.modal-edit-body-group-item columms="4">
        <x-layout.modal.modal-edit-body-group-item-label item="name" title="NOME" plus="none"/>

            <input type="text" wire:model="name" class="form-control form-control-sm" id="name">

        <x-layout.modal.modal-edit-body-group-item-error item="name" message="$message"/>
    </x-layout.modal.modal-edit-body-group-item>
</x-layout.modal.modal-edit-body-group>

<x-layout.modal.modal-edit-body-group>
    <x-layout.modal.modal-edit-body-group-item columms="6">
        <x-layout.modal.modal-edit-body-group-item-label item="journey_start_week" title="JORNADA SEMANA (INÍCIO)" plus="none"/>

        <input type="time" wire:model="journey_start_week" class="form-control form-control-sm" id="journey_start_week">

        <x-layout.modal.modal-edit-body-group-item-error item="journey_start_week" message="$message"/>
    </x-layout.modal.modal-edit-body-group-item>

    <x-layout.modal.modal-edit-body-group-item columms="6">
        <x-layout.modal.modal-edit-body-group-item-label item="journey_end_week" title="JORNADA SEMANA (FINAL)" plus="none"/>

        <input type="time" wire:model="journey_end_week" class="form-control form-control-sm" id="journey_end_week">

        <x-layout.modal.modal-edit-body-group-item-error item="journey_end_week" message="$message"/>
    </x-layout.modal.modal-edit-body-group-item>
</x-layout.modal.modal-edit-body-group>

<x-layout.modal.modal-edit-body-group>
    <x-layout.modal.modal-edit-body-group-item columms="6">
        <x-layout.modal.modal-edit-body-group-item-label item="journey_start_saturday" title="JORNADA SÁBADO (INÍCIO)" plus="none"/>

        <input type="time" wire:model="journey_start_saturday" class="form-control form-control-sm" id="journey_start_saturday">

        <x-layout.modal.modal-edit-body-group-item-error item="journey_start_saturday" message="$message"/>
    </x-layout.modal.modal-edit-body-group-item>

    <x-layout.modal.modal-edit-body-group-item columms="6">
        <x-layout.modal.modal-edit-body-group-item-label item="journey_end_saturday" title="JORNADA SÁBADO (FINAL)" plus="none"/>

        <input type="time" wire:model="journey_end_saturday" class="form-control form-control-sm" id="journey_end_saturday">

        <x-layout.modal.modal-edit-body-group-item-error item="journey_end_saturday" message="$message"/>
    </x-layout.modal.modal-edit-body-group-item>
</x-layout.modal.modal-edit-body-group>
{{-- conteúdo --}}

    </x-layout.modal.modal-edit-body>

    <x-layout.modal.modal-edit-footer/>
</x-layout.modal.modal-edit>