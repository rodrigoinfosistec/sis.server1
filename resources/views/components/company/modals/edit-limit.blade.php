<x-layout.modal.modal-edit modal="editLimit" size="">
    <x-layout.modal.modal-edit-header icon="bi-clock" modal="editLimit">
        {{ $config['title'] }}

        <x-slot:identifier>
            CNPJ {{ $cnpj }}
            <br>
            {{ $name }}
        </x-slot>
    </x-layout.modal.modal-edit-header>

    <x-layout.modal.modal-edit-body method="modernizeLimit">

{{-- conteúdo --}}
<x-layout.modal.modal-edit-body-group>
    <x-layout.modal.modal-edit-body-group-item columms="6">
        <x-layout.modal.modal-edit-body-group-item-label item="limit_start" title="PONTO INÍCIO (SEMANA)" plus="none"/>

        <input type="time" wire:model="limit_start" class="form-control form-control-sm" id="limit_start">

        <x-layout.modal.modal-edit-body-group-item-error item="limit_start" message="$message"/>
    </x-layout.modal.modal-edit-body-group-item>

    <x-layout.modal.modal-edit-body-group-item columms="6">
        <x-layout.modal.modal-edit-body-group-item-label item="limit_end" title="PONTO FIM (SEMANA)" plus="none"/>

        <input type="time" wire:model="limit_end" class="form-control form-control-sm" id="limit_end">

        <x-layout.modal.modal-edit-body-group-item-error item="limit_end" message="$message"/>
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
