<x-layout.modal.modal-add modal="addEmployee" method="registerEmployee" size="">
    <x-layout.modal.modal-add-header icon="bi-plus-circle-fill" modal="addEmployee">
        {{ $config['title'] }}

        <x-slot:identifier>
            <span class="text-primary">{{ $name }}</span>
            <br>
            {{ $pis }}
        </x-slot>
    </x-layout.modal.modal-add-header>

    <x-layout.modal.modal-add-body method="registerEmployee">

{{-- conteúdo --}}
<x-layout.modal.modal-add-body-group>
    <x-layout.modal.modal-add-body-group-item columms="6">
        <x-layout.modal.modal-add-body-group-item-label item="date" title="DATA" plus="none"/>

        <input type="date" wire:model="date" class="form-control form-control-sm" id="date" min="{{ $month }}-01" max="{{ $month }}-{{ $month_end }}">

        <x-layout.modal.modal-add-body-group-item-error item="date" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>

    <x-layout.modal.modal-add-body-group-item columms="6">
        <x-layout.modal.modal-add-body-group-item-label item="time" title="HORÁRIO" plus="none"/>

        <input type="time" wire:model="time" class="form-control form-control-sm" id="time">

        <x-layout.modal.modal-add-body-group-item-error item="time" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>
</x-layout.modal.modal-add-body-group>

{{-- conteúdo --}}

    </x-layout.modal.modal-add-body>

    <x-layout.modal.modal-add-footer/>
</x-layout.modal.modal-add>
