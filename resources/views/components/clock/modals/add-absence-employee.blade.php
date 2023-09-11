<x-layout.modal.modal-add modal="addAbsenceEmployee" method="registerAbsenceEmployee" size="">
    <x-layout.modal.modal-add-header icon="bi-clipboard-x" modal="addAbsenceEmployee">
        Falta do Funcionário

        <x-slot:identifier>
            <span class="text-primary">{{ $clockemployee_employee_name }}</span>
            <br>
            {{ $clockemployee_employee_pis }}
        </x-slot>
    </x-layout.modal.modal-add-header>

    <x-layout.modal.modal-add-body method="registerAbsenceEmployee">

{{-- conteúdo --}}
<x-layout.modal.modal-add-body-group>
    <x-layout.modal.modal-add-body-group-item columms="6">
        <x-layout.modal.modal-add-body-group-item-label item="date_start" title="INÍCIO" plus="none"/>

        <input type="date" wire:model="date_start" class="form-control form-control-sm" id="date_start">

        <x-layout.modal.modal-add-body-group-item-error item="date_start" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>

    <x-layout.modal.modal-add-body-group-item columms="6">
        <x-layout.modal.modal-add-body-group-item-label item="date_end" title="FINAL" plus="none"/>

        <input type="date" wire:model="date_end" class="form-control form-control-sm" id="date_end">

        <x-layout.modal.modal-add-body-group-item-error item="date_end" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>
</x-layout.modal.modal-add-body-group>
{{-- conteúdo --}}

    </x-layout.modal.modal-add-body>

    <x-layout.modal.modal-add-footer/>
</x-layout.modal.modal-add>
