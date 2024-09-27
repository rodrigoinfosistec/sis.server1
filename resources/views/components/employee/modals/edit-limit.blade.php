<x-layout.modal.modal-edit modal="editLimit" size="">
    <x-layout.modal.modal-edit-header icon="bi-archive" modal="editLimit">
        Limites do Ponto do {{ $config['title'] }}

        <x-slot:identifier>
            PIS {{ $pis }}
            <br>
            <span class="text-primary">{{ $name }}</span>
        </x-slot>
    </x-layout.modal.modal-edit-header>

    <x-layout.modal.modal-edit-body method="modernizeLimit">

{{-- conteúdo --}}
<x-layout.modal.modal-edit-body-group>
    <x-layout.modal.modal-edit-body-group-item columms="6">
        <x-layout.modal.modal-edit-body-group-item-label item="limit_start_week" title="PONTO INÍCIO (SEMANA)" plus="none"/>

        <input type="time" wire:model="limit_start_week" class="form-control form-control-sm" id="limit_start_week">

        <x-layout.modal.modal-edit-body-group-item-error item="limit_start_week" message="$message"/>
    </x-layout.modal.modal-edit-body-group-item>

    <x-layout.modal.modal-edit-body-group-item columms="6">
        <x-layout.modal.modal-edit-body-group-item-label item="limit_end_week" title="PONTO FIM (SEMANA)" plus="none"/>

        <input type="time" wire:model="limit_end_week" class="form-control form-control-sm" id="limit_end_week">

        <x-layout.modal.modal-edit-body-group-item-error item="limit_end_week" message="$message"/>
    </x-layout.modal.modal-edit-body-group-item>
</x-layout.modal.modal-edit-body-group>

<x-layout.modal.modal-edit-body-group>
    <x-layout.modal.modal-edit-body-group-item columms="6">
        <x-layout.modal.modal-edit-body-group-item-label item="limit_start_saturday" title="PONTO INÍCIO (SÁBADO)" plus="none"/>

        <input type="time" wire:model="limit_start_saturday" class="form-control form-control-sm" id="limit_start_saturday">

        <x-layout.modal.modal-edit-body-group-item-error item="limit_start_saturday" message="$message"/>
    </x-layout.modal.modal-edit-body-group-item>

    <x-layout.modal.modal-edit-body-group-item columms="6">
        <x-layout.modal.modal-edit-body-group-item-label item="limit_end_saturday" title="PONTO FIM (SÁBADO)" plus="none"/>

        <input type="time" wire:model="limit_end_saturday" class="form-control form-control-sm" id="limit_end_saturday">

        <x-layout.modal.modal-edit-body-group-item-error item="limit_end_saturday" message="$message"/>
    </x-layout.modal.modal-edit-body-group-item>
</x-layout.modal.modal-edit-body-group>
{{-- conteúdo --}}

    </x-layout.modal.modal-edit-body>

    <x-layout.modal.modal-edit-footer/>
</x-layout.modal.modal-edit>
