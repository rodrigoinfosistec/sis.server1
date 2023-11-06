<x-layout.modal.modal-add modal="addEmployeeDate" method="registerEmployeeDate" size="">
    <x-layout.modal.modal-add-header icon="bi-fingerprint" modal="addEmployeeDate">
        Eventos na data

        <x-slot:identifier>
            <span class="text-primary">{{ $name }}</span>
            <br>
            {{ $pis }}
        </x-slot>
    </x-layout.modal.modal-add-header>

    <x-layout.modal.modal-add-body method="registerEmployeeDate">

{{-- conteúdo --}}
<x-layout.modal.modal-add-body-group>
    <x-layout.modal.modal-add-body-group-item columms="12">
        <x-layout.modal.modal-add-body-group-item-label item="date" title="DATA" plus="none"/>

        <input type="date" wire:model="date" class="form-control form-control-sm" id="date">

        <x-layout.modal.modal-add-body-group-item-error item="date" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>
</x-layout.modal.modal-add-body-group>
<x-layout.modal.modal-add-body-group>
    <x-layout.modal.modal-add-body-group-item columms="3">
        <x-layout.modal.modal-add-body-group-item-label item="input" title="ENTRADA" plus="none"/>

        <input type="time" wire:model="input" class="form-control form-control-sm" id="input">

        <x-layout.modal.modal-add-body-group-item-error item="input" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>

    <x-layout.modal.modal-add-body-group-item columms="3">
        <x-layout.modal.modal-add-body-group-item-label item="break_start" title="INTERVALO (INÍCIO)" plus="none"/>

        <input type="time" wire:model="break_start" class="form-control form-control-sm" id="break_start">

        <x-layout.modal.modal-add-body-group-item-error item="break_start" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>

    <x-layout.modal.modal-add-body-group-item columms="3">
        <x-layout.modal.modal-add-body-group-item-label item="break_end" title="INTERVALO (FINAL)" plus="none"/>

        <input type="time" wire:model="break_end" class="form-control form-control-sm" id="break_end">

        <x-layout.modal.modal-add-body-group-item-error item="break_end" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>

    <x-layout.modal.modal-add-body-group-item columms="3">
        <x-layout.modal.modal-add-body-group-item-label item="output" title="SAÍDA" plus="none"/>

        <input type="time" wire:model="output" class="form-control form-control-sm" id="output">

        <x-layout.modal.modal-add-body-group-item-error item="output" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>
</x-layout.modal.modal-add-body-group>
{{-- conteúdo --}}

    </x-layout.modal.modal-add-body>

    <x-layout.modal.modal-add-footer/>
</x-layout.modal.modal-add>
